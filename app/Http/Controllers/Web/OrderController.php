<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function checkout()
    {
        $user = Auth::user();
        $cart = $user->carts()->latest('last_activity_at')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', __('سلتك فارغة، لا يمكنك إتمام الطلب'));
        }

        return Inertia::render('Checkout', [
            'user' => $user
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_alt_phone' => 'nullable|string|max:20',
            'shipping_city' => 'required|string|max:255',
            'shipping_area' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:1000',
            'shipping_landmark' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|in:cash_on_delivery,credit_card,mada',
            'coupon_code' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cart = $user->carts()->with(['items.product'])->latest('last_activity_at')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', __('سلتك فارغة!'));
        }

        // Calculate Totals securely on the backend
        $subtotalCents = 0;
        foreach ($cart->items as $item) {
            $subtotalCents += $item->product->price_cents * $item->quantity;
        }

        $discountCents = 0;
        $couponId = null;

        if (!empty($validated['coupon_code'])) {
            $coupon = Coupon::where('code', $validated['coupon_code'])->first();
            if ($coupon && $coupon->is_active && !$coupon->isExpired() && !$coupon->hasLimitReached()) {
                if (!$coupon->min_spend_cents || $subtotalCents >= $coupon->min_spend_cents) {
                    $couponId = $coupon->id;
                    if ($coupon->type->value === 'percentage') {
                        $discount = ($subtotalCents * $coupon->value) / 100;
                        if ($coupon->max_discount_cents && $discount > $coupon->max_discount_cents) {
                            $discount = $coupon->max_discount_cents;
                        }
                        $discountCents = (int) $discount;
                    } else {
                        $discountCents = $coupon->value * 100;
                    }
                }
            }
        }

        $shippingCostCents = 2500; // Fixed 25.00
        $totalCents = max(0, $subtotalCents - $discountCents) + $shippingCostCents;

        DB::beginTransaction();
        try {
            // Generate Order Number
            $orderNumber = 'ORD-' . strtoupper(uniqid());

            $order = Order::create([
                'user_id' => $user->id,
                'coupon_id' => $couponId,
                'order_number' => $orderNumber,
                'subtotal_cents' => $subtotalCents,
                'discount_cents' => $discountCents,
                'shipping_cents' => $shippingCostCents,
                'tax_cents' => 0,
                'total_cents' => $totalCents,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_alt_phone' => $validated['shipping_alt_phone'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_area' => $validated['shipping_area'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_landmark' => $validated['shipping_landmark'],
                'notes' => $validated['notes'],
                'currency_code' => app(\App\Services\CurrencyService::class)->code(),
                'exchange_rate' => app(\App\Services\CurrencyService::class)->rate(),
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'color_id' => $item->color_id,
                    'size_id' => $item->size_id,
                    'quantity' => $item->quantity,
                    'price_cents' => $item->product->price_cents,
                ]);

                // Reduce stock
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            if ($couponId) {
                Coupon::find($couponId)->increment('times_used');
            }

            // Clear Cart
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('حدث خطأ أثناء إتمام الطلب، يرجى المحاولة لاحقاً.'));
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('CheckoutSuccess', [
            'order' => $order->load(['items.product.images', 'items.color', 'items.size', 'coupon'])
        ]);
    }
}
