<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('resource.banners');
    }

    public static function getModelLabel(): string
    {
        return __('resource.banner');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.banners');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.cms');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('محتوى البنر')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('العنوان')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('link')
                            ->label('الرابط')
                            ->placeholder('/products')
                            ->maxLength(500),

                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('الصور')
                    ->schema([
                        Forms\Components\FileUpload::make('desktop_image')
                            ->label('صورة سطح المكتب')
                            ->image()
                            ->imageEditor()
                            ->directory('banners')
                            ->hint('يُنصح بأبعاد 1920×500px'),

                        Forms\Components\FileUpload::make('mobile_image')
                            ->label('صورة الجوال')
                            ->image()
                            ->imageEditor()
                            ->directory('banners')
                            ->hint('يُنصح بأبعاد 768×400px'),
                    ])->columns(2),

                Forms\Components\Section::make('إعدادات الجدولة والعرض')
                    ->schema([
                        Forms\Components\DateTimePicker::make('starts_at')
                            ->label('تاريخ بداية العرض')
                            ->hint('اتركه فارغاً للعرض الفوري'),

                        Forms\Components\DateTimePicker::make('ends_at')
                            ->label('تاريخ انتهاء العرض')
                            ->hint('اتركه فارغاً للعرض الدائم'),

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
                Tables\Columns\ImageColumn::make('desktop_image')
                    ->label('الصورة'),

                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('link')
                    ->label('الرابط')
                    ->url(fn($state) => $state)
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label('بداية العرض')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ends_at')
                    ->label('نهاية العرض')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
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
            'index'  => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit'   => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
