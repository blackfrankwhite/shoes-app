<?php

namespace App\Http\Requests\Admin;

use App\Models\Color;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ColorRequest extends FormRequest
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
        $color = $this->route('color');

        return [
            'name' => ['required', 'string', 'max:255'],
            'name_translations' => ['array'],
            'name_translations.en' => ['nullable', 'string', 'max:255'],
            'name_translations.ru' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique(Color::class, 'slug')->ignore($color?->id),
            ],
            'hex_code' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => ['boolean'],
        ];
    }
}
