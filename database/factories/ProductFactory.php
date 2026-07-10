<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $arabicProducts = [
            'دفتر ملاحظات جلدي فاخر',
            'قلم حبر سائل أزرق',
            'حقيبة ظهر مقاومة للماء',
            'منظم مكتب خشبي',
            'مفكرة يومية صغيرة',
            'حامل أقلام معدني',
            'ملصقات كرتونية ظريفة',
            'بطاقات تهنئة يدوية الصنع',
            'شريط لاصق مزخرف (واشي تيب)',
            'مجموعة أقلام تلوين خشبية',
            'لوحة كتابة مغناطيسية',
            'حافظة ملفات بلاستيكية ملونة',
        ];

        $name = $this->faker->randomElement($arabicProducts) . ' ' . $this->faker->unique()->numberBetween(1, 1000);
        
        $arabicDescriptions = [
            'هذا المنتج مصنوع من مواد عالية الجودة وصديقة للبيئة، ومناسب للاستخدام اليومي في العمل أو الدراسة.',
            'يتميز بتصميم عصري وأنيق يلبي كافة احتياجاتك اليومية بشكل عملي ومريح.',
            'خيار ممتاز كهدية للأصدقاء أو العائلة، بفضل جودته الاستثنائية وتغليفه الفاخر.',
            'منتج عملي جداً وذو متانة عالية يضمن لك الاستخدام لفترات طويلة دون أي تلف.',
        ];

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->randomElement($arabicDescriptions),
            'price_cents' => $this->faker->numberBetween(1000, 50000), // $10.00 to $500.00
            'compare_at_price_cents' => fn(array $attributes) => $this->faker->boolean(40) ? $attributes['price_cents'] + $this->faker->numberBetween(500, 10000) : null,
            'stock_quantity' => $this->faker->numberBetween(0, 150),
            'low_stock_threshold' => 5,
            'status' => ProductStatus::Published,
        ];
    }
}
