<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('access-admin');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('slug') ?: $this->input('name')),
            'featured' => $this->boolean('featured'),
            'is_active' => $this->has('is_active') ? $this->boolean('is_active') : true,
            'stock_quantity' => (int) $this->input('stock_quantity', 0),
        ]);
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique(Product::class, 'slug')->ignore($product?->id),
            ],
            'sex' => ['required', Rule::in(Product::SEXES)],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'description' => ['nullable', 'string'],
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Product::class, 'sku')->ignore($product?->id),
            ],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'featured' => ['boolean'],
            'is_active' => ['boolean'],
            'sizes' => ['array'],
            'sizes.*' => ['integer', 'exists:sizes,id'],
            'colors' => ['array'],
            'colors.*' => ['integer', 'exists:colors,id'],
            'images' => ['array'],
            'images.*' => ['image', 'max:4096'],
            'delete_images' => ['array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
            'image_order' => ['array'],
            'image_order.*' => ['integer', 'min:0', 'max:1000'],
            'main_image_id' => ['nullable', 'integer', 'exists:product_images,id'],
        ];
    }
}
