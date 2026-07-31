<?php

namespace App\Services\Payment\Drivers;

use App\Models\Order;
use App\Services\Payment\PaymentDriverInterface;
use Illuminate\Http\Request;

class PalPayPaymentDriver implements PaymentDriverInterface
{
    public function process(Order $order): array
    {
        $palpayRef = 'PALPAY-' . rand(100000, 999999);

        return [
            'status' => 'pending',
            'driver' => 'palpay',
            'transaction_ref' => $palpayRef,
            'merchant_id' => config('services.palpay.merchant_id', 'WISAL_STORE_PAL'),
            'amount' => number_format($order->total_cents / 100, 2, '.', ''),
            'currency' => strtoupper($order->currency_code),
            'checkout_url' => route('checkout.success', ['order' => $order->id, 'palpay_ref' => $palpayRef]),
        ];
    }

    public function verify(Request $request): bool
    {
        return $request->filled('palpay_ref') || $request->boolean('mock_success', true);
    }
}
