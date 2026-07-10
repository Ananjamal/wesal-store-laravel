<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $arabicReviews = [
            'منتج رائع جداً وجودة التصنيع ممتازة، أنصح به بشدة!',
            'التوصيل سريع والتغليف كان ممتازاً. جودة الدفتر رائعة جداً.',
            'جميل جداً ومطابق للوصف تماماً، شكراً لكم على الخدمة الممتازة.',
            'جودة عالية وتصميم مبتكر، سأقوم بالشراء مجدداً بالتأكيد.',
            'منتج جيد ولكن تمنيت لو كان السعر أقل قليلاً، شكراً لكم.',
        ];

        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->randomElement($arabicReviews),
            'is_approved' => true,
        ];
    }
}
