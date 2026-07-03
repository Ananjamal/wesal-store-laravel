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
            self::Pending => __('status.pending'),
            self::Processing => __('status.processing'),
            self::Shipped => __('status.shipped'),
            self::Delivered => __('status.delivered'),
            self::Cancelled => __('status.cancelled'),
            self::Refunded => __('status.refunded'),
        };
    }
}
