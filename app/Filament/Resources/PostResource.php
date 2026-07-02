<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'المقالات';
    protected static ?string $pluralModelLabel = 'المقالات';
    protected static ?string $modelLabel = 'مقال';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('الكاتب')
                    ->relationship('user', 'name')
                    ->default(fn() => \Illuminate\Support\Facades\Auth::id())
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->label('الرابط الدائم (Slug)')
                    ->required()
                    ->maxLength(255)
                    ->unique(Post::class, 'slug', ignoreRecord: true),
                Forms\Components\Textarea::make('summary')
                    ->label('الملخص للمقال بقائمة المقالات')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->label('محتوى المقال الكامل')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('featured_image')
                    ->label('الصورة البارزة')
                    ->image()
                    ->directory('blog-images'),
                Forms\Components\Select::make('status')
                    ->label('حالة المقال')
                    ->options(\App\Enums\PostStatus::class)
                    ->default(\App\Enums\PostStatus::Draft)
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('تاريخ وتوقيت النشر'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('الصورة البارزة'),
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('الكاتب')
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('الرابط الدائم')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn(\App\Enums\PostStatus $state): string => match ($state) {
                        \App\Enums\PostStatus::Draft      => 'gray',
                        \App\Enums\PostStatus::Published  => 'success',
                        \App\Enums\PostStatus::Archived   => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('تاريخ النشر')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(\App\Enums\PostStatus::class),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
