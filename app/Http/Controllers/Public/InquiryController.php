<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\InquiryRequest;
use App\Models\Product;
use App\Support\ProductData;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InquiryController extends Controller
{
    public function create(string $locale, Product $product): Response
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'sizes', 'colors', 'images.color']);

        return Inertia::render('Public/Inquiries/Create', [
            'product' => ProductData::detail($product),
        ]);
    }

    public function store(InquiryRequest $request, string $locale, Product $product): RedirectResponse
    {
        $product->inquiries()->create([
            ...$request->validated(),
            'status' => 'new',
        ]);

        return redirect()
            ->route('products.show', ['locale' => app()->getLocale(), 'product' => $product])
            ->with('success', __('app.flash.reservation_received'));
    }
}
