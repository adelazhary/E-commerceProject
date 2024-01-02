<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\order>
 */
class orderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'order_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'subtotal' => $this->faker->numberBetween(1000, 100000),
            'taxes' => $this->faker->numberBetween(1000, 100000),
            'total' => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
