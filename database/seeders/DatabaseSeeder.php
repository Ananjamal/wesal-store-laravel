<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Post;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);

        // 2. Create Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@wisal-store.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );

        if (!$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
        }

        // 2.5. Create Default Currencies
        $currencies = [
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => 'ر.س', 'exchange_rate' => 1.000000, 'is_default' => false, 'is_active' => true],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'exchange_rate' => 0.266667, 'is_default' => false, 'is_active' => true],
            ['code' => 'EGP', 'name' => 'Egyptian Pound', 'symbol' => 'ج.م', 'exchange_rate' => 12.800000, 'is_default' => false, 'is_active' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'exchange_rate' => 12.800000, 'is_default' => false, 'is_active' => true],
            ['code' => 'ILS', 'name' => 'Israeli Shekel', 'symbol' => '₪', 'exchange_rate' => 12.800000, 'is_default' => true, 'is_active' => true],

        ];

        foreach ($currencies as $currency) {
            \App\Models\Currency::firstOrCreate(['code' => $currency['code']], $currency);
        }

        // 3. Create Store Settings
        $settings = [
            ['key' => 'store_name', 'value' => 'Wisal Store', 'type' => 'string'],
            ['key' => 'store_email', 'value' => 'info@wisal-store.com', 'type' => 'string'],
            ['key' => 'loyalty_points_enabled', 'value' => 'true', 'type' => 'boolean'],
            ['key' => 'points_per_sar', 'value' => '10', 'type' => 'integer'],
        ];

        foreach ($settings as $setting) {
            StoreSetting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        // 4. Create Shipping Methods
        $shippingMethods = [
            ['name' => 'Standard Shipping', 'carrier' => 'SMSA', 'cost_cents' => 2500, 'estimated_delivery_days' => '3-5 days', 'is_active' => true],
            ['name' => 'Express Shipping', 'carrier' => 'DHL', 'cost_cents' => 5000, 'estimated_delivery_days' => '1-2 days', 'is_active' => true],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::firstOrCreate(['name' => $method['name']], $method);
        }

        // 5. Create Categories & Products
        $notebookCategory = Category::factory()->create([
            'name' => 'دفاتر ومذكرات',
            'slug' => 'notebooks',
        ]);

        $stickerCategory = Category::factory()->create([
            'name' => 'ملصقات',
            'slug' => 'stickers',
        ]);

        $notebooks = Product::factory()->count(5)->create([
            'category_id' => $notebookCategory->id,
        ]);

        $stickers = Product::factory()->count(5)->create([
            'category_id' => $stickerCategory->id,
        ]);

        // 6. Create Coupons
        Coupon::factory()->create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10,
        ]);

        Coupon::factory()->create([
            'code' => 'FIXED50',
            'type' => 'fixed',
            'value' => 5000, // 50 SAR in cents
        ]);

        // 7. Create Test Customer Users & Addresses & Orders
        $customers = User::factory()->count(3)->create();
        foreach ($customers as $customer) {
            $customer->assignRole($customerRole);

            Address::factory()->create([
                'user_id' => $customer->id,
                'is_default' => true,
            ]);

            // Create some orders for customer
            $order = Order::factory()->create([
                'user_id' => $customer->id,
            ]);

            OrderItem::factory()->count(2)->create([
                'order_id' => $order->id,
                'product_id' => fn() => Product::inRandomOrder()->first()->id,
            ]);
        }

        // 8. Create Blog Posts
        Post::factory()->count(3)->create([
            'user_id' => $admin->id,
        ]);

        $this->command->info('Seeding finished successfully.');
    }
}
