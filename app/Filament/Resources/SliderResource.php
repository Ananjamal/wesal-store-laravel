<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('resource.sliders');
    }

    public static function getModelLabel(): string
    {
        return __('resource.slider');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.sliders');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.cms');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('محتوى الشريحة')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('العنوان الرئيسي')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('subtitle')
                            ->label('العنوان الفرعي')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->label('صورة الشريحة')
                            ->image()
                            ->imageEditor()
                            ->directory('sliders')
                            ->required()
                            ->hint('يُنصح بأبعاد 1920×700px')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('الزر والإعدادات')
                    ->schema([
                        Forms\Components\TextInput::make('button_text')
                            ->label('نص الزر')
                            ->maxLength(100)
                            ->placeholder('مثال: تسوق الآن'),

                        Forms\Components\TextInput::make('button_link')
                            ->label('رابط الزر')
                            ->placeholder('/products'),

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
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtitle')
                    ->label('العنوان الفرعي')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('button_text')
                    ->label('الزر')
                    ->toggleable(isToggledHiddenByDefault: true),

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
            'index'  => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit'   => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
