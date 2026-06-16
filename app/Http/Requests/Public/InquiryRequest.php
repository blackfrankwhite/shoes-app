<?php

namespace App\Http\Requests\Public;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class InquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('product') instanceof Product;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9\s().-]{7,24}$/'],
            'address' => ['required', 'string', 'max:500'],
            'size_id' => ['required', 'integer', 'exists:sizes,id'],
            'color_id' => ['required', 'integer', 'exists:colors,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:50'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $product = $this->route('product');

                if (! $product instanceof Product || ! $product->is_active) {
                    $validator->errors()->add('product', __('app.validation.product_unavailable'));

                    return;
                }

                if (! $product->sizes()->whereKey($this->integer('size_id'))->exists()) {
                    $validator->errors()->add('size_id', __('app.validation.size_unavailable'));
                }

                if (! $product->colors()->whereKey($this->integer('color_id'))->exists()) {
                    $validator->errors()->add('color_id', __('app.validation.color_unavailable'));
                }

                if ($product->stock_quantity > 0 && $this->integer('quantity') > $product->stock_quantity) {
                    $validator->errors()->add('quantity', __('app.validation.quantity_over_stock'));
                }
            },
        ];
    }
}
