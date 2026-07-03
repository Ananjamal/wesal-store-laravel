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
    protected static ?string $navigationLabel = 'طرق الشحن';
    protected static ?string $pluralModelLabel = 'طرق الشحن';
    protected static ?string $modelLabel = 'طريقة شحن';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('carrier')
                    ->label('الناقل')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cost_cents')
                    ->label('تكلفة الشحن (بالهللة)')
                    ->numeric()
                    ->required()
                    ->default(0),
                Forms\Components\TextInput::make('estimated_delivery_days')
                    ->label('وقت الشحن المتوقع')
                    ->placeholder('E.g. 3-5 days')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('نشطة')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('carrier')
                    ->label('الشركة الناقلة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost_cents')
                    ->label('التكلفة')
                    ->formatStateUsing(fn($state) => number_format($state / 100, 2) . ' SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_delivery_days')
                    ->label('مدة التوصيل والمتوقع'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشطة')
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
            'index' => Pages\ListShippingMethods::route('/'),
            'create' => Pages\CreateShippingMethod::route('/create'),
            'edit' => Pages\EditShippingMethod::route('/{record}/edit'),
        ];
    }
}
