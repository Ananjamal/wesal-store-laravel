<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColorResource\Pages;
use App\Models\Color;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ColorResource extends Resource
{
    protected static ?string $model = Color::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return __('resource.colors');
    }

    public static function getModelLabel(): string
    {
        return __('resource.color');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.colors');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.product_attributes');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('بيانات اللون')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم اللون')
                            ->required()
                            ->maxLength(50),

                        Forms\Components\ColorPicker::make('hex_code')
                            ->label('كود اللون'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('hex_code')
                    ->label('اللون'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('hex_code')
                    ->label('كود اللون')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('المنتجات')
                    ->counts('products')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('الحالة'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListColors::route('/'),
            'create' => Pages\CreateColor::route('/create'),
            'edit'   => Pages\EditColor::route('/{record}/edit'),
        ];
    }
}
