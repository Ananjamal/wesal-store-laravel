<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurrencyResource\Pages;
use App\Models\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('resource.currencies');
    }
    public static function getModelLabel(): string
    {
        return __('resource.currency');
    }
    public static function getPluralModelLabel(): string
    {
        return __('resource.currencies');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('nav.settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label(__('field.currency_code'))
                    ->placeholder('E.g. USD, SAR')
                    ->required()
                    ->maxLength(10)
                    ->unique(Currency::class, 'code', ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->label(__('field.currency_name'))
                    ->placeholder('E.g. US Dollar')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('symbol')
                    ->label(__('field.symbol'))
                    ->placeholder('E.g. $, ر.س')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('exchange_rate')
                    ->label(__('field.exchange_rate'))
                    ->helperText(__('messages.exchange_rate_hint'))
                    ->numeric()
                    ->required()
                    ->default(1.000000),
                Forms\Components\Toggle::make('is_default')
                    ->label(__('field.is_default'))
                    ->helperText(__('messages.is_default_hint'))
                    ->default(false),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('field.is_active'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('field.currency_code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('field.currency_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('symbol')
                    ->label(__('field.symbol')),
                Tables\Columns\TextColumn::make('exchange_rate')
                    ->label(__('field.exchange_rate'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->label(__('field.is_default'))
                    ->boolean()
                    ->sortable(),
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
            'index'  => Pages\ListCurrencies::route('/'),
        ];
    }
}
