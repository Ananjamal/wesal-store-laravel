<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'gateway' => $this->faker->randomElement(['Stripe', 'PayPal']),
            'transaction_id' => 'tx_' . $this->faker->sha256(),
            'amount_cents' => $this->faker->numberBetween(1000, 10000),
            'status' => PaymentStatus::Completed,
            'payload' => ['ip' => $this->faker->ipv4()],
        ];
    }
}
