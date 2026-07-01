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
            self::Fixed => 'مبلغ ثابت',
            self::Percentage => 'نسبة مئوية',
            self::FreeShipping => 'شحن مجاني',
        };
    }
}
