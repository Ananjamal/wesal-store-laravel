<?php

namespace App\Filament\Resources\MenuResource\RelationManagers;

use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'allItems';

    protected static ?string $title = 'عناصر القائمة';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('بيانات العنصر')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('نص العنصر')
                            ->required()
                            ->maxLength(100),

                        Forms\Components\TextInput::make('url')
                            ->label('الرابط')
                            ->required()
                            ->maxLength(500)
                            ->placeholder('مثال: /products أو https://...'),

                        Forms\Components\Select::make('parent_id')
                            ->label('العنصر الأب (للقوائم الفرعية)')
                            ->options(
                                fn(RelationManager $livewire) =>
                                MenuItem::where('menu_id', $livewire->ownerRecord->id)
                                    ->pluck('title', 'id')
                            )
                            ->nullable()
                            ->searchable()
                            ->hint('اتركه فارغاً لجعله عنصراً رئيسياً'),

                        Forms\Components\Select::make('target')
                            ->label('طريقة الفتح')
                            ->options([
                                '_self'  => 'في نفس الصفحة',
                                '_blank' => 'في تبويب جديد',
                            ])
                            ->default('_self'),

                        Forms\Components\TextInput::make('icon')
                            ->label('الأيقونة (اختياري)')
                            ->maxLength(100)
                            ->placeholder('heroicon-o-home'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('الرابط')
                    ->limit(40),

                Tables\Columns\TextColumn::make('parent.title')
                    ->label('العنصر الأب')
                    ->placeholder('— رئيسي')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('target')
                    ->label('الفتح')
                    ->formatStateUsing(fn($state) => $state === '_blank' ? 'تبويب جديد' : 'نفس الصفحة')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('الحالة'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('إضافة عنصر'),
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
}
