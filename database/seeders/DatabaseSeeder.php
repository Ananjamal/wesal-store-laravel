<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Bind Faker to use Arabic locale if available (dev environments)
        if (class_exists(\Faker\Factory::class)) {
            app()->singleton(\Faker\Generator::class, function () {
                return \Faker\Factory::create('ar_SA');
            });
        }

        // Call the individual seeders in correct order of dependency
        $this->call([
            RolesAndUsersSeeder::class,
            CurrencySeeder::class,
            StoreSettingSeeder::class,
            ShippingMethodSeeder::class,
            CategoryAndProductSeeder::class,
            CouponSeeder::class,
            OrderSeeder::class,
            PostSeeder::class,
            DummyDataSeeder::class,
        ]);

        $this->command->info('✅ Seeding finished successfully.');
        $this->command->info('   Admin email:    admin@wisal-store.com');
        $this->command->info('   Admin password: 1234567890');
    }
}
