<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryAndProductSeeder extends Seeder
{
    public function run(): void
    {
        $notebookCategory = Category::firstOrCreate(
            ['slug' => 'notebooks'],
            ['name' => 'دفاتر ومذكرات']
        );

        $stickerCategory = Category::firstOrCreate(
            ['slug' => 'stickers'],
            ['name' => 'ملصقات']
        );

        // Add products only if category has none and Faker is available
        if (class_exists(\Faker\Factory::class)) {
            if ($notebookCategory->products()->count() === 0) {
                Product::factory()->count(5)->create(['category_id' => $notebookCategory->id]);
            }

            if ($stickerCategory->products()->count() === 0) {
                Product::factory()->count(5)->create(['category_id' => $stickerCategory->id]);
            }
        }
    }
}
