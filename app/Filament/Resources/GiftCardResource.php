<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftCardResource\Pages;
use App\Models\GiftCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GiftCardResource extends Resource
{
    protected static ?string $model = GiftCard::class;
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('resource.gift_cards');
    }
    public static function getModelLabel(): string
    {
        return __('resource.gift_card');
    }
    public static function getPluralModelLabel(): string
    {
        return __('resource.gift_cards');
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
                    ->maxLength(255)
                    ->unique(GiftCard::class, 'code', ignoreRecord: true),
                Forms\Components\TextInput::make('initial_amount_cents')
                    ->label(__('field.initial_amount'))
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('remaining_amount_cents')
                    ->label(__('field.remaining_amount'))
                    ->numeric()
                    ->required(),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label(__('field.expires_at')),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('field.is_active'))
                    ->default(true),
                Forms\Components\Select::make('created_by')
                    ->label(__('field.created_by'))
                    ->relationship('createdBy', 'name')
                    ->default(fn() => \Illuminate\Support\Facades\Auth::id())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('field.code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initial_amount_cents')
                    ->label(__('field.initial_amount'))
                    ->formatStateUsing(fn($state) => app(\App\Services\CurrencyService::class)->format($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_amount_cents')
                    ->label(__('field.remaining_amount'))
                    ->formatStateUsing(fn($state) => app(\App\Services\CurrencyService::class)->format($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label(__('field.expires_at'))
                    ->dateTime()
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
            'index'  => Pages\ListGiftCards::route('/'),
        ];
    }
}
