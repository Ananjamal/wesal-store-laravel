<?php

namespace Database\Factories;

use App\Models\FlashSale;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlashSaleFactory extends Factory
{
    protected $model = FlashSale::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true) . ' Sale',
            'discount_percentage' => $this->faker->numberBetween(10, 70),
            'starts_at' => now(),
            'ends_at' => now()->addHours($this->faker->numberBetween(2, 48)),
            'is_active' => true,
        ];
    }
}
