<?php

namespace Database\Seeders;

use App\Models\coupons;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class couponsTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        coupons::create([
            'code' => '123456',
            'value' => 100,
            'type' => 'fixed',
            'percentage_off' => null,
        ]);

        coupons::create([
            'code' => '654321',
            'value' => null,
            'type' => 'percentage',
            'percentage_off' => 50,
        ]);

        coupons::create([
            'code' => '987654',
            'value' => 200,
            'type' => 'fixed',
            'percentage_off' => null,
        ]);
    }
}
