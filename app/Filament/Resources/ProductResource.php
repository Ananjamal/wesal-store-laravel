<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatus;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Post;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

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
                Forms\Components\Tabs::make('product_tabs')
                    ->tabs([

                        // ── Tab 1: المعلومات الأساسية ─────────────────────────
                        Forms\Components\Tabs\Tab::make('basic')
                            ->label('المعلومات الأساسية')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('اسم المنتج')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                            if ($operation === 'create') {
                                                $set('slug', Str::slug($state));
                                            }
                                        }),

                                    Forms\Components\TextInput::make('slug')
                                        ->label('الرابط الدائم (Slug)')
                                        ->required()
                                        ->unique(Product::class, 'slug', ignoreRecord: true)
                                        ->dehydrateStateUsing(fn($state) => Str::slug($state))
                                        ->hint('يُملأ تلقائياً من اسم المنتج'),

                                    Forms\Components\Select::make('category_id')
                                        ->label('القسم')
                                        ->relationship('category', 'name')
                                        ->required()
                                        ->searchable()
                                        ->preload(),

                                    Forms\Components\TextInput::make('message')
                                        ->label('رسالة المنتج')
                                        ->maxLength(255)
                                        ->placeholder('مثال: هدية مثالية للمناسبات')
                                        ->hint('تظهر بشكل بارز في صفحة المنتج'),
                                ]),

                                Forms\Components\Textarea::make('short_description')
                                    ->label('وصف مختصر')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->columnSpanFull()
                                    ->hint('يظهر في بطاقة المنتج وقوائم المنتجات'),

                                Forms\Components\RichEditor::make('description')
                                    ->label('وصف تفصيلي (Rich Text)')
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'heading', 'bulletList', 'orderedList',
                                        'blockquote', 'link', 'table',
                                        'redo', 'undo',
                                    ]),
                            ]),

                        // ── Tab 2: التسعير والمخزون ───────────────────────────
                        Forms\Components\Tabs\Tab::make('pricing')
                            ->label('التسعير والمخزون')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('price_cents')
                                        ->label('السعر')
                                        ->type('number')
                                        ->step('any')
                                        ->required()
                                        ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])
                                        ->prefix(fn() => \Illuminate\Support\Facades\Cache::remember('currency_default', 3600, fn() => \App\Models\Currency::where('is_default', true)->first())?->symbol ?? 'ر.س'),

                                    Forms\Components\TextInput::make('compare_at_price_cents')
                                        ->label('السعر قبل الخصم')
                                        ->type('number')
                                        ->step('any')
                                        ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])
                                        ->prefix(fn() => \Illuminate\Support\Facades\Cache::remember('currency_default', 3600, fn() => \App\Models\Currency::where('is_default', true)->first())?->symbol ?? 'ر.س')
                                        ->hint('اتركه فارغاً إن لم يكن هناك خصم'),

                                    Forms\Components\TextInput::make('stock_quantity')
                                        ->label('الكمية المتوفرة')
                                        ->required()
                                        ->type('number')
                                        ->step('any')
                                        ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])
                                        ->default(0),

                                    Forms\Components\TextInput::make('low_stock_threshold')
                                        ->label('حد تنبيه المخزون المنخفض')
                                        ->required()
                                        ->type('number')
                                        ->step('any')
                                        ->extraInputAttributes(['dir' => 'ltr', 'style' => 'text-align: right;'])
                                        ->default(5),
                                ]),

                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('status')
                                        ->label('حالة المنتج')
                                        ->options(ProductStatus::class)
                                        ->default(ProductStatus::Draft)
                                        ->required(),

                                    Forms\Components\Toggle::make('is_published')
                                        ->label('حالة النشر (فعال / مخفي)')
                                        ->default(true)
                                        ->helperText('قم بإيقاف التبديل لإخفاء المنتج من المتجر'),
                                ]),
                            ]),

                        // ── Tab 3: الصور ──────────────────────────────────────
                        Forms\Components\Tabs\Tab::make('images')
                            ->label('الصور')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Section::make('الصورة الرئيسية وصورة الغلاف')
                                    ->schema([
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('cover')
                                            ->label('صورة الغلاف (اختياري)')
                                            ->collection('product-cover')
                                            ->image()
                                            ->imageEditor()
                                            ->hint('تستخدم كخلفية أو صورة كبيرة في صفحة المنتج'),
                                    ]),

                                Forms\Components\Section::make('معرض الصور')
                                    ->schema([
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                                            ->label('صور المنتج')
                                            ->collection('product-images')
                                            ->multiple()
                                            ->reorderable()
                                            ->image()
                                            ->imageEditor()
                                            ->hint('يمكن إعادة ترتيب الصور بالسحب والإفلات'),
                                    ]),
                            ]),

                        // ── Tab 4: الألوان والمقاسات ──────────────────────────
                        Forms\Components\Tabs\Tab::make('variants')
                            ->label('الألوان والمقاسات')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                Forms\Components\Select::make('colors')
                                    ->label('ألوان المنتج')
                                    ->relationship('colors', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->hint('اختر الألوان المتاحة لهذا المنتج'),

                                Forms\Components\Select::make('sizes')
                                    ->label('مقاسات المنتج')
                                    ->relationship('sizes', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->hint('اختر المقاسات المتاحة لهذا المنتج (مثال: S, M, L, XL)'),
                            ]),

                        // ── Tab 5: المقالات المرتبطة ──────────────────────────
                        Forms\Components\Tabs\Tab::make('articles')
                            ->label('المقالات المرتبطة')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Select::make('articles')
                                    ->label('ربط بمقالات')
                                    ->relationship('articles', 'title')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->hint('اختر المقالات التي تذكر هذا المنتج أو ترتبط به'),
                            ]),

                        // ── Tab 6: SEO ────────────────────────────────────────
                        Forms\Components\Tabs\Tab::make('seo')
                            ->label('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->columnSpanFull()
                                    ->hint('الحد الأقصى 60 حرفاً — يُملأ تلقائياً من اسم المنتج إن تُرك فارغاً'),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->columnSpanFull()
                                    ->hint('الحد الأقصى 160 حرفاً'),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
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
                    ->sortable()
                    ->description(fn(Product $r) => $r->short_description ? Str::limit($r->short_description, 50) : null),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('القسم')
                    ->sortable()
                    ->searchable()
                    ->badge(),

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

                Tables\Columns\IconColumn::make('is_published')
                    ->label('منشور')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('colors_count')
                    ->label('الألوان')
                    ->counts('colors')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('sizes_count')
                    ->label('المقاسات')
                    ->counts('sizes')
                    ->badge()
                    ->color('warning'),

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

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('حالة النشر')
                    ->trueLabel('منشور')
                    ->falseLabel('مخفي'),
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
            ->defaultSort('created_at', 'desc')
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
