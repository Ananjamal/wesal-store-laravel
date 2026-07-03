<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CouponType: string implements HasLabel
{
    case Fixed = 'fixed';
    case Percentage = 'percentage';
    case FreeShipping = 'free_shipping';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Fixed => __('coupon.fixed'),
            self::Percentage => __('coupon.percentage'),
            self::FreeShipping => __('coupon.free_shipping'),
        };
    }
}
