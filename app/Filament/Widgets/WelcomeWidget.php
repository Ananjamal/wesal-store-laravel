<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected static ?int $sort = 1;

    protected static string $view = 'filament.widgets.welcome-widget';

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    protected function getViewData(): array
    {
        $user = Auth::user();
        $hour = now()->hour;

        $greeting = match (true) {
            $hour < 12 => __('widgets.good_morning'),
            $hour < 17 => __('widgets.good_afternoon'),
            default    => __('widgets.good_evening'),
        };

        $isoFormat = app()->getLocale() === 'ar' ? 'dddd، D MMMM YYYY' : 'dddd, D MMMM YYYY';
        $date = now()->locale(app()->getLocale())->isoFormat($isoFormat);

        return [
            'greeting'      => $greeting,
            'userName'      => $user?->name ?? __('widgets.admin'),
            'pendingOrders' => Order::where('status', OrderStatus::Pending->value)->count(),
            'todayRevenue'  => number_format(app(\App\Services\CurrencyService::class)->convert(
                Order::whereDate('created_at', today())
                    ->whereNotIn('status', [OrderStatus::Cancelled->value, OrderStatus::Refunded->value])
                    ->sum('total_cents')
            ), 2),
            'todayOrders'   => Order::whereDate('created_at', today())->count(),
            'date'          => $date,
        ];
    }
}
