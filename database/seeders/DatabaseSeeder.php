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
            // couponsTableSeed::class,
            // CountriesSeeder::class,
        ]);

        }
}
