<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InquiryCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_valid_product_inquiry(): void
    {
        $category = Category::factory()->create();
        $size = Size::factory()->create(['label' => 'EU 41', 'value' => '41']);
        $color = Color::factory()->create(['name' => 'Black', 'slug' => 'black']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'slug' => 'reservation-product',
            'stock_quantity' => 3,
            'is_active' => true,
        ]);
        $product->sizes()->attach($size);
        $product->colors()->attach($color, ['sku' => 'RES-BLK']);

        $response = $this->post(route('inquiries.store', ['locale' => 'ka', 'product' => $product]), [
            'name' => 'Nino Beridze',
            'phone' => '+995 599 12 34 56',
            'email' => 'nino@example.com',
            'size_id' => $size->id,
            'color_id' => $color->id,
            'quantity' => 2,
            'comment' => 'Please reserve until Friday.',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('products.show', ['locale' => 'ka', 'product' => $product]));

        $this->assertDatabaseHas('inquiries', [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'color_id' => $color->id,
            'name' => 'Nino Beridze',
            'phone' => '+995 599 12 34 56',
            'quantity' => 2,
            'status' => 'new',
        ]);
    }

    public function test_inquiry_rejects_size_not_available_for_product(): void
    {
        $category = Category::factory()->create();
        $availableSize = Size::factory()->create(['value' => '40']);
        $unavailableSize = Size::factory()->create(['value' => '44']);
        $color = Color::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'is_active' => true,
        ]);
        $product->sizes()->attach($availableSize);
        $product->colors()->attach($color, ['sku' => 'RES-COLOR']);

        $response = $this
            ->from(route('inquiries.create', ['locale' => 'ka', 'product' => $product]))
            ->post(route('inquiries.store', ['locale' => 'ka', 'product' => $product]), [
                'name' => 'Nino Beridze',
                'phone' => '+995 599 12 34 56',
                'size_id' => $unavailableSize->id,
                'color_id' => $color->id,
                'quantity' => 1,
            ]);

        $response
            ->assertSessionHasErrors('size_id')
            ->assertRedirect(route('inquiries.create', ['locale' => 'ka', 'product' => $product]));

        $this->assertDatabaseCount('inquiries', 0);
    }
}
