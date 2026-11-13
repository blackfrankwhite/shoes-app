<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function value(string $key): ?string
    {
        return static::query()->where('key', $key)->value('value');
    }

    public static function set(string $key, ?string $value): void
    {
        request()->attributes->remove('store_sale');

        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }
}
