<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'discount_percent' => $this->faker->randomNumber(2),
            'discription' => $this->faker->text,
            'active' => $this->faker->boolean,
            'modified_at' => $this->faker->date,
        ];
    }
}
