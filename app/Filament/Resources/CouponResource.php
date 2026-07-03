<?php

namespace App\Filament\Resources;

use App\Enums\CouponType;
use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('resource.coupons');
    }
    public static function getModelLabel(): string
    {
        return __('resource.coupon');
    }
    public static function getPluralModelLabel(): string
    {
        return __('resource.coupons');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('nav.marketing');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label(__('field.code'))
                    ->required()
                    ->maxLength(100)
                    ->unique(Coupon::class, 'code', ignoreRecord: true)
                    ->placeholder('SAVE20'),
                Forms\Components\Select::make('type')
                    ->label(__('field.type'))
                    ->options(CouponType::class)
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('value')
                    ->label(__('field.value'))
                    ->required()
                    ->numeric()
                    ->helperText(fn(Forms\Get $get) => match ($get('type')) {
                        'percentage'    => __('messages.coupon_percentage_hint'),
                        'fixed'         => __('messages.coupon_fixed_hint'),
                        'free_shipping' => __('messages.coupon_free_shipping_hint'),
                        default         => '',
                    }),
                Forms\Components\TextInput::make('min_order_cents')
                    ->label(__('field.min_order'))
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('max_uses')
                    ->label(__('field.max_uses'))
                    ->numeric()
                    ->placeholder(__('messages.unlimited')),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label(__('field.expires_at')),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('field.is_active'))
                    ->default(true)
                    ->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('field.code'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('field.type'))
                    ->badge()
                    ->color(fn(CouponType $state): string => match ($state) {
                        CouponType::Fixed        => 'info',
                        CouponType::Percentage   => 'success',
                        CouponType::FreeShipping => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label(__('field.value'))
                    ->formatStateUsing(fn($state, Coupon $record) => match ($record->type) {
                        CouponType::Fixed        => app(\App\Services\CurrencyService::class)->format($state),
                        CouponType::Percentage   => $state . '%',
                        CouponType::FreeShipping => __('messages.coupon_free_shipping_hint'),
                    }),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label(__('field.times_used'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_uses')
                    ->label(__('field.max_uses'))
                    ->placeholder(__('messages.unlimited')),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label(__('field.expires_at'))
                    ->dateTime()
                    ->sortable()
                    ->color(fn($state) => $state && $state < now() ? 'danger' : null),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('field.is_active'))
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('field.type'))
                    ->options(CouponType::class),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('field.is_active'))
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit'   => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
