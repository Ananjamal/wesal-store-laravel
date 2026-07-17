<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Database\Seeder;

class CategoryAndProductSeeder extends Seeder
{
    public function run(): void
    {
        $categoriesData = [
            [
                'slug' => 'notebooks',
                'name' => 'دفاتر ومذكرات',
                'image' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'slug' => 'stickers',
                'name' => 'ملصقات',
                'image' => 'https://images.unsplash.com/photo-1572945281862-8a9d18e95085?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'slug' => 'gift-boxes',
                'name' => 'صناديق هدايا منسقة',
                'image' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'slug' => 'qurans',
                'name' => 'المصاحف الملونة',
                'image' => 'https://images.unsplash.com/photo-1609599006353-e629e1d90818?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'slug' => 'prayer-mats',
                'name' => 'سجادات صلاة فاخرة',
                'image' => 'https://images.unsplash.com/photo-1584727638096-042c45049ebe?auto=format&fit=crop&w=400&q=80',
            ],
            [
                'slug' => 'pens-accessories',
                'name' => 'أقلام وإكسسوارات',
                'image' => 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?auto=format&fit=crop&w=400&q=80',
            ],
        ];

        $colors = Color::all();
        $sizes = Size::all();

        foreach ($categoriesData as $catData) {
            $category = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'image' => $catData['image'],
                    'is_active' => true,
                ]
            );

            // Add products if none exist
            if ($category->products()->count() === 0 && class_exists(\Faker\Factory::class)) {
                $products = Product::factory()->count(5)->create(['category_id' => $category->id]);
                foreach ($products as $p) {
                    if ($colors->isNotEmpty()) {
                        $p->colors()->sync($colors->random(min(3, $colors->count()))->pluck('id')->toArray());
                    }
                    if ($sizes->isNotEmpty()) {
                        $p->sizes()->sync($sizes->random(min(3, $sizes->count()))->pluck('id')->toArray());
                    }
                }
            }
        }
    }
}
