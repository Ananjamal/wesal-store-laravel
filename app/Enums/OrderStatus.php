<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Refunded = 'refunded';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'قيد الانتظار',
            self::Processing => 'قيد المعالجة (التجهيز)',
            self::Shipped => 'تم الشحن',
            self::Delivered => 'تم التوصيل',
            self::Cancelled => 'ملغي',
            self::Refunded => 'مسترجع',
        };
    }
}
