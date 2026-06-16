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
        Storage::fake(config('filesystems.default'));

        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $size = Size::factory()->create(['label' => 'EU 42', 'value' => '42']);
        $color = Color::factory()->create(['name' => 'Black', 'slug' => 'black']);
        $secondColor = Color::factory()->create(['name' => 'Tan', 'slug' => 'tan']);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'category_id' => $category->id,
                'name' => 'Admin Created Shoe',
                'slug' => 'admin-created-shoe',
                'sex' => 'unisex',
                'price' => 199.50,
                'description' => 'Created from admin CRUD test.',
                'stock_quantity' => 9,
                'featured' => true,
                'is_active' => true,
                'sizes' => [$size->id],
                'colors' => [$color->id, $secondColor->id],
                'color_skus' => [
                    $color->id => 'ADMIN-BLK',
                    $secondColor->id => 'ADMIN-TAN',
                ],
                'images' => [UploadedFile::fake()->image('shoe.png', 900, 1100)],
                'new_image_color_ids' => [$color->id],
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $product = Product::query()->where('slug', 'admin-created-shoe')->firstOrFail();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Admin Created Shoe',
            'slug' => 'admin-created-shoe',
            'sku' => null,
        ]);
        $this->assertDatabaseHas('color_product', [
            'product_id' => $product->id,
            'color_id' => $color->id,
            'sku' => 'ADMIN-BLK',
        ]);
        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id,
            'color_id' => $color->id,
        ]);

        $image = $product->images()->firstOrFail();
        $this->actingAs($admin)
            ->put(route('admin.products.update', $product), [
                'category_id' => $category->id,
                'name' => 'Admin Updated Shoe',
                'slug' => 'admin-updated-shoe',
                'sex' => 'women',
                'price' => 219.00,
                'description' => 'Updated from admin CRUD test.',
                'stock_quantity' => 5,
                'featured' => false,
                'is_active' => true,
                'sizes' => [$size->id],
                'colors' => [$color->id, $secondColor->id],
                'color_skus' => [
                    $color->id => 'ADMIN-BLK-UPDATED',
                    $secondColor->id => 'ADMIN-TAN-UPDATED',
                ],
                'image_color_ids' => [$image->id => $secondColor->id],
                'main_image_id' => $image->id,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $product->refresh();
        $this->assertSame('Admin Updated Shoe', $product->name);
        $this->assertSame('women', $product->sex);
        $this->assertSame(5, $product->stock_quantity);
        $this->assertNull($product->sku);
        $this->assertDatabaseHas('color_product', [
            'product_id' => $product->id,
            'color_id' => $secondColor->id,
            'sku' => 'ADMIN-TAN-UPDATED',
        ]);
        $this->assertDatabaseHas('product_images', [
            'id' => $image->id,
            'color_id' => $secondColor->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
