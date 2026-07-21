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
        // Clear old products
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        \Illuminate\Support\Facades\DB::table('color_product')->truncate();
        \Illuminate\Support\Facades\DB::table('product_size')->truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categoriesData = [
            [
                'slug' => 'notebooks',
                'name' => 'دفاتر ومذكرات',
                'image' => 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=400&q=80',
                'products' => [
                    ['name' => 'دفتر ملاحظات جلدي فاخر', 'price' => 12000, 'img' => 'https://images.unsplash.com/photo-1521059424845-a7738f6574c8?auto=format&fit=crop&w=600&q=80'],
                    ['name' => 'مفكرة إنجاز يومية', 'price' => 7500, 'img' => 'https://images.unsplash.com/photo-1574634534894-89d7576c8259?auto=format&fit=crop&w=600&q=80'],
                    ['name' => 'دفتر سلك مسطر أنيق', 'price' => 4500, 'img' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?auto=format&fit=crop&w=600&q=80'],
                ]
            ],
            [
                'slug' => 'stickers',
                'name' => 'ملصقات',
                'image' => 'https://images.unsplash.com/photo-1572945281862-8a9d18e95085?auto=format&fit=crop&w=400&q=80',
                'products' => [
                    ['name' => 'مجموعة ملصقات تحفيزية', 'price' => 2500, 'img' => 'https://images.unsplash.com/photo-1620325852504-20b1e102280d?auto=format&fit=crop&w=600&q=80'],
                    ['name' => 'ملصقات زهور مائية', 'price' => 3000, 'img' => 'https://images.unsplash.com/photo-1582216503940-272cb61266b7?auto=format&fit=crop&w=600&q=80'],
                ]
            ],
            [
                'slug' => 'gift-boxes',
                'name' => 'صناديق هدايا منسقة',
                'image' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=400&q=80',
                'products' => [
                    ['name' => 'صندوق هدايا التخرج', 'price' => 25000, 'img' => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=600&q=80'],
                    ['name' => 'صندوق الهدوء والاسترخاء', 'price' => 32000, 'img' => 'https://images.unsplash.com/photo-1607344645866-009c320b63e0?auto=format&fit=crop&w=600&q=80'],
                ]
            ],
            [
                'slug' => 'qurans',
                'name' => 'المصاحف الملونة',
                'image' => 'https://images.unsplash.com/photo-1609599006353-e629e1d90818?auto=format&fit=crop&w=400&q=80',
                'products' => [
                    ['name' => 'مصحف مخملي وردي', 'price' => 9500, 'img' => 'https://images.unsplash.com/photo-1609599006353-e629e1d90818?auto=format&fit=crop&w=600&q=80'],
                    ['name' => 'مصحف بتغليف كرتوني مقوى', 'price' => 8000, 'img' => 'https://images.unsplash.com/photo-1609599006353-e629e1d90818?auto=format&fit=crop&w=600&q=80'],
                ]
            ],
            [
                'slug' => 'prayer-mats',
                'name' => 'سجادات صلاة فاخرة',
                'image' => 'https://images.unsplash.com/photo-1584727638096-042c45049ebe?auto=format&fit=crop&w=400&q=80',
                'products' => [
                    ['name' => 'سجادة صلاة مبطنة طبية', 'price' => 15000, 'img' => 'https://images.unsplash.com/photo-1584727638096-042c45049ebe?auto=format&fit=crop&w=600&q=80'],
                    ['name' => 'سجادة سفر خفيفة', 'price' => 6000, 'img' => 'https://images.unsplash.com/photo-1614995738622-0d6621375d31?auto=format&fit=crop&w=600&q=80'],
                ]
            ],
            [
                'slug' => 'pens-accessories',
                'name' => 'أقلام وإكسسوارات',
                'image' => 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?auto=format&fit=crop&w=400&q=80',
                'products' => [
                    ['name' => 'طقم أقلام حبر فاخرة', 'price' => 18000, 'img' => 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?auto=format&fit=crop&w=600&q=80'],
                    ['name' => 'منظم مكتب خشبي', 'price' => 11500, 'img' => 'https://images.unsplash.com/photo-1593642532744-d377ab507dc8?auto=format&fit=crop&w=600&q=80'],
                ]
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

            foreach ($catData['products'] as $prodData) {
                $p = Product::create([
                    'category_id' => $category->id,
                    'name' => $prodData['name'],
                    'slug' => \Illuminate\Support\Str::slug($prodData['name'] . '-' . rand(100, 999)),
                    'description' => 'هذا المنتج الفاخر مصمم خصيصاً ليناسب ذوقك الرفيع، وهو مثالي للاستخدام الشخصي أو للإهداء.',
                    'price_cents' => $prodData['price'],
                    'stock_quantity' => rand(10, 50),
                    'low_stock_threshold' => 5,
                    'status' => \App\Enums\ProductStatus::Published,
                ]);

                // Create main image
                $p->images()->create([
                    'image_path' => $prodData['img'],
                    'sort_order' => 0,
                ]);

                // Assign random colors and sizes
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
