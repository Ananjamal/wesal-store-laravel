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

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $user = Auth::user();
        $hour = now()->hour;

        $greeting = match (true) {
            $hour < 12 => 'صباح الخير',
            $hour < 17 => 'مساء الخير',
            default    => 'مساء النور',
        };

        return [
            'greeting'      => $greeting,
            'userName'      => $user?->name ?? 'المدير',
            'pendingOrders' => Order::where('status', OrderStatus::Pending->value)->count(),
            'todayRevenue'  => number_format(
                Order::whereDate('created_at', today())
                    ->whereNotIn('status', [OrderStatus::Cancelled->value, OrderStatus::Refunded->value])
                    ->sum('total_cents') / 100,
                2
            ),
            'todayOrders'   => Order::whereDate('created_at', today())->count(),
            'date'          => now()->locale('ar')->isoFormat('dddd، D MMMM YYYY'),
        ];
    }
}
