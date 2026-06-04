<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_product(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $size = Size::factory()->create(['label' => 'EU 42', 'value' => '42']);
        $color = Color::factory()->create(['name' => 'Black', 'slug' => 'black']);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'name' => 'Admin Created Shoe',
                'slug' => 'admin-created-shoe',
                'sex' => 'unisex',
                'price' => 199.50,
                'description' => 'Created from admin CRUD test.',
                'sku' => 'CRUD-001',
                'stock_quantity' => 9,
                'featured' => true,
                'is_active' => true,
                'sizes' => [$size->id],
                'colors' => [$color->id],
                'images' => [UploadedFile::fake()->image('shoe.png', 900, 1100)],
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $product = Product::query()->where('sku', 'CRUD-001')->firstOrFail();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Admin Created Shoe',
            'slug' => 'admin-created-shoe',
        ]);
        $this->assertDatabaseCount('product_images', 1);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'category_id' => $category->id,
                'name' => 'Admin Updated Shoe',
                'slug' => 'admin-updated-shoe',
                'sex' => 'women',
                'price' => 219.00,
                'description' => 'Updated from admin CRUD test.',
                'sku' => 'CRUD-001',
                'stock_quantity' => 5,
                'featured' => false,
                'is_active' => true,
                'sizes' => [$size->id],
                'colors' => [$color->id],
                'main_image_id' => $product->images()->first()->id,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $product->refresh();
        $this->assertSame('Admin Updated Shoe', $product->name);
        $this->assertSame('women', $product->sex);
        $this->assertSame(5, $product->stock_quantity);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
