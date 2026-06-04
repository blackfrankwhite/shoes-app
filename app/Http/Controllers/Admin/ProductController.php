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
            ->with(['category', 'images'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
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
            'categories' => Category::query()->orderBy('name')->get(['id', 'name', 'slug']),
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
        $product->colors()->sync($validated['colors'] ?? []);
        $this->syncImages($product, $request, $validated);

        return redirect()->route('admin.products.show', $product)->with('success', 'Product created.');
    }

    public function show(Product $product): Response
    {
        $this->authorize('view', $product);

        $product->load(['category', 'sizes', 'colors', 'images']);

        return Inertia::render('Admin/Products/Show', [
            'product' => ProductData::detail($product),
        ]);
    }

    public function edit(Product $product): Response
    {
        $this->authorize('update', $product);

        $product->load(['category', 'sizes', 'colors', 'images']);

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
        $product->colors()->sync($validated['colors'] ?? []);
        $this->syncImages($product, $request, $validated);

        return redirect()->route('admin.products.show', $product)->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $product->images->each(fn (ProductImage $image) => $this->deleteImageFile($image->path));
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    private function options(): array
    {
        return [
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'sizes' => Size::query()->orderBy('sort_order')->orderBy('value')->get(['id', 'label', 'value']),
            'colors' => Color::query()->orderBy('name')->get(['id', 'name', 'hex_code']),
            'sexes' => Product::SEXES,
        ];
    }

    private function productData(array $validated): array
    {
        return collect($validated)->only([
            'category_id',
            'name',
            'slug',
            'sex',
            'price',
            'description',
            'sku',
            'stock_quantity',
            'featured',
            'is_active',
        ])->all();
    }

    private function syncImages(Product $product, ProductRequest $request, array $validated): void
    {
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

        $nextSortOrder = ((int) $product->images()->max('sort_order')) + 1;
        foreach ($request->file('images', []) as $file) {
            $product->images()->create([
                'path' => $file->store('products', 'public'),
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
            Storage::disk('public')->delete($path);
        }
    }
}
