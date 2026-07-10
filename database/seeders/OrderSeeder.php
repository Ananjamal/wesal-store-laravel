<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (class_exists(\Faker\Factory::class)) {
            $customerRole = Role::where('name', 'Customer')->first();
            $existingCustomerCount = User::role('Customer')->count();
            
            if ($existingCustomerCount < 3) {
                $needed    = 3 - $existingCustomerCount;
                $customers = User::factory()->count($needed)->create();

                foreach ($customers as $customer) {
                    if ($customerRole) {
                        $customer->assignRole($customerRole);
                    }

                    Address::firstOrCreate(
                        ['user_id' => $customer->id, 'is_default' => true],
                        Address::factory()->make(['user_id' => $customer->id, 'is_default' => true])->toArray()
                    );

                    $order = Order::factory()->create(['user_id' => $customer->id]);

                    OrderItem::factory()->count(2)->create([
                        'order_id'   => $order->id,
                        'product_id' => fn() => Product::inRandomOrder()->first()->id,
                    ]);
                }
            }
        }
    }
}
