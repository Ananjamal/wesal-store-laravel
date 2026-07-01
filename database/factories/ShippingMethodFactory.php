<?php

namespace Database\Factories;

use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodFactory extends Factory
{
    protected $model = ShippingMethod::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'carrier' => $this->faker->company(),
            'cost_cents' => $this->faker->numberBetween(1000, 5000), // $10 to $50
            'estimated_delivery_days' => '2-5 business days',
            'is_active' => true,
        ];
    }
}
