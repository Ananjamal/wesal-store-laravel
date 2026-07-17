<?php

namespace App\Filament\Resources;

use App\Enums\PostStatus;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('resource.posts');
    }

    public static function getModelLabel(): string
    {
        return __('resource.post');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.posts');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.content');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('post_tabs')
                    ->tabs([

                        // ── Tab 1: المعلومات الأساسية ─────────────────────────
                        Forms\Components\Tabs\Tab::make('basic')
                            ->label('المعلومات الأساسية')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->label(__('field.name'))
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                            if ($operation === 'create') {
                                                $set('slug', Str::slug($state));
                                            }
                                        }),

                                    Forms\Components\TextInput::make('slug')
                                        ->label(__('field.slug'))
                                        ->required()
                                        ->unique(Post::class, 'slug', ignoreRecord: true)
                                        ->dehydrateStateUsing(fn($state) => Str::slug($state))
                                        ->hint('يُملأ تلقائياً من عنوان المقال'),

                                    Forms\Components\Select::make('article_category_id')
                                        ->label('تصنيف المقال')
                                        ->relationship('articleCategory', 'name')
                                        ->required()
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')
                                                ->label('اسم التصنيف')
                                                ->required(),
                                        ]),

                                    Forms\Components\Select::make('user_id')
                                        ->label(__('field.author'))
                                        ->relationship('user', 'name')
                                        ->default(fn() => Auth::id())
                                        ->required()
                                        ->searchable()
                                        ->preload(),
                                ]),

                                Forms\Components\Textarea::make('summary')
                                    ->label('ملخص المقال')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->columnSpanFull()
                                    ->hint('يظهر في بطاقات المقالات وصفحات القوائم'),

                                Forms\Components\RichEditor::make('content')
                                    ->label('محتوى المقال (Rich Text)')
                                    ->required()
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'heading', 'bulletList', 'orderedList',
                                        'blockquote', 'link', 'table',
                                        'attachFiles', 'redo', 'undo',
                                    ]),
                            ]),

                        // ── Tab 2: الصور ──────────────────────────────────────
                        Forms\Components\Tabs\Tab::make('images')
                            ->label('الصور')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Section::make('الصورة الرئيسية')
                                    ->schema([
                                        Forms\Components\FileUpload::make('featured_image')
                                            ->label('الصورة الرئيسية للمقال')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('blog-images')
                                            ->columnSpanFull(),
                                    ]),

                                Forms\Components\Section::make('معرض الصور (اختياري)')
                                    ->schema([
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                                            ->label('معرض صور إضافية')
                                            ->collection('post-images')
                                            ->multiple()
                                            ->reorderable()
                                            ->image()
                                            ->imageEditor()
                                            ->hint('صور إضافية مرتبطة بالمقال'),
                                    ]),
                            ]),

                        // ── Tab 3: النشر والإعدادات ───────────────────────────
                        Forms\Components\Tabs\Tab::make('publish')
                            ->label('النشر')
                            ->icon('heroicon-o-paper-airplane')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('status')
                                        ->label(__('field.status'))
                                        ->options(PostStatus::class)
                                        ->default(PostStatus::Draft)
                                        ->required(),

                                    Forms\Components\DateTimePicker::make('published_at')
                                        ->label(__('field.published_at'))
                                        ->hint('اتركه فارغاً للنشر الفوري'),

                                    Forms\Components\Toggle::make('is_featured')
                                        ->label('مقال مميز')
                                        ->helperText('يظهر المقال المميز في أبرز الأقسام'),
                                ]),
                            ]),

                        // ── Tab 4: المنتجات المرتبطة ──────────────────────────
                        Forms\Components\Tabs\Tab::make('products')
                            ->label('المنتجات المرتبطة')
                            ->icon('heroicon-o-shopping-bag')
                            ->schema([
                                Forms\Components\Select::make('products')
                                    ->label('ربط بمنتجات')
                                    ->relationship('products', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->hint('اختر المنتجات التي يذكرها هذا المقال أو يوصي بها'),
                            ]),

                        // ── Tab 5: SEO ────────────────────────────────────────
                        Forms\Components\Tabs\Tab::make('seo')
                            ->label('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(60)
                                    ->columnSpanFull()
                                    ->hint('الحد الأقصى 60 حرفاً'),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->columnSpanFull()
                                    ->hint('الحد الأقصى 160 حرفاً'),

                                Forms\Components\TextInput::make('keywords')
                                    ->label('الكلمات المفتاحية')
                                    ->maxLength(255)
                                    ->columnSpanFull()
                                    ->placeholder('مثال: تعليم, مدرسة, أدوات')
                                    ->hint('افصل الكلمات بفاصلة'),
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
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label(__('field.featured_image'))
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Post $r) => $r->summary ? Str::limit($r->summary, 60) : null),

                Tables\Columns\TextColumn::make('articleCategory.name')
                    ->label('التصنيف')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('field.author'))
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('field.status'))
                    ->badge()
                    ->color(fn(PostStatus $state): string => match ($state) {
                        PostStatus::Draft     => 'gray',
                        PostStatus::Published => 'success',
                        PostStatus::Archived  => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('field.published_at'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('field.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('field.status'))
                    ->options(PostStatus::class),

                Tables\Filters\SelectFilter::make('articleCategory')
                    ->label('التصنيف')
                    ->relationship('articleCategory', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('المقالات المميزة'),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
