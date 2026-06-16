<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_locale_is_read_from_public_url(): void
    {
        $this->get(route('home', ['locale' => 'ka']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('i18n.locale', 'ka')
                ->where('i18n.translations.app_name', 'Un Shoes')
                ->where('i18n.translations.nav.products', 'პროდუქცია')
            );

        $this->get(route('products.index', ['locale' => 'en']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('i18n.locale', 'en')
                ->where('i18n.translations.nav.products', 'Products')
            );
    }

    public function test_invalid_url_locale_is_not_routed(): void
    {
        $this->get('/de/products')->assertNotFound();
    }

    public function test_catalog_names_use_selected_locale_and_fallback_to_georgian(): void
    {
        $category = Category::factory()->create([
            'name' => 'კედები',
            'slug' => 'sneakers',
            'name_translations' => ['en' => 'Sneakers', 'ru' => 'Кроссовки'],
            'description' => 'ქართული აღწერა',
            'description_translations' => ['en' => 'English description', 'ru' => 'Русское описание'],
        ]);
        $color = Color::factory()->create([
            'name' => 'შავი',
            'slug' => 'black',
            'name_translations' => ['en' => 'Black', 'ru' => 'Черный'],
        ]);
        $size = Size::factory()->create(['label' => 'EU 42', 'value' => '42']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'ფაბრიკის რანერი',
            'slug' => 'factory-runner',
            'sku' => 'TR-001',
            'description' => 'ქართული პროდუქტის აღწერა',
            'name_translations' => ['en' => 'Factory Runner', 'ru' => 'Фабричный раннер'],
            'description_translations' => ['en' => 'English product description', 'ru' => 'Русское описание товара'],
            'is_active' => true,
        ]);
        $product->colors()->attach($color, ['sku' => 'TR-001-BLK']);
        $product->sizes()->attach($size);

        $this->get(route('products.index', ['locale' => 'en']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('products.data.0.name', 'Factory Runner')
                ->where('products.data.0.category.name', 'Sneakers')
                ->where('options.categories.0.name', 'Sneakers')
                ->where('options.colors.0.name', 'Black')
            );

        $this->get(route('products.show', ['locale' => 'ru', 'product' => $product]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('product.name', 'Фабричный раннер')
                ->where('product.description', 'Русское описание товара')
                ->where('product.colors.0.name', 'Черный')
            );

        $product->update([
            'name_translations' => ['en' => 'Factory Runner'],
            'description_translations' => ['en' => 'English product description'],
        ]);
        $color->update(['name_translations' => ['en' => 'Black']]);

        $this->get(route('products.show', ['locale' => 'ru', 'product' => $product]))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('product.name', 'ფაბრიკის რანერი')
                ->where('product.description', 'ქართული პროდუქტის აღწერა')
                ->where('product.colors.0.name', 'შავი')
            );
    }
}
