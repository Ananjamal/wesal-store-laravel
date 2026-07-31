<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Http\Request;

interface PaymentDriverInterface
{
    /**
     * Process or initiate payment for the given order.
     * Returns payload for frontend or direct redirect URL.
     */
    public function process(Order $order): array;

    /**
     * Verify payment response / webhook payload.
     */
    public function verify(Request $request): bool;
}
