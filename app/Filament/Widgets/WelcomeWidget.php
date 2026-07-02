<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WelcomeWidget extends Component
{
    protected static ?int $sort = 1;

    protected static string $view = 'filament.widgets.welcome-widget';

    /**
     * Make the widget span the full width of the dashboard.
     */
    protected int | string | array $columnSpan = 'full';

    public function getData(): array
    {
        $user = Auth::user();
        $hour = now()->hour;

        $greeting = match (true) {
            $hour < 12 => 'صباح الخير',
            $hour < 17 => 'مساء الخير',
            default    => 'مساء النور',
        };

        return [
            'greeting'       => $greeting,
            'userName'       => $user?->name ?? 'المدير',
            'pendingOrders'  => Order::where('status', OrderStatus::Pending->value)->count(),
            'todayRevenue'   => number_format(
                Order::whereDate('created_at', today())
                    ->whereNotIn('status', [OrderStatus::Cancelled->value, OrderStatus::Refunded->value])
                    ->sum('total_cents') / 100,
                2
            ),
            'todayOrders'    => Order::whereDate('created_at', today())->count(),
            'date'           => now()->locale('ar')->isoFormat('dddd، D MMMM YYYY'),
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view(static::$view, $this->getData());
    }
}
