<?php

namespace App\Filament\Resources;

use App\Enums\HomepageSectionType;
use App\Filament\Resources\HomepageSectionResource\Pages;
use App\Models\HomepageSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('resource.homepage_sections');
    }

    public static function getModelLabel(): string
    {
        return __('resource.homepage_section');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.homepage_sections');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.cms');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Forms\Components\Section::make('نوع القسم')
                    ->schema([
                         Forms\Components\Select::make('type')
                            ->label('نوع القسم')
                            ->options(HomepageSectionType::class)
                            ->required()
                            ->searchable()
                            ->live()
                            ->helperText('ملاحظة: إذا اخترت "السلايدر الرئيسي" أو "البنرات الإعلانية"، فإن محتواهما (الصور والشرائح والروابط) يتم التحكم به وإضافته من صفحة "السلايدر" وصفحة "البنرات" المخصصة في القائمة الجانبية.'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('إظهار القسم')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('المحتوى النصي')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('العنوان')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('subtitle')
                            ->label('العنوان الفرعي')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->hidden(fn (Forms\Get $get) => in_array($get('type'), ['hero_section', 'offers'])),

                Forms\Components\Section::make('المظهر')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('صورة القسم')
                            ->image()
                            ->imageEditor()
                            ->directory('homepage-sections'),

                        Forms\Components\TextInput::make('items_count')
                            ->label('عدد العناصر المعروضة')
                            ->numeric()
                            ->default(8)
                            ->minValue(1)
                            ->maxValue(50),
                    ])
                    ->columns(2)
                    ->hidden(fn (Forms\Get $get) => in_array($get('type'), ['hero_section', 'offers'])),

                Forms\Components\Section::make('إعدادات متقدمة (اختياري)')
                    ->schema([
                        Forms\Components\KeyValue::make('settings')
                            ->label('إعدادات إضافية')
                            ->columnSpanFull()
                            ->hint('أضف أي إعدادات إضافية بصيغة مفتاح/قيمة'),
                    ])
                    ->collapsed()
                    ->hidden(fn (Forms\Get $get) => in_array($get('type'), ['hero_section', 'offers'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع القسم')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof HomepageSectionType ? $state->getLabel() : (HomepageSectionType::tryFrom($state)?->getLabel() ?? $state)),

                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('عدد العناصر')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('مرئي')
                    ->boolean(),
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
            'index'  => Pages\ListHomepageSections::route('/'),
            'create' => Pages\CreateHomepageSection::route('/create'),
            'edit'   => Pages\EditHomepageSection::route('/{record}/edit'),
        ];
    }
}
