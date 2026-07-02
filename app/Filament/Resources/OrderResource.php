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

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $recordTitleAttribute = 'order_number';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'الطلبات';
    protected static ?string $pluralModelLabel = 'الطلبات';
    protected static ?string $modelLabel = 'طلب';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الطلب')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('العميل')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('حالة الطلب')
                            ->options(OrderStatus::class)
                            ->required(),
                        Forms\Components\TextInput::make('order_number')
                            ->label('رقم الطلب')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('notes')
                            ->label('ملاحظات')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('التسعير')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal_cents')->label('المجموع الفرعي (بالهللة)')->numeric()->required(),
                        Forms\Components\TextInput::make('shipping_cents')->label('تكلفة الشحن (بالهللة)')->numeric()->default(0),
                        Forms\Components\TextInput::make('tax_cents')->label('الضريبة (بالهللة)')->numeric()->default(0),
                        Forms\Components\TextInput::make('discount_cents')->label('الخصم (بالهللة)')->numeric()->default(0),
                        Forms\Components\TextInput::make('total_cents')->label('المجموع الإجمالي (بالهللة)')->numeric()->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cents')
                    ->label('المجموع الكلي')
                    ->formatStateUsing(fn($state) => number_format($state / 100, 2) . ' SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('حالة الطلب')
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
                    ->label('تاريخ الطلب')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(OrderStatus::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
