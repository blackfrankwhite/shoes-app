<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductListingFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_can_be_filtered_by_public_query_params(): void
    {
        $sneakers = Category::factory()->create(['name' => 'Sneakers', 'slug' => 'sneakers']);
        $boots = Category::factory()->create(['name' => 'Boots', 'slug' => 'boots']);
        $size42 = Size::factory()->create(['label' => 'EU 42', 'value' => '42', 'sort_order' => 42]);
        $size38 = Size::factory()->create(['label' => 'EU 38', 'value' => '38', 'sort_order' => 38]);
        $black = Color::factory()->create(['name' => 'Black', 'slug' => 'black']);
        $tan = Color::factory()->create(['name' => 'Tan', 'slug' => 'tan']);

        $matching = Product::factory()->create([
            'category_id' => $sneakers->id,
            'name' => 'Filter Match Runner',
            'slug' => 'filter-match-runner',
            'sku' => 'MATCH-001',
            'sex' => 'men',
            'price' => 190,
            'is_active' => true,
        ]);
        $matching->sizes()->attach($size42);
        $matching->colors()->attach($black);

        $other = Product::factory()->create([
            'category_id' => $boots->id,
            'name' => 'Other Boot',
            'slug' => 'other-boot',
            'sku' => 'OTHER-001',
            'sex' => 'women',
            'price' => 260,
            'is_active' => true,
        ]);
        $other->sizes()->attach($size38);
        $other->colors()->attach($tan);

        Product::factory()->create([
            'category_id' => $sneakers->id,
            'name' => 'Inactive Match',
            'slug' => 'inactive-match',
            'sku' => 'MATCH-002',
            'sex' => 'men',
            'price' => 180,
            'is_active' => false,
        ]);

        $response = $this->get(route('products.index', [
            'locale' => 'ka',
            'search' => 'MATCH',
            'category' => 'sneakers',
            'sex' => 'men',
            'size' => '42',
            'color' => 'black',
            'min_price' => 100,
            'max_price' => 200,
        ]));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Public/Products/Index')
            ->has('products.data', 1)
            ->where('products.data.0.slug', $matching->slug)
            ->where('products.data.0.sku', $matching->sku)
        );
    }
}
