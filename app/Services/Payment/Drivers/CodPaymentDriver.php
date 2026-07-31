<?php

namespace App\Services\Payment\Drivers;

use App\Models\Order;
use App\Services\Payment\PaymentDriverInterface;
use Illuminate\Http\Request;

class CodPaymentDriver implements PaymentDriverInterface
{
    public function process(Order $order): array
    {
        return [
            'status' => 'success',
            'driver' => 'cash_on_delivery',
            'message' => __('سيتم الدفع نقداً عند استلام الطلب.'),
            'redirect_url' => route('checkout.success', $order->id),
        ];
    }

    public function verify(Request $request): bool
    {
        return true;
    }
}
