<?php

namespace App\Enums;

enum CouponType: string
{
    case Percentage  = 'percentage';
    case Fixed       = 'fixed';
    case FreeShipping = 'free_shipping';
    case BuyXGetY    = 'buy_x_get_y';

    public function label(): string
    {
        return match ($this) {
            self::Percentage   => 'نسبة مئوية',
            self::Fixed        => 'مبلغ ثابت',
            self::FreeShipping => 'شحن مجاني',
            self::BuyXGetY     => 'اشترِ X واحصل على Y',
        };
    }
}
