<?php

namespace Database\Factories;

use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Size>
 */
class SizeFactory extends Factory
{
    public function definition(): array
    {
        $value = (string) fake()->unique()->numberBetween(28, 48);

        return [
            'label' => 'EU '.$value,
            'value' => $value,
            'sort_order' => (int) $value,
            'is_active' => true,
        ];
    }
}
