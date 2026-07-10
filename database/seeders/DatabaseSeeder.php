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
        $adminRole    = Role::firstOrCreate(['name' => 'Admin',    'guard_name' => 'web']);
        $managerRole  = Role::firstOrCreate(['name' => 'Manager',  'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);

        // 2. Create Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@wisal-store.com'],
            [
                'name'      => 'Admin User',
                'password'  => Hash::make('password123'),
                'is_active' => true,
            ]
        );

        // Always ensure admin has the Admin role
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
        }

        // Create Default Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@wisal-store.com'],
            [
                'name'      => 'Manager User',
                'password'  => Hash::make('password123'),
                'is_active' => true,
            ]
        );

        // Always ensure manager has the Manager role
        if (!$manager->hasRole('Manager')) {
            $manager->assignRole($managerRole);
        }

        // 2.5. Create Default Currencies
        $currencies = [
            ['code' => 'SAR', 'name' => 'Saudi Riyal',      'symbol' => 'ر.س', 'exchange_rate' => 1.000000,  'is_default' => false, 'is_active' => true],
            ['code' => 'USD', 'name' => 'US Dollar',         'symbol' => '$',   'exchange_rate' => 0.266667,  'is_default' => false, 'is_active' => true],
            ['code' => 'EGP', 'name' => 'Egyptian Pound',    'symbol' => 'ج.م', 'exchange_rate' => 12.800000, 'is_default' => false, 'is_active' => true],
            ['code' => 'EUR', 'name' => 'Euro',              'symbol' => '€',   'exchange_rate' => 0.250000,  'is_default' => false, 'is_active' => true],
            ['code' => 'ILS', 'name' => 'Israeli Shekel',    'symbol' => '₪',   'exchange_rate' => 0.970000,  'is_default' => true,  'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            \App\Models\Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }

        // 3. Create Store Settings
        $settings = [
            ['key' => 'store_name',              'value' => 'Wisal Store',          'type' => 'string'],
            ['key' => 'store_email',             'value' => 'info@wisal-store.com', 'type' => 'string'],
            ['key' => 'loyalty_points_enabled',  'value' => 'true',                 'type' => 'boolean'],
            ['key' => 'points_per_sar',          'value' => '10',                   'type' => 'integer'],
        ];

        foreach ($settings as $setting) {
            StoreSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 4. Create Shipping Methods
        $shippingMethods = [
            ['name' => 'Standard Shipping', 'carrier' => 'SMSA', 'cost_cents' => 2500, 'estimated_delivery_days' => '3-5 days', 'is_active' => true],
            ['name' => 'Express Shipping',  'carrier' => 'DHL',  'cost_cents' => 5000, 'estimated_delivery_days' => '1-2 days', 'is_active' => true],
        ];

        foreach ($shippingMethods as $method) {
            ShippingMethod::updateOrCreate(['name' => $method['name']], $method);
        }

        // 5. Create Categories & Products (only if they don't exist yet)
        $notebookCategory = Category::firstOrCreate(
            ['slug' => 'notebooks'],
            ['name' => 'دفاتر ومذكرات']
        );

        $stickerCategory = Category::firstOrCreate(
            ['slug' => 'stickers'],
            ['name' => 'ملصقات']
        );

        // Add products only if category has none
        if ($notebookCategory->products()->count() === 0) {
            Product::factory()->count(5)->create(['category_id' => $notebookCategory->id]);
        }

        if ($stickerCategory->products()->count() === 0) {
            Product::factory()->count(5)->create(['category_id' => $stickerCategory->id]);
        }

        // 6. Create Coupons (skip if already exist)
        Coupon::firstOrCreate(
            ['code' => 'WELCOME10'],
            ['type' => 'percentage', 'value' => 10]
        );

        Coupon::firstOrCreate(
            ['code' => 'FIXED50'],
            ['type' => 'fixed', 'value' => 5000]
        );

        // 7. Create Test Customer Users & Addresses & Orders (only if fewer than 3 customers exist)
        $existingCustomerCount = User::role('Customer')->count();
        if ($existingCustomerCount < 3) {
            $needed    = 3 - $existingCustomerCount;
            $customers = User::factory()->count($needed)->create();

            foreach ($customers as $customer) {
                $customer->assignRole($customerRole);

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

        // 8. Create Blog Posts (only if none exist for admin)
        if (Post::where('user_id', $admin->id)->count() === 0) {
            Post::factory()->count(3)->create(['user_id' => $admin->id]);
        }

        // 9. Seed the remaining Sidebar Elements
        if (\App\Models\AuditLog::count() === 0) {
            \App\Models\AuditLog::factory()->count(10)->create(['user_id' => $admin->id]);
        }

        if (\App\Models\ContactMessage::count() === 0) {
            \App\Models\ContactMessage::factory()->count(5)->create();
        }

        if (\App\Models\FlashSale::count() === 0) {
            \App\Models\FlashSale::factory()->count(2)->create();
        }

        if (\App\Models\GiftCard::count() === 0) {
            \App\Models\GiftCard::factory()->count(5)->create(['created_by' => $admin->id]);
        }

        if (\App\Models\Review::count() === 0) {
            $randomUser = User::inRandomOrder()->first() ?? $admin;
            $randomProduct = Product::inRandomOrder()->first();
            
            if ($randomProduct) {
                \App\Models\Review::factory()->count(5)->create([
                    'user_id' => $randomUser->id,
                    'product_id' => $randomProduct->id,
                ]);
            }
        }

        if (\App\Models\Subscriber::count() === 0) {
            \App\Models\Subscriber::factory()->count(10)->create();
        }

        $this->command->info('✅ Seeding finished successfully.');
        $this->command->info('   Admin email:    admin@wisal-store.com');
        $this->command->info('   Admin password: password123');
    }
}
