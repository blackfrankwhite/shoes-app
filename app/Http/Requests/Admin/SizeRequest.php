<?php

namespace App\Http\Requests\Admin;

use App\Models\Size;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SizeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->can('access-admin');
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'value' => $this->input('value') ?: $this->input('label'),
            'sort_order' => (int) $this->input('sort_order', 0),
            'is_active' => $this->has('is_active') ? $this->boolean('is_active') : true,
        ]);
    }

    public function rules(): array
    {
        $size = $this->route('size');

        return [
            'label' => ['required', 'string', 'max:50'],
            'value' => [
                'required',
                'string',
                'max:50',
                Rule::unique(Size::class, 'value')->ignore($size?->id),
            ],
            'sort_order' => ['required', 'integer', 'min:0', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }
}
