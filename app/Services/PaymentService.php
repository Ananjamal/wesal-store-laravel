<?php

namespace App\Services;

use App\Models\Order;
use App\Services\Payment\PaymentDriverInterface;
use App\Services\Payment\Drivers\CodPaymentDriver;
use App\Services\Payment\Drivers\PalPayPaymentDriver;
use App\Services\Payment\Drivers\PayPalPaymentDriver;
use App\Services\Payment\Drivers\StripePaymentDriver;
use InvalidArgumentException;

class PaymentService
{
    /**
     * Get the payment driver implementation.
     */
    public function driver(string $method): PaymentDriverInterface
    {
        return match ($method) {
            'cash_on_delivery', 'cod' => new CodPaymentDriver(),
            'stripe', 'credit_card', 'mada' => new StripePaymentDriver(),
            'paypal' => new PayPalPaymentDriver(),
            'palpay' => new PalPayPaymentDriver(),
            default => throw new InvalidArgumentException("Unsupported payment method: {$method}"),
        };
    }

    /**
     * Process order payment.
     */
    public function process(Order $order): array
    {
        $driver = $this->driver($order->payment_method);
        $result = $driver->process($order);

        if ($order->payment_method === 'cash_on_delivery') {
            $order->update([
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);
        }

        return $result;
    }
}
