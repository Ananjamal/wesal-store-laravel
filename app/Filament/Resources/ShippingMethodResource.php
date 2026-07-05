<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingMethodResource\Pages;
use App\Models\ShippingMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShippingMethodResource extends Resource
{
    protected static ?string $model = ShippingMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('resource.shipping_methods');
    }
    public static function getModelLabel(): string
    {
        return __('resource.shipping_method');
    }
    public static function getPluralModelLabel(): string
    {
        return __('resource.shipping_methods');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('nav.operations');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('field.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('carrier')
                    ->label(__('field.carrier'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cost_cents')
                    ->label(__('field.shipping_cost'))
                    ->numeric()
                    ->required()
                    ->default(0),
                Forms\Components\TextInput::make('estimated_delivery_days')
                    ->label(__('field.delivery_time'))
                    ->placeholder('E.g. 3-5 days')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('field.is_active'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('field.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('carrier')
                    ->label(__('field.carrier'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost_cents')
                    ->label(__('field.shipping_cost'))
                    ->formatStateUsing(fn($state) => app(\App\Services\CurrencyService::class)->format($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_delivery_days')
                    ->label(__('field.delivery_time')),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('field.is_active'))
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('field.is_active'))
                    ->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index'  => Pages\ListShippingMethods::route('/'),
        ];
    }
}
