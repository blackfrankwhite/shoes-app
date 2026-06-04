<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\ProductData;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $featuredProducts = Product::query()
            ->active()
            ->where('featured', true)
            ->with(['category', 'images'])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn (Product $product): array => ProductData::card($product));

        $categories = Category::query()
            ->where('is_active', true)
            ->withCount(['products' => fn ($query) => $query->active()])
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category): array => [
                'id' => $category->id,
                'name' => $category->translated('name'),
                'slug' => $category->slug,
                'description' => $category->translated('description'),
                'products_count' => $category->products_count,
            ]);

        return Inertia::render('Public/Home', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
        ]);
    }
}
