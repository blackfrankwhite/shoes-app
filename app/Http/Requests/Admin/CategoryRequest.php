<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('access-admin');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
            'is_active' => $this->has('is_active') ? $this->boolean('is_active') : true,
        ]);
    }

    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'name' => ['required', 'string', 'max:255'],
            'name_translations' => ['array'],
            'name_translations.en' => ['nullable', 'string', 'max:255'],
            'name_translations.ru' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique(Category::class, 'slug')->ignore($category?->id),
            ],
            'description' => ['nullable', 'string'],
            'description_translations' => ['array'],
            'description_translations.en' => ['nullable', 'string'],
            'description_translations.ru' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
