<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use App\Support\ProductData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Product::class);

        $filters = $request->only(['search', 'category', 'active']);

        $products = Product::query()
            ->with(['category', 'images', 'colors'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('name_translations->en', 'like', "%{$search}%")
                        ->orWhere('name_translations->ru', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhereHas('colors', fn ($query) => $query->where('color_product.sku', 'like', "%{$search}%"));
                });
            })
            ->when($filters['category'] ?? null, fn ($query, string $slug) => $query->whereHas('category', fn ($query) => $query->where('slug', $slug)))
            ->when(isset($filters['active']) && $filters['active'] !== '', fn ($query) => $query->where('is_active', filter_var($filters['active'], FILTER_VALIDATE_BOOLEAN)))
            ->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn (Product $product): array => ProductData::card($product));

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'filters' => $filters,
            'categories' => Category::query()
                ->orderBy('name')
                ->get()
                ->map(fn (Category $category): array => [
                    'id' => $category->id,
                    'name' => $category->translated('name'),
                    'slug' => $category->slug,
                ]),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Product::class);

        return Inertia::render('Admin/Products/Form', [
            'product' => null,
            'options' => $this->options(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $validated = $request->validated();
        $product = Product::create($this->productData($validated));

        $product->sizes()->sync($validated['sizes'] ?? []);
        $this->syncColors($product, $validated);
        $this->syncImages($product, $request, $validated);

        return redirect()->route('admin.products.show', $product)->with('success', __('app.flash.product_created'));
    }

    public function show(Product $product): Response
    {
        $this->authorize('view', $product);

        $product->load(['category', 'sizes', 'colors', 'images.color']);

        return Inertia::render('Admin/Products/Show', [
            'product' => ProductData::detail($product),
        ]);
    }

    public function edit(Product $product): Response
    {
        $this->authorize('update', $product);

        $product->load(['category', 'sizes', 'colors', 'images.color']);

        return Inertia::render('Admin/Products/Form', [
            'product' => ProductData::detail($product),
            'options' => $this->options(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $validated = $request->validated();
        $product->update($this->productData($validated));
        $product->sizes()->sync($validated['sizes'] ?? []);
        $this->syncColors($product, $validated);
        $this->syncImages($product, $request, $validated);

        return redirect()->route('admin.products.show', $product)->with('success', __('app.flash.product_updated'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $product->images->each(fn (ProductImage $image) => $this->deleteImageFile($image->path));
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', __('app.flash.product_deleted'));
    }

    private function options(): array
    {
        return [
            'categories' => Category::query()
                ->orderBy('name')
                ->get()
                ->map(fn (Category $category): array => [
                    'id' => $category->id,
                    'name' => $category->translated('name'),
                ]),
            'sizes' => Size::query()->orderBy('sort_order')->orderBy('value')->get(['id', 'label', 'value']),
            'colors' => Color::query()
                ->orderBy('name')
                ->get()
                ->map(fn (Color $color): array => [
                    'id' => $color->id,
                    'name' => $color->translated('name'),
                    'hex_code' => $color->hex_code,
                ]),
            'sexes' => Product::SEXES,
        ];
    }

    private function productData(array $validated): array
    {
        return collect($validated)->only([
            'category_id',
            'name',
            'name_translations',
            'slug',
            'sex',
            'price',
            'description',
            'description_translations',
            'sku',
            'stock_quantity',
            'featured',
            'is_active',
        ])->all();
    }

    private function syncColors(Product $product, array $validated): void
    {
        $colorSkus = collect($validated['color_skus'] ?? []);
        $colors = collect($validated['colors'] ?? [])
            ->mapWithKeys(fn (mixed $colorId): array => [
                (int) $colorId => ['sku' => trim((string) $colorSkus->get((int) $colorId))],
            ])
            ->all();

        $product->colors()->sync($colors);
    }

    private function syncImages(Product $product, ProductRequest $request, array $validated): void
    {
        $validColorIds = $product->colors()->pluck('colors.id')->map(fn (int $id): int => $id)->all();
        $normalizeColorId = fn (mixed $colorId): ?int => in_array((int) $colorId, $validColorIds, true) ? (int) $colorId : null;

        foreach ($validated['delete_images'] ?? [] as $imageId) {
            $image = $product->images()->whereKey($imageId)->first();

            if ($image) {
                $this->deleteImageFile($image->path);
                $image->delete();
            }
        }

        foreach ($validated['image_order'] ?? [] as $imageId => $sortOrder) {
            $product->images()->whereKey($imageId)->update(['sort_order' => (int) $sortOrder]);
        }

        foreach ($validated['image_color_ids'] ?? [] as $imageId => $colorId) {
            $product->images()->whereKey($imageId)->update(['color_id' => $normalizeColorId($colorId)]);
        }

        $nextSortOrder = ((int) $product->images()->max('sort_order')) + 1;
        foreach ($request->file('images', []) as $index => $file) {
            $product->images()->create([
                'color_id' => $normalizeColorId($validated['new_image_color_ids'][$index] ?? null),
                'path' => $file->store('products'),
                'alt_text' => $product->name,
                'sort_order' => $nextSortOrder++,
                'is_main' => false,
            ]);
        }

        if (! empty($validated['main_image_id']) && $product->images()->whereKey($validated['main_image_id'])->exists()) {
            $product->images()->update(['is_main' => false]);
            $product->images()->whereKey($validated['main_image_id'])->update(['is_main' => true]);
        }

        if (! $product->images()->where('is_main', true)->exists()) {
            $product->images()->orderBy('sort_order')->orderBy('id')->first()?->update(['is_main' => true]);
        }
    }

    private function deleteImageFile(string $path): void
    {
        if (! Str::startsWith($path, ['/', 'http://', 'https://'])) {
            Storage::delete($path);
        }
    }
}
