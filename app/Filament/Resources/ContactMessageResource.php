<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('resource.contact_messages');
    }

    public static function getModelLabel(): string
    {
        return __('resource.contact_message');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource.contact_messages');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.operations');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = ContactMessage::where('is_read', false)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('field.sender_info'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('field.name'))
                            ->disabled()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('field.email'))
                            ->email()
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->label(__('field.phone'))
                            ->disabled()
                            ->nullable(),
                    ])->columns(3),

                Forms\Components\Section::make(__('field.message_details'))
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label(__('field.subject'))
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('message')
                            ->label(__('field.message'))
                            ->disabled()
                            ->rows(6)
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_read')
                            ->label(__('field.is_read'))
                            ->onColor('success'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_read')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('field.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('field.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label(__('field.subject'))
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('field.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label(__('field.is_read'))
                    ->trueLabel(__('field.read'))
                    ->falseLabel(__('field.unread')),
            ])
            ->actions([
                Tables\Actions\Action::make('markRead')
                    ->label(__('field.mark_read'))
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->hidden(fn(ContactMessage $record) => $record->is_read)
                    ->action(fn(ContactMessage $record) => $record->update(['is_read' => true])),
                Tables\Actions\ViewAction::make(),
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
            'index'  => Pages\ListContactMessages::route('/'),
        ];
    }
}
