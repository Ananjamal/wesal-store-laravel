<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleCategoryResource\Pages;
use App\Models\ArticleCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleCategoryResource extends Resource
{
    protected static ?string $model = ArticleCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('resource.article_categories');
    }

    public static function getModelLabel(): string
    {
        return __('resource.article_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.article_categories');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.content');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('بيانات التصنيف')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم التصنيف')
                            ->required()
                            ->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label('الرابط الدائم (Slug)')
                            ->required()
                            ->unique(ArticleCategory::class, 'slug', ignoreRecord: true)
                            ->dehydrateStateUsing(fn($state) => Str::slug($state)),

                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('صورة التصنيف')
                            ->image()
                            ->imageEditor()
                            ->directory('article-categories'),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('ترتيب الظهور')
                            ->numeric()
                            ->default(0),

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
                Tables\Columns\ImageColumn::make('image')
                    ->label('الصورة')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\TextColumn::make('posts_count')
                    ->label('المقالات')
                    ->counts('posts')
                    ->badge()
                    ->color('info'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('الحالة'),
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
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListArticleCategories::route('/'),
            'create' => Pages\CreateArticleCategory::route('/create'),
            'edit'   => Pages\EditArticleCategory::route('/{record}/edit'),
        ];
    }
}
