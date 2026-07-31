<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request, string $gateway)
    {
        Log::info("Payment Webhook Received [{$gateway}]:", $request->all());

        try {
            $paymentService = new PaymentService();
            $driver = $paymentService->driver($gateway);

            if ($driver->verify($request)) {
                $orderId = $request->input('order_id') ?? $request->input('metadata.order_id');
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order) {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'pending',
                        ]);
                    }
                }
                return response()->json(['status' => 'success', 'message' => 'Payment verified']);
            }
        } catch (\Exception $e) {
            Log::error("Webhook error: " . $e->getMessage());
        }

        return response()->json(['status' => 'ignored'], 200);
    }
}
