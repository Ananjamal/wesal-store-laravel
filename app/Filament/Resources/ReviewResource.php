<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('resource.reviews');
    }
    public static function getModelLabel(): string
    {
        return __('resource.review');
    }
    public static function getPluralModelLabel(): string
    {
        return __('resource.reviews');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('nav.catalog');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('field.user'))
                    ->relationship('user', 'name')
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->label(__('field.product'))
                    ->relationship('product', 'name')
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('rating')
                    ->label(__('field.rating'))
                    ->numeric()
                    ->disabled()
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label(__('field.comment'))
                    ->disabled()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_approved')
                    ->label(__('field.is_approved'))
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('field.user'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('field.product'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label(__('field.rating'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label(__('field.comment'))
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label(__('field.is_approved'))
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label(__('field.is_approved'))
                    ->boolean(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit'   => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
