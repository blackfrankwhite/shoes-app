<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inquiry>
 */
class InquiryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'size_id' => Size::factory(),
            'color_id' => Color::factory(),
            'name' => fake()->name(),
            'phone' => '+995 5'.fake()->numerify('## ## ## ##'),
            'email' => fake()->safeEmail(),
            'quantity' => fake()->numberBetween(1, 3),
            'comment' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(Inquiry::STATUSES),
        ];
    }
}
