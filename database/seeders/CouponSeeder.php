<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::firstOrCreate(
            ['code' => 'WELCOME10'],
            ['type' => 'percentage', 'value' => 10]
        );

        Coupon::firstOrCreate(
            ['code' => 'FIXED50'],
            ['type' => 'fixed', 'value' => 5000]
        );
    }
}
