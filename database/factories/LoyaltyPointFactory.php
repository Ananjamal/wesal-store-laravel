<?php

namespace Database\Factories;

use App\Enums\LoyaltyTransactionType;
use App\Models\LoyaltyPoint;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoyaltyPointFactory extends Factory
{
    protected $model = LoyaltyPoint::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'points' => $this->faker->numberBetween(10, 500),
            'type' => LoyaltyTransactionType::Earned,
            'description' => 'Purchase reward',
            'order_id' => Order::factory(),
        ];
    }
}
