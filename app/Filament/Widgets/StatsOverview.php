<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // Revenue — skip cancelled & refunded
        $revenueCents = Order::whereNotIn('status', [
            OrderStatus::Cancelled->value,
            OrderStatus::Refunded->value,
        ])->sum('total_cents');

        // Month-on-month revenue trend (last 30 days vs previous 30 days)
        $revenueThisMonth = Order::whereNotIn('status', [
            OrderStatus::Cancelled->value,
            OrderStatus::Refunded->value,
        ])->where('created_at', '>=', now()->subDays(30))->sum('total_cents');

        $revenueLastMonth = Order::whereNotIn('status', [
            OrderStatus::Cancelled->value,
            OrderStatus::Refunded->value,
        ])->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->sum('total_cents');

        $revenueTrend = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : 0;

        // Orders
        $totalOrders       = Order::count();
        $ordersThisMonth   = Order::where('created_at', '>=', now()->subDays(30))->count();
        $ordersLastMonth   = Order::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();
        $pendingOrders     = Order::where('status', OrderStatus::Pending->value)->count();

        // Products & Categories
        $totalProducts   = Product::count();
        $publishedProducts = Product::where('status', 'published')->count();
        $totalCategories = Category::count();

        // Users (customers)
        $totalUsers       = User::count();
        $usersThisMonth   = User::where('created_at', '>=', now()->subDays(30))->count();

        // Build trend data arrays for sparkline charts (last 7 days)
        $revenueChart = [];
        $ordersChart  = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $revenueChart[] = Order::whereDate('created_at', $date)
                ->whereNotIn('status', [OrderStatus::Cancelled->value, OrderStatus::Refunded->value])
                ->sum('total_cents') / 100;
            $ordersChart[] = Order::whereDate('created_at', $date)->count();
        }

        return [
            Stat::make('إجمالي الإيرادات', number_format($revenueCents / 100, 2) . ' ر.س')
                ->description($revenueTrend >= 0
                    ? "↑ {$revenueTrend}% مقارنة بالشهر الماضي"
                    : "↓ " . abs($revenueTrend) . "% مقارنة بالشهر الماضي")
                ->descriptionIcon($revenueTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueTrend >= 0 ? 'success' : 'danger')
                ->chart($revenueChart),

            Stat::make('إجمالي الطلبات', number_format($totalOrders))
                ->description("{$pendingOrders} طلب معلّق · {$ordersThisMonth} هذا الشهر")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart($ordersChart),

            Stat::make('المنتجات', "{$publishedProducts} / {$totalProducts}")
                ->description("{$totalCategories} قسم · " . ($totalProducts - $publishedProducts) . " مسودة")
                ->descriptionIcon('heroicon-m-tag')
                ->color('warning'),

            Stat::make('المستخدمون', number_format($totalUsers))
                ->description("{$usersThisMonth} انضموا هذا الشهر")
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
