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
        $name = $this->faker->unique()->words(3, true);
        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(2, true),
            'price_cents' => $this->faker->numberBetween(1000, 50000), // $10.00 to $500.00
            'compare_at_price_cents' => fn(array $attributes) => $this->faker->boolean(40) ? $attributes['price_cents'] + $this->faker->numberBetween(500, 10000) : null,
            'stock_quantity' => $this->faker->numberBetween(0, 150),
            'low_stock_threshold' => 5,
            'status' => ProductStatus::Published,
        ];
    }
}
