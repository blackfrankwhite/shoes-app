<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasTranslations;

    public const SEXES = ['men', 'women', 'kids', 'unisex'];

    protected $fillable = [
        'category_id',
        'name',
        'name_translations',
        'slug',
        'sex',
        'price',
        'sale_percent',
        'sale_enabled',
        'description',
        'description_translations',
        'sku',
        'stock_quantity',
        'featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_percent' => 'integer',
            'sale_enabled' => 'boolean',
            'stock_quantity' => 'integer',
            'featured' => 'boolean',
            'is_active' => 'boolean',
            'name_translations' => 'array',
            'description_translations' => 'array',
        ];
    }

    public function discountPercent(): int
    {
        return app(\App\Support\StoreSale::class)->percent()
            ?: ($this->sale_enabled ? (int) $this->sale_percent : 0);
    }

    public function scopePriceBetween(Builder $query, ?float $min, ?float $max): Builder
    {
        $percent = app(\App\Support\StoreSale::class)->percent();
        $expression = $percent
            ? "ROUND(price * (100 - {$percent}) / 100, 2)"
            : '(CASE WHEN sale_enabled = 1 AND sale_percent BETWEEN 1 AND 100 THEN ROUND(price * (100 - sale_percent) / 100, 2) ELSE price END)';

        return $query
            ->when($min !== null, fn ($query) => $query->whereRaw("{$expression} >= CAST(? AS DECIMAL(10, 2))", [$min]))
            ->when($max !== null, fn ($query) => $query->whereRaw("{$expression} <= CAST(? AS DECIMAL(10, 2))", [$max]));
    }

    public function isOnSale(): bool
    {
        return $this->discountPercent() > 0 && $this->discountPercent() <= 100 && $this->price > 0;
    }

    public function currentPrice(): float
    {
        return $this->isOnSale()
            ? round((float) $this->price * (100 - $this->discountPercent()) / 100, 2)
            : (float) $this->price;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class)->orderBy('sort_order')->orderBy('value');
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class)->withPivot('sku')->orderBy('name');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function mainImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_main', true)->orderBy('sort_order')->orderBy('id');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }
}
