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
    protected static ?string $navigationLabel = 'العملات';
    protected static ?string $pluralModelLabel = 'العملات';
    protected static ?string $modelLabel = 'عملة';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('رمز العملة')
                    ->placeholder('E.g. USD, SAR')
                    ->required()
                    ->maxLength(10)
                    ->unique(Currency::class, 'code', ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->label('اسم العملة')
                    ->placeholder('E.g. US Dollar, ريال سعودي')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('symbol')
                    ->label('رمز العرض')
                    ->placeholder('E.g. $, ر.س')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('exchange_rate')
                    ->label('سعر الصرف')
                    ->helperText('سعر صرف العملة مقارنة بالعملة الأساسية (العملة الأساسية يجب أن تكون 1.0)')
                    ->numeric()
                    ->required()
                    ->default(1.000000),
                Forms\Components\Toggle::make('is_default')
                    ->label('العملة الرئيسية')
                    ->helperText('إذا تم التفعيل، ستصبح هذه هي العملة الأساسية للمتجر وسيتم ضبط سعر صرفها تلقائياً ليكون 1.0')
                    ->default(false),
                Forms\Components\Toggle::make('is_active')
                    ->label('نشطة')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('رمز العملة')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('symbol')
                    ->label('الرمز'),
                Tables\Columns\TextColumn::make('exchange_rate')
                    ->label('سعر الصرف')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('الرئيسية')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('النشطة منها فقط')
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
            'index' => Pages\ListCurrencies::route('/'),
            'create' => Pages\CreateCurrency::route('/create'),
            'edit' => Pages\EditCurrency::route('/{record}/edit'),
        ];
    }
}
