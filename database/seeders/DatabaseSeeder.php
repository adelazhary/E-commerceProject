<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\category::factory(10)->create();
        // \App\Models\Country::factory(10)->create();
        // \App\Models\discount::factory(10)->create();
        \App\Models\product::factory(10)->create();
        // \App\Models\Order::factory(10)->create();
        $this->call([
            // CountriesSeeder::class,
            // InventorySeeder::class,
        ]);

    //     $faker = Faker::create();

    //     foreach (range(1, 10) as $index) {
    //         DB::table('products')->insert([
    //             'name' => $faker->unique()->word,
    //             'description' => $faker->sentence,
    //             'price' => $faker->randomNumber(2),
    //             'is_available' => $faker->boolean,
    //             'is_in_stock' => $faker->boolean,
    //             'amount_in_stock' => $faker->randomNumber(2),
    //             'modified_at' => $faker->date,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //             'inventory_id' => $faker->numberBetween(1, 10), // Adjust the range based on your inventories
    //             'discount_id' => $faker->numberBetween(1, 10), // Adjust the range based on your discounts
    //             'is_active' => $faker->boolean,
    //         ]);
    //     }
    }
}
