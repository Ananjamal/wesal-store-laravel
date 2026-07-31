<?php

namespace App\Services\Payment\Drivers;

use App\Models\Order;
use App\Services\Payment\PaymentDriverInterface;
use Illuminate\Http\Request;

class PayPalPaymentDriver implements PaymentDriverInterface
{
    public function process(Order $order): array
    {
        $paypalOrderId = 'PAYPAL-' . strtoupper(uniqid());

        return [
            'status' => 'pending',
            'driver' => 'paypal',
            'paypal_order_id' => $paypalOrderId,
            'amount' => number_format($order->total_cents / 100, 2, '.', ''),
            'currency' => strtoupper($order->currency_code),
            'approval_url' => route('checkout.success', ['order' => $order->id, 'paypal_token' => $paypalOrderId]),
        ];
    }

    public function verify(Request $request): bool
    {
        return $request->filled('paypal_token') || $request->boolean('mock_success', true);
    }
}
