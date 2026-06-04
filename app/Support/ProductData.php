<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductData
{
    public static function imageUrl(?string $path): string
    {
        if (! $path) {
            return '/images/placeholders/shoe-1.png';
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::url($path);
    }

    public static function card(Product $product): array
    {
        $mainImage = $product->relationLoaded('images')
            ? ($product->images->firstWhere('is_main', true) ?? $product->images->first())
            : null;

        return [
            'id' => $product->id,
            'name' => $product->translated('name'),
            'base_name' => $product->name,
            'name_translations' => $product->name_translations ?? [],
            'slug' => $product->slug,
            'sku' => $product->sku,
            'sex' => $product->sex,
            'price' => (float) $product->price,
            'formatted_price' => 'GEL '.number_format((float) $product->price, 2),
            'stock_quantity' => $product->stock_quantity,
            'featured' => (bool) $product->featured,
            'is_active' => (bool) $product->is_active,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->translated('name'),
                'base_name' => $product->category->name,
                'slug' => $product->category->slug,
            ] : null,
            'image' => self::imageUrl($mainImage?->path),
        ];
    }

    public static function detail(Product $product): array
    {
        $images = $product->images->map(fn ($image): array => [
            'id' => $image->id,
            'color_id' => $image->color_id,
            'color' => $image->color ? [
                'id' => $image->color->id,
                'name' => $image->color->translated('name'),
                'hex_code' => $image->color->hex_code,
            ] : null,
            'path' => $image->path,
            'url' => self::imageUrl($image->path),
            'alt_text' => $image->alt_text,
            'sort_order' => $image->sort_order,
            'is_main' => (bool) $image->is_main,
        ])->values();

        if ($images->isEmpty()) {
            $images = collect([[
                'id' => 0,
                'path' => null,
                'url' => self::imageUrl(null),
                'alt_text' => $product->name,
                'sort_order' => 0,
                'is_main' => true,
            ]]);
        }

        return [
            ...self::card($product),
            'description' => $product->translated('description'),
            'base_description' => $product->description,
            'description_translations' => $product->description_translations ?? [],
            'sizes' => $product->sizes->map(fn ($size): array => [
                'id' => $size->id,
                'label' => $size->label,
                'value' => $size->value,
            ])->values(),
            'colors' => $product->colors->map(fn ($color): array => [
                'id' => $color->id,
                'name' => $color->translated('name'),
                'base_name' => $color->name,
                'slug' => $color->slug,
                'hex_code' => $color->hex_code,
            ])->values(),
            'images' => $images,
        ];
    }
}
