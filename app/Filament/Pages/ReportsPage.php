<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ReportsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('reports.title');
    }

    public function getTitle(): string
    {
        return __('reports.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('reports.group');
    }

    protected static string $view = 'filament.pages.reports-page';

    protected function getViewData(): array
    {
        // 1. Total Revenue (in SAR) - exclude cancelled and refunded
        $totalRevenueCents = \App\Models\Order::whereNotIn('status', [
            \App\Enums\OrderStatus::Cancelled->value,
            \App\Enums\OrderStatus::Refunded->value
        ])->sum('total_cents');

        $totalRevenue = number_format(app(\App\Services\CurrencyService::class)->convert($totalRevenueCents), 2);

        // 2. Total Orders count
        $totalOrdersCount = \App\Models\Order::count();

        // 3. Orders by Status count
        $statusCounts = [];
        foreach (\App\Enums\OrderStatus::cases() as $status) {
            $statusCounts[$status->getLabel()] = \App\Models\Order::where('status', $status->value)->count();
        }

        // 4. Top 5 Products by Sales Volume
        $topProducts = \App\Models\OrderItem::select('product_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->with('product')
            ->get();

        // 5. Recent 5 Orders
        $recentOrders = \App\Models\Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'totalRevenue' => $totalRevenue,
            'totalOrdersCount' => $totalOrdersCount,
            'statusCounts' => $statusCounts,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'currencySymbol' => app(\App\Services\CurrencyService::class)->symbol(),
        ];
    }
}
