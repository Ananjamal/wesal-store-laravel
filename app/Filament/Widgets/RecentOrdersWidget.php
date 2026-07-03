<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): string
    {
        return __('widgets.recent_orders_title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label(__('field.order_number'))
                    ->searchable()
                    ->copyable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('field.customer'))
                    ->searchable()
                    ->icon('heroicon-m-user'),

                Tables\Columns\TextColumn::make('total_cents')
                    ->label(__('field.total'))
                    ->formatStateUsing(fn($state) => app(\App\Services\CurrencyService::class)->format($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('field.status'))
                    ->badge()
                    ->color(fn(OrderStatus $state): string => match ($state) {
                        OrderStatus::Pending    => 'warning',
                        OrderStatus::Processing => 'info',
                        OrderStatus::Shipped    => 'primary',
                        OrderStatus::Delivered  => 'success',
                        OrderStatus::Cancelled  => 'danger',
                        OrderStatus::Refunded   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('field.created_at'))
                    ->dateTime('d M Y - H:i')
                    ->sortable()
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label(__('action.view'))
                    ->icon('heroicon-m-eye')
                    ->url(fn(Order $record): string => route('filament.admin.resources.orders.edit', $record)),
            ])
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5);
    }
}
