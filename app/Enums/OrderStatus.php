<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending    = 'pending';
    case Processing = 'processing';
    case Shipped    = 'shipped';
    case Delivered  = 'delivered';
    case Cancelled  = 'cancelled';
    case Refunded   = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Pending    => 'قيد الانتظار',
            self::Processing => 'قيد التجهيز',
            self::Shipped    => 'تم الشحن',
            self::Delivered  => 'تم التوصيل',
            self::Cancelled  => 'ملغي',
            self::Refunded   => 'مسترد',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending    => 'warning',
            self::Processing => 'info',
            self::Shipped    => 'primary',
            self::Delivered  => 'success',
            self::Cancelled  => 'danger',
            self::Refunded   => 'gray',
        };
    }
}
