<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Support\ProductData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'category', 'sex', 'size', 'color', 'min_price', 'max_price']);

        $products = Product::query()
            ->active()
            ->with(['category', 'images'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('name_translations->en', 'like', "%{$search}%")
                        ->orWhere('name_translations->ru', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'] ?? null, fn ($query, string $slug) => $query->whereHas('category', fn ($query) => $query->where('slug', $slug)))
            ->when($filters['sex'] ?? null, fn ($query, string $sex) => $query->where('sex', $sex))
            ->when($filters['size'] ?? null, fn ($query, string $size) => $query->whereHas('sizes', fn ($query) => $query->where('value', $size)))
            ->when($filters['color'] ?? null, fn ($query, string $color) => $query->whereHas('colors', fn ($query) => $query->where('slug', $color)))
            ->when($filters['min_price'] ?? null, fn ($query, string $price) => $query->where('price', '>=', (float) $price))
            ->when($filters['max_price'] ?? null, fn ($query, string $price) => $query->where('price', '<=', (float) $price))
            ->latest()
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Product $product): array => ProductData::card($product));

        return Inertia::render('Public/Products/Index', [
            'products' => $products,
            'filters' => $filters,
            'options' => [
                'categories' => Category::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get()
                    ->map(fn (Category $category): array => [
                        'id' => $category->id,
                        'name' => $category->translated('name'),
                        'slug' => $category->slug,
                    ]),
                'sizes' => Size::query()
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('value')
                    ->get(['id', 'label', 'value']),
                'colors' => Color::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get()
                    ->map(fn (Color $color): array => [
                        'id' => $color->id,
                        'name' => $color->translated('name'),
                        'slug' => $color->slug,
                        'hex_code' => $color->hex_code,
                    ]),
                'sexes' => Product::SEXES,
            ],
        ]);
    }

    public function show(string $locale, Product $product): Response
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'sizes', 'colors', 'images']);

        $relatedProducts = Product::query()
            ->active()
            ->whereKeyNot($product->id)
            ->where(function ($query) use ($product): void {
                $query->where('category_id', $product->category_id)
                    ->orWhere('sex', $product->sex);
            })
            ->with(['category', 'images'])
            ->limit(4)
            ->get()
            ->map(fn (Product $related): array => ProductData::card($related));

        return Inertia::render('Public/Products/Show', [
            'product' => ProductData::detail($product),
            'relatedProducts' => $relatedProducts,
            'breadcrumbs' => [
                ['label' => __('app.public.show.home'), 'href' => route('home', ['locale' => app()->getLocale()])],
                ['label' => __('app.common.products'), 'href' => route('products.index', ['locale' => app()->getLocale()])],
                ['label' => $product->category?->translated('name'), 'href' => route('products.index', ['locale' => app()->getLocale(), 'category' => $product->category?->slug])],
                ['label' => $product->translated('name'), 'href' => null],
            ],
        ]);
    }
}
