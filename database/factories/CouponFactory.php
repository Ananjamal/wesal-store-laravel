<?php

namespace Database\Factories;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement([CouponType::Fixed, CouponType::Percentage]);
        $value = $type === CouponType::Percentage ? $this->faker->numberBetween(5, 50) : $this->faker->numberBetween(500, 5000);

        return [
            'code' => strtoupper($this->faker->unique()->bothify('?????##')),
            'type' => $type,
            'value' => $value,
            'min_spend_cents' => $this->faker->numberBetween(1000, 10000),
            'max_discount_cents' => $type === CouponType::Percentage ? $this->faker->numberBetween(2000, 5000) : null,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
            'usage_limit' => $this->faker->numberBetween(50, 500),
            'usages_count' => 0,
            'is_active' => true,
        ];
    }
}
