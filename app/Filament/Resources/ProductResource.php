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
    protected static ?string $navigationLabel = 'المنتجات';
    protected static ?string $pluralModelLabel = 'المنتجات';
    protected static ?string $modelLabel = 'منتج';
    protected static ?string $navigationGroup = 'الكتالوج';
    protected static ?int $navigationSort = 2;

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
                        Forms\Components\TextInput::make('slug')
                            ->label('الرابط الدائم (Slug)')
                            ->required()
                            ->maxLength(255)
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
                            ->label('السعر (بالهللة)')
                            ->numeric()
                            ->required()
                            ->suffix('¢'),
                        Forms\Components\TextInput::make('compare_at_price_cents')
                            ->label('السعر قبل الخصم (بالهللة)')
                            ->numeric()
                            ->suffix('¢'),
                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('الكمية المتوفرة')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('low_stock_threshold')
                            ->label('حد تنبيه كمية المخزون المنخفضة')
                            ->required()
                            ->numeric()
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
                    ->formatStateUsing(fn($state) => number_format($state / 100, 2) . ' SAR')
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
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
