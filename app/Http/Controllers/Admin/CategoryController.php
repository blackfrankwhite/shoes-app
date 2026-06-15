<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Support\ProductData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Category::class);

        return Inertia::render('Admin/Categories/Index', [
            'categories' => Category::query()
                ->withCount('products')
                ->orderBy('name')
                ->paginate(15)
                ->through(fn (Category $category): array => [
                    'id' => $category->id,
                    'name' => $category->translated('name'),
                    'base_name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->translated('description'),
                    'image_url' => $category->image_path ? ProductData::imageUrl($category->image_path) : null,
                    'products_count' => $category->products_count,
                    'is_active' => (bool) $category->is_active,
                ]),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Category::class);

        return Inertia::render('Admin/Categories/Form', [
            'category' => null,
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $this->authorize('create', Category::class);

        $validated = $request->validated();
        $category = Category::create($this->categoryData($validated));
        $this->syncImage($category, $request);

        return redirect()->route('admin.categories.index')->with('success', __('app.flash.category_created'));
    }

    public function edit(Category $category): Response
    {
        $this->authorize('update', $category);

        return Inertia::render('Admin/Categories/Form', [
            'category' => [
                ...$category->toArray(),
                'image_url' => $category->image_path ? ProductData::imageUrl($category->image_path) : null,
            ],
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);

        $validated = $request->validated();
        $category->update($this->categoryData($validated));
        $this->syncImage($category, $request);

        return redirect()->route('admin.categories.index')->with('success', __('app.flash.category_updated'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        $this->deleteImageFile($category->image_path);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', __('app.flash.category_deleted'));
    }

    private function categoryData(array $validated): array
    {
        return collect($validated)->only([
            'name',
            'name_translations',
            'slug',
            'description',
            'description_translations',
            'is_active',
        ])->all();
    }

    private function syncImage(Category $category, CategoryRequest $request): void
    {
        if ($request->boolean('remove_image') || $request->hasFile('image')) {
            $this->deleteImageFile($category->image_path);
            $category->forceFill(['image_path' => null])->save();
        }

        if ($request->hasFile('image')) {
            $category->forceFill([
                'image_path' => $request->file('image')->store('categories'),
            ])->save();
        }
    }

    private function deleteImageFile(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['/', 'http://', 'https://'])) {
            Storage::delete($path);
        }
    }
}
