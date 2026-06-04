<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'sex' => fake()->randomElement(Product::SEXES),
            'price' => fake()->randomFloat(2, 80, 420),
            'description' => fake()->paragraph(),
            'sku' => 'TSF-'.fake()->unique()->bothify('####??'),
            'stock_quantity' => fake()->numberBetween(0, 50),
            'featured' => false,
            'is_active' => true,
        ];
    }
}
