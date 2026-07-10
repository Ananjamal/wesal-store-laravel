<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        if (class_exists(\Faker\Factory::class)) {
            $admin = User::where('email', 'admin@wisal-store.com')->first();
            $adminId = $admin ? $admin->id : null;

            if ($adminId && \App\Models\AuditLog::count() === 0) {
                \App\Models\AuditLog::factory()->count(10)->create(['user_id' => $adminId]);
            }

            if (\App\Models\ContactMessage::count() === 0) {
                \App\Models\ContactMessage::factory()->count(5)->create();
            }

            if (\App\Models\FlashSale::count() === 0) {
                \App\Models\FlashSale::factory()->count(2)->create();
            }

            if ($adminId && \App\Models\GiftCard::count() === 0) {
                \App\Models\GiftCard::factory()->count(5)->create(['created_by' => $adminId]);
            }

            if (\App\Models\Review::count() === 0) {
                $randomUser = User::inRandomOrder()->first() ?? $admin;
                $randomProduct = Product::inRandomOrder()->first();
                
                if ($randomProduct && $randomUser) {
                    \App\Models\Review::factory()->count(5)->create([
                        'user_id' => $randomUser->id,
                        'product_id' => $randomProduct->id,
                    ]);
                }
            }

            if (\App\Models\Subscriber::count() === 0) {
                \App\Models\Subscriber::factory()->count(10)->create();
            }
        }
    }
}
