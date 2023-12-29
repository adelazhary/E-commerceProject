<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomNumber(2),
            'is_available' => $this->faker->boolean,
            'is_in_stock' => $this->faker->boolean,
            'amount_in_stock' => $this->faker->randomNumber(2),
            'modified_at' => $this->faker->date,
            'inventory_id' => $this->faker->numberBetween(1, 10), // Adjust the range based on your inventories
            'discount_id' => $this->faker->numberBetween(1, 10), // Adjust the range based on your discounts
            'is_active' => $this->faker->boolean,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
