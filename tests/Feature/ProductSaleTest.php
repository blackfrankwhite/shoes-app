<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Support\ProductData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductSaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_prepare_start_and_end_a_percentage_sale(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['price' => 199.50]);
        $data = [
            'category_id' => $product->category_id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sex' => $product->sex,
            'price' => 199.50,
            'stock_quantity' => 5,
            'sale_percent' => 25,
            'sale_enabled' => false,
        ];

        foreach ([false, true, false] as $enabled) {
            $this->actingAs($admin)->put(route('admin.products.update', $product), [
                ...$data, 'sale_enabled' => $enabled,
            ])->assertSessionHasNoErrors()->assertRedirect();

            $product->refresh();
            $card = ProductData::card($product);
            $this->assertSame($enabled, $card['on_sale']);
            $this->assertSame($enabled ? 'GEL 149.63' : 'GEL 199.50', $card['formatted_price']);
            $this->assertSame('GEL 199.50', $card['formatted_original_price']);
            $this->assertSame('199.50', $product->price);
            $this->assertSame(25, $product->sale_percent);
        }
    }

    public function test_storewide_sale_applies_to_all_products_and_can_be_ended(): void
    {
        $admin = User::factory()->admin()->create();
        $first = Product::factory()->create(['price' => 200, 'is_active' => true]);
        $second = Product::factory()->create(['price' => 100, 'sale_percent' => 10, 'sale_enabled' => true, 'is_active' => true]);

        $this->actingAs($admin)->post(route('admin.storefront.update'), [
            'sale_enabled' => true, 'sale_percent' => 25,
        ])->assertSessionHasNoErrors()->assertRedirect();

        $this->assertSame(150.0, $first->currentPrice());
        $this->assertSame(75.0, $second->currentPrice());
        $this->assertSame(25, ProductData::card($second)['discount_percent']);
        $this->get(route('products.index', ['locale' => 'ka', 'max_price' => 160]))
            ->assertOk()->assertInertia(fn (Assert $page) => $page->has('products.data', 2));

        $this->post(route('admin.storefront.update'), [
            'sale_enabled' => false, 'sale_percent' => 25,
        ])->assertSessionHasNoErrors();
        $this->assertSame(200.0, $first->currentPrice());
        $this->assertSame(90.0, $second->currentPrice());
    }

    public function test_storewide_sale_requires_a_valid_percentage_and_admin_access(): void
    {
        $this->post(route('admin.storefront.update'), ['sale_enabled' => true, 'sale_percent' => 25])->assertRedirect();
        $this->actingAs(User::factory()->create())->post(route('admin.storefront.update'), ['sale_enabled' => true, 'sale_percent' => 25])->assertForbidden();
        $this->actingAs(User::factory()->admin()->create());
        foreach ([null, 0, 101, 12.5] as $percent) {
            $this->post(route('admin.storefront.update'), [
                'sale_enabled' => true, 'sale_percent' => $percent,
            ])->assertSessionHasErrors('sale_percent');
        }
    }

    public function test_invalid_sale_percentages_are_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create();
        foreach ([null, 0, -10, 101, 12.5] as $percent) {
            $this->actingAs($admin)->put(route('admin.products.update', $product), [
                'category_id' => $product->category_id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sex' => $product->sex,
                'price' => 200,
                'stock_quantity' => 5,
                'sale_enabled' => true,
                'sale_percent' => $percent,
            ])->assertSessionHasErrors('sale_percent');
        }
    }

    public function test_public_prices_and_filters_follow_sale_toggle(): void
    {
        $product = Product::factory()->create([
            'price' => 200, 'sale_percent' => 25, 'sale_enabled' => true, 'is_active' => true,
        ]);
        $url = route('products.index', ['locale' => 'ka', 'min_price' => 140, 'max_price' => 160]);
        $this->get($url)->assertOk()->assertInertia(fn (Assert $page) => $page
            ->has('products.data', 1)
            ->where('products.data.0.current_price', 150)
            ->where('products.data.0.on_sale', true)
        );
        $product->update(['sale_enabled' => false]);
        $this->get($url)->assertOk()->assertInertia(fn (Assert $page) => $page->has('products.data', 0));
    }
}
