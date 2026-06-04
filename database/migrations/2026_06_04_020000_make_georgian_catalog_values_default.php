<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $decode = fn ($value): array => is_string($value) ? (json_decode($value, true) ?: []) : ((array) $value);
        $encode = fn (array $value): string => json_encode(array_filter($value, fn ($item) => filled($item)), JSON_UNESCAPED_UNICODE);

        foreach (DB::table('categories')->select('id', 'name', 'description', 'name_translations', 'description_translations')->get() as $category) {
            $names = $decode($category->name_translations);
            $descriptions = $decode($category->description_translations);

            if (! isset($names['ka']) && ! isset($descriptions['ka'])) {
                continue;
            }

            DB::table('categories')->where('id', $category->id)->update([
                'name' => $names['ka'] ?? $category->name,
                'name_translations' => $encode([
                    'en' => $names['en'] ?? $category->name,
                    'ru' => $names['ru'] ?? null,
                ]),
                'description' => $descriptions['ka'] ?? $category->description,
                'description_translations' => $encode([
                    'en' => $descriptions['en'] ?? $category->description,
                    'ru' => $descriptions['ru'] ?? null,
                ]),
            ]);
        }

        foreach (DB::table('colors')->select('id', 'name', 'name_translations')->get() as $color) {
            $names = $decode($color->name_translations);

            if (! isset($names['ka'])) {
                continue;
            }

            DB::table('colors')->where('id', $color->id)->update([
                'name' => $names['ka'],
                'name_translations' => $encode([
                    'en' => $names['en'] ?? $color->name,
                    'ru' => $names['ru'] ?? null,
                ]),
            ]);
        }

        foreach (DB::table('products')->select('id', 'name', 'description', 'name_translations', 'description_translations')->get() as $product) {
            $names = $decode($product->name_translations);
            $descriptions = $decode($product->description_translations);

            if (! isset($names['ka']) && ! isset($descriptions['ka'])) {
                continue;
            }

            DB::table('products')->where('id', $product->id)->update([
                'name' => $names['ka'] ?? $product->name,
                'name_translations' => $encode([
                    'en' => $names['en'] ?? $product->name,
                    'ru' => $names['ru'] ?? null,
                ]),
                'description' => $descriptions['ka'] ?? $product->description,
                'description_translations' => $encode([
                    'en' => $descriptions['en'] ?? $product->description,
                    'ru' => $descriptions['ru'] ?? null,
                ]),
            ]);
        }
    }
};
