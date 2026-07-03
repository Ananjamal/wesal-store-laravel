<?php

namespace App\Filament\Resources;

use App\Enums\PostStatus;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                Forms\Components\Select::make('user_id')
                    ->label(__('field.author'))
                    ->relationship('user', 'name')
                    ->default(fn() => \Illuminate\Support\Facades\Auth::id())
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('title')
                    ->label(__('field.name'))
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) =>
                    $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->label(__('field.slug'))
                    ->required()
                    ->maxLength(255)
                    ->unique(Post::class, 'slug', ignoreRecord: true),
                Forms\Components\Textarea::make('summary')
                    ->label(__('field.summary'))
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->label(__('field.content'))
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('featured_image')
                    ->label(__('field.featured_image'))
                    ->image()
                    ->directory('blog-images'),
                Forms\Components\Select::make('status')
                    ->label(__('field.status'))
                    ->options(PostStatus::class)
                    ->default(PostStatus::Draft)
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label(__('field.published_at')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label(__('field.featured_image')),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('field.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('field.author'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('field.slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
