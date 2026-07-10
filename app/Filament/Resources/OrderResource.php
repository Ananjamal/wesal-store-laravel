<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('resource.orders');
    }
    public static function getModelLabel(): string
    {
        return __('resource.order');
    }
    public static function getPluralModelLabel(): string
    {
        return __('resource.orders');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('nav.operations');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('field.order'))
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label(__('field.customer'))
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label(__('field.order_status'))
                            ->options(OrderStatus::class)
                            ->required(),
                        Forms\Components\TextInput::make('order_number')
                            ->label(__('field.order_number'))
                            ->maxLength(255),
                        Forms\Components\Textarea::make('notes')
                            ->label(__('field.notes'))
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make(__('field.price'))
                    ->schema([
                        Forms\Components\TextInput::make('subtotal_cents')->label(__('field.subtotal'))->type('number')->step('any')->required()->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])->prefix(__('field.currency_unit')),
                        Forms\Components\TextInput::make('shipping_cents')->label(__('field.shipping_cost'))->type('number')->step('any')->default(0)->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])->prefix(__('field.currency_unit')),
                        Forms\Components\TextInput::make('tax_cents')->label(__('field.tax'))->type('number')->step('any')->default(0)->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])->prefix(__('field.currency_unit')),
                        Forms\Components\TextInput::make('discount_cents')->label(__('field.discount'))->type('number')->step('any')->default(0)->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])->prefix(__('field.currency_unit')),
                        Forms\Components\TextInput::make('total_cents')->label(__('field.total'))->type('number')->step('any')->required()->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])->prefix(__('field.currency_unit')),
                    ])->columns(2),

                Forms\Components\Section::make(__('field.order_items'))
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label(__('field.product'))
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label(__('field.quantity'))
                                    ->type('number')
                                    ->step('any')
                                    ->required()
                                    ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;']),
                                Forms\Components\TextInput::make('price_cents')
                                    ->label(__('field.price'))
                                    ->type('number')
                                    ->step('any')
                                    ->required()
                                    ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])
                                    ->prefix(__('field.currency_unit')),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addable(false)
                            ->deletable(false)
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ViewEntry::make('order_details')
                    ->view('filament.infolists.order-details')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label(__('field.order_number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('field.customer'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cents')
                    ->label(__('field.total'))
                    ->formatStateUsing(fn($state) => app(\App\Services\CurrencyService::class)->format($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('field.order_status'))
                    ->badge()
                    ->color(fn(OrderStatus $state): string => match ($state) {
                        OrderStatus::Pending    => 'warning',
                        OrderStatus::Processing => 'info',
                        OrderStatus::Shipped    => 'primary',
                        OrderStatus::Delivered  => 'success',
                        OrderStatus::Cancelled  => 'danger',
                        OrderStatus::Refunded   => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('field.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('filter.status'))
                    ->options(OrderStatus::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('changeStatus')
                    ->label('تحديث الحالة')
                    ->icon('heroicon-m-arrow-path')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label(__('field.order_status') ?? 'حالة الطلب')
                            ->options(OrderStatus::class)
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update([
                            'status' => $data['status'],
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('تم تحديث حالة الطلب بنجاح')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
        ];
    }
}
