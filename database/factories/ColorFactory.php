<?php

namespace Database\Factories;

use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Color>
 */
class ColorFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->safeColorName();

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'hex_code' => fake()->hexColor(),
            'is_active' => true,
        ];
    }
}
