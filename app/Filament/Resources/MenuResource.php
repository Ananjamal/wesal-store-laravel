<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('resource.menus');
    }

    public static function getModelLabel(): string
    {
        return __('resource.menu');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.menus');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.cms');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('بيانات القائمة')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم القائمة')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\Select::make('location')
                            ->label('موقع القائمة')
                            ->required()
                            ->unique(Menu::class, 'location', ignoreRecord: true)
                            ->options([
                                'header'  => 'القائمة الرئيسية (Header)',
                                'footer'  => 'قائمة الفوتر (Footer)',
                                'sidebar' => 'الشريط الجانبي (Sidebar)',
                                'mobile'  => 'قائمة الجوال (Mobile)',
                                'top_bar' => 'الشريط العلوي (Top Bar)',
                            ]),

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
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم القائمة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('location')
                    ->label('الموقع')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'header'  => 'رئيسية',
                        'footer'  => 'فوتر',
                        'sidebar' => 'جانبي',
                        'mobile'  => 'جوال',
                        'top_bar' => 'شريط علوي',
                        default   => $state,
                    })
                    ->colors([
                        'primary' => 'header',
                        'info'    => 'footer',
                        'success' => 'sidebar',
                        'warning' => 'mobile',
                        'gray'    => 'top_bar',
                    ]),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\TextColumn::make('allItems_count')
                    ->label('عناصر القائمة')
                    ->counts('allItems')
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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MenuItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit'   => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
