<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(5000, 100000);
        $shipping = $this->faker->numberBetween(1000, 3000);
        $tax = (int) ($subtotal * 0.15); // 15% VAT
        $discount = $this->faker->boolean(30) ? $this->faker->numberBetween(500, 2000) : 0;

        return [
            'user_id' => User::factory(),
            'order_number' => 'WISAL-' . $this->faker->unique()->numberBetween(1000000, 9999999),
            'status' => OrderStatus::Pending,
            'subtotal_cents' => $subtotal,
            'shipping_cents' => $shipping,
            'tax_cents' => $tax,
            'discount_cents' => $discount,
            'total_cents' => ($subtotal + $shipping + $tax) - $discount,
            'coupon_id' => $discount > 0 ? Coupon::factory() : null,
            'address_id' => Address::factory(),
            'shipping_method_id' => ShippingMethod::factory(),
            'notes' => $this->faker->sentence(),
        ];
    }
}
