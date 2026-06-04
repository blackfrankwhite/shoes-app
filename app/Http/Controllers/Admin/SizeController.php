<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SizeRequest;
use App\Models\Size;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SizeController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Size::class);

        return Inertia::render('Admin/Sizes/Index', [
            'sizes' => Size::query()->orderBy('sort_order')->orderBy('value')->paginate(20),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Size::class);

        return Inertia::render('Admin/Sizes/Form', ['size' => null]);
    }

    public function store(SizeRequest $request): RedirectResponse
    {
        $this->authorize('create', Size::class);

        Size::create($request->validated());

        return redirect()->route('admin.sizes.index')->with('success', __('app.flash.size_created'));
    }

    public function edit(Size $size): Response
    {
        $this->authorize('update', $size);

        return Inertia::render('Admin/Sizes/Form', ['size' => $size]);
    }

    public function update(SizeRequest $request, Size $size): RedirectResponse
    {
        $this->authorize('update', $size);

        $size->update($request->validated());

        return redirect()->route('admin.sizes.index')->with('success', __('app.flash.size_updated'));
    }

    public function destroy(Size $size): RedirectResponse
    {
        $this->authorize('delete', $size);

        $size->delete();

        return redirect()->route('admin.sizes.index')->with('success', __('app.flash.size_deleted'));
    }
}
