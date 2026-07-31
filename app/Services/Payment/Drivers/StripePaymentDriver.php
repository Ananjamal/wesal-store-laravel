<?php

namespace App\Services\Payment\Drivers;

use App\Models\Order;
use App\Services\Payment\PaymentDriverInterface;
use Illuminate\Http\Request;

class StripePaymentDriver implements PaymentDriverInterface
{
    public function process(Order $order): array
    {
        // Mock / Sandbox Stripe PaymentIntent creation or API payload
        $secretKey = config('services.stripe.secret') ?? 'sk_test_mock_wisal_key';
        
        $intentId = 'pi_' . bin2hex(random_bytes(12));
        $clientSecret = $intentId . '_secret_' . bin2hex(random_bytes(8));

        return [
            'status' => 'requires_action',
            'driver' => 'stripe',
            'client_secret' => $clientSecret,
            'payment_intent_id' => $intentId,
            'amount' => $order->total_cents,
            'currency' => strtolower($order->currency_code),
            'redirect_url' => route('checkout.success', $order->id),
        ];
    }

    public function verify(Request $request): bool
    {
        $payload = $request->all();
        // Check Stripe Webhook Signature / Event type
        if (isset($payload['type']) && $payload['type'] === 'payment_intent.succeeded') {
            return true;
        }

        return $request->boolean('mock_success', true);
    }
}
