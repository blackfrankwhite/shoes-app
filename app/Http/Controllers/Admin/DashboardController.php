<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $request->user()->can('access-admin') ?: abort(403);

        $recentInquiries = Inquiry::query()
            ->with(['product', 'size', 'color'])
            ->latest()
            ->limit(6)
            ->get()
            ->map(fn (Inquiry $inquiry): array => [
                'id' => $inquiry->id,
                'name' => $inquiry->name,
                'phone' => $inquiry->phone,
                'status' => $inquiry->status,
                'product' => $inquiry->product?->translated('name'),
                'created_at' => $inquiry->created_at?->toFormattedDateString(),
            ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'products' => Product::count(),
                'active_products' => Product::query()->where('is_active', true)->count(),
                'categories' => Category::count(),
                'sizes' => Size::count(),
                'colors' => Color::count(),
                'new_inquiries' => Inquiry::query()->where('status', 'new')->count(),
                'total_inquiries' => Inquiry::count(),
            ],
            'recentInquiries' => $recentInquiries,
        ]);
    }
}
