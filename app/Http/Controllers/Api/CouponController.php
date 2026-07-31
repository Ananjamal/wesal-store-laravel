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

        // Calculate discount using central model logic
        $discountCents = $coupon->calculateDiscountCents($subtotalCents);
        $discountAmount = $discountCents / 100;
        $currencySymbol = app(\App\Services\CurrencyService::class)->symbol();
        $formulaText = $coupon->getCalculationDescription($request->subtotal, $discountAmount, $currencySymbol);

        $typeVal = is_object($coupon->type) ? $coupon->type->value : $coupon->type;
        $formattedValue = $typeVal === 'percentage' ? "{$coupon->value}%" : number_format($coupon->value >= 100 ? $coupon->value / 100 : $coupon->value, 2) . " {$currencySymbol}";

        return response()->json([
            'message' => __('تم تطبيق الكوبون بنجاح!'),
            'coupon' => [
                'code' => $coupon->code,
                'discount_amount' => $discountAmount,
                'discount_cents' => $discountCents,
                'type' => $typeVal,
                'value' => $coupon->value,
                'formatted_value' => $formattedValue,
                'formula_text' => $formulaText,
            ]
        ]);
    }
}
