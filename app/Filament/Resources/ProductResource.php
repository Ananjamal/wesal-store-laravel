<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('resource.products');
    }
    public static function getModelLabel(): string
    {
        return __('resource.product');
    }
    public static function getPluralModelLabel(): string
    {
        return __('resource.products');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('nav.catalog');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم المنتج')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                        Forms\Components\Hidden::make('slug')
                            ->unique(Product::class, 'slug', ignoreRecord: true),
                        Forms\Components\Select::make('category_id')
                            ->label('القسم')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\RichEditor::make('description')
                            ->label('الوصف التفصيلي')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('التسعير والمخزون')
                    ->schema([
                        Forms\Components\TextInput::make('price_cents')
                            ->label('السعر ')
                            ->type('number')
                            ->step('any')
                            ->required()
                            ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])
                            ->prefix(__('field.currency_unit')),
                        Forms\Components\TextInput::make('compare_at_price_cents')
                            ->label('السعر قبل الخصم ')
                            ->type('number')
                            ->step('any')
                            ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])
                            ->prefix(__('field.currency_unit')),
                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('الكمية المتوفرة')
                            ->required()
                            ->type('number')
                            ->step('any')
                            ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])
                            ->default(0),
                        Forms\Components\TextInput::make('low_stock_threshold')
                            ->label('حد تنبيه كمية المخزون المنخفضة')
                            ->required()
                            ->type('number')
                            ->step('any')
                            ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])
                            ->default(5),
                        Forms\Components\Select::make('status')
                            ->label('حالة المنتج')
                            ->options(ProductStatus::class)
                            ->default(ProductStatus::Draft)
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('صور المنتج')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                            ->label('الصور')
                            ->collection('product-images')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('product-images')
                    ->collection('product-images')
                    ->label('الصورة')
                    ->circular()
                    ->limit(1),
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('القسم')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price_cents')
                    ->label('السعر')
                    ->formatStateUsing(fn($state) => app(\App\Services\CurrencyService::class)->format($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('المخزون')
                    ->sortable()
                    ->color(fn(Product $record) => $record->stock_quantity <= $record->low_stock_threshold ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('القسم')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(ProductStatus::class),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
        ];
    }
}
