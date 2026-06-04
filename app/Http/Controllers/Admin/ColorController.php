<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ColorRequest;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ColorController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Color::class);

        return Inertia::render('Admin/Colors/Index', [
            'colors' => Color::query()
                ->orderBy('name')
                ->paginate(20)
                ->through(fn (Color $color): array => [
                    'id' => $color->id,
                    'name' => $color->translated('name'),
                    'base_name' => $color->name,
                    'slug' => $color->slug,
                    'hex_code' => $color->hex_code,
                    'is_active' => (bool) $color->is_active,
                ]),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Color::class);

        return Inertia::render('Admin/Colors/Form', ['color' => null]);
    }

    public function store(ColorRequest $request): RedirectResponse
    {
        $this->authorize('create', Color::class);

        Color::create($request->validated());

        return redirect()->route('admin.colors.index')->with('success', __('app.flash.color_created'));
    }

    public function edit(Color $color): Response
    {
        $this->authorize('update', $color);

        return Inertia::render('Admin/Colors/Form', ['color' => $color]);
    }

    public function update(ColorRequest $request, Color $color): RedirectResponse
    {
        $this->authorize('update', $color);

        $color->update($request->validated());

        return redirect()->route('admin.colors.index')->with('success', __('app.flash.color_updated'));
    }

    public function destroy(Color $color): RedirectResponse
    {
        $this->authorize('delete', $color);

        $color->delete();

        return redirect()->route('admin.colors.index')->with('success', __('app.flash.color_deleted'));
    }
}
