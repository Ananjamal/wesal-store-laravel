<?php

namespace Database\Factories;

use App\Models\GiftCard;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiftCardFactory extends Factory
{
    protected $model = GiftCard::class;

    public function definition(): array
    {
        $amount = $this->faker->numberBetween(1000, 10000); // $10 to $100

        return [
            'code' => strtoupper($this->faker->unique()->bothify('GIF-????-####')),
            'initial_amount_cents' => $amount,
            'remaining_amount_cents' => $amount,
            'expires_at' => now()->addDays(90),
            'is_active' => true,
            'created_by' => User::factory(),
        ];
    }
}
