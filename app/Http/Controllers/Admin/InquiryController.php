<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Support\ProductData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class InquiryController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Inquiry::class);

        $filters = $request->only(['status', 'search']);

        $inquiries = Inquiry::query()
            ->with(['product.category', 'product.images', 'product.colors', 'size', 'color'])
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('product', fn ($query) => $query
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhereHas('colors', fn ($query) => $query->where('color_product.sku', 'like', "%{$search}%")));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString()
            ->through(fn (Inquiry $inquiry): array => $this->inquiryData($inquiry));

        return Inertia::render('Admin/Inquiries/Index', [
            'inquiries' => $inquiries,
            'filters' => $filters,
            'statuses' => Inquiry::STATUSES,
        ]);
    }

    public function show(Inquiry $inquiry): Response
    {
        $this->authorize('view', $inquiry);

        $inquiry->load(['product.category', 'product.images.color', 'product.sizes', 'product.colors', 'size', 'color']);

        return Inertia::render('Admin/Inquiries/Show', [
            'inquiry' => [
                ...$this->inquiryData($inquiry),
                'email' => $inquiry->email,
                'quantity' => $inquiry->quantity,
                'comment' => $inquiry->comment,
                'product_detail' => ProductData::detail($inquiry->product),
            ],
            'statuses' => Inquiry::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $this->authorize('update', $inquiry);

        $validated = $request->validate([
            'status' => ['required', Rule::in(Inquiry::STATUSES)],
        ]);

        $inquiry->update($validated);

        return back()->with('success', __('app.flash.inquiry_status_updated'));
    }

    private function inquiryData(Inquiry $inquiry): array
    {
        return [
            'id' => $inquiry->id,
            'name' => $inquiry->name,
            'phone' => $inquiry->phone,
            'status' => $inquiry->status,
            'quantity' => $inquiry->quantity,
            'size' => $inquiry->size?->label,
            'color' => $inquiry->color?->name,
            'product' => $inquiry->product ? ProductData::card($inquiry->product) : null,
            'created_at' => $inquiry->created_at?->toDayDateTimeString(),
        ];
    }
}
