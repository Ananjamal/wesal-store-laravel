<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Apply a coupon to the cart.
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric', // Frontend subtotal in normal currency
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['message' => __('الكوبون غير صحيح')], 404);
        }

        if (!$coupon->is_active) {
            return response()->json(['message' => __('الكوبون غير فعال')], 400);
        }

        if ($coupon->isExpired()) {
            return response()->json(['message' => __('الكوبون منتهي الصلاحية')], 400);
        }

        if ($coupon->hasLimitReached()) {
            return response()->json(['message' => __('عذراً، تم تجاوز الحد الأقصى لاستخدام هذا الكوبون')], 400);
        }

        // Subtotal in DB is in cents (assuming base currency). Let's do rough comparison or exact.
        // Frontend sends subtotal in normal amount (e.g. 150.00). Convert to cents if needed.
        $subtotalCents = (int) ($request->subtotal * 100);

        if ($coupon->min_spend_cents && $subtotalCents < $coupon->min_spend_cents) {
            $minSpend = number_format($coupon->min_spend_cents / 100, 2);
            return response()->json(['message' => __('الحد الأدنى لتطبيق هذا الكوبون هو :amount', ['amount' => $minSpend])], 400);
        }

        // Calculate discount
        $discountAmount = 0;
        if ($coupon->type->value === 'percentage') {
            $discountAmount = ($request->subtotal * $coupon->value) / 100;
            // Cap at max discount if exists
            if ($coupon->max_discount_cents) {
                $maxDiscount = $coupon->max_discount_cents / 100;
                if ($discountAmount > $maxDiscount) {
                    $discountAmount = $maxDiscount;
                }
            }
        } else {
            // fixed amount
            $discountAmount = $coupon->value;
        }

        return response()->json([
            'message' => __('تم تطبيق الكوبون بنجاح!'),
            'coupon' => [
                'code' => $coupon->code,
                'discount_amount' => $discountAmount,
                'type' => $coupon->type->value,
            ]
        ]);
    }
}
