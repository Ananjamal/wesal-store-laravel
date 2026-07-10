<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $shippingMethods = [
            ['name' => 'شحن قياسي', 'carrier' => 'سمسا', 'cost_cents' => 2500, 'estimated_delivery_days' => '3-5 أيام', 'is_active' => true],
            ['name' => 'شحن سريع',  'carrier' => 'دي إتش إل',  'cost_cents' => 5000, 'estimated_delivery_days' => '1-2 أيام', 'is_active' => true],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::updateOrCreate(['name' => $method['name']], $method);
        }
    }
}
