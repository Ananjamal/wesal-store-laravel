<?php

namespace Database\Factories;

use App\Models\GiftCard;
use App\Models\GiftCardUsage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiftCardUsageFactory extends Factory
{
    protected $model = GiftCardUsage::class;

    public function definition(): array
    {
        return [
            'gift_card_id' => GiftCard::factory(),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'amount_cents' => $this->faker->numberBetween(500, 5000),
        ];
    }
}
