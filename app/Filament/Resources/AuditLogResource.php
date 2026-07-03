<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('resource.audit_logs');
    }
    public static function getModelLabel(): string
    {
        return __('resource.audit_log');
    }
    public static function getPluralModelLabel(): string
    {
        return __('resource.audit_logs');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('nav.settings');
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit($record): bool
    {
        return false;
    }
    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('field.user'))
                    ->relationship('user', 'name')
                    ->disabled(),
                Forms\Components\TextInput::make('action')
                    ->label(__('field.action'))
                    ->disabled(),
                Forms\Components\TextInput::make('model_type')
                    ->label(__('field.model_type'))
                    ->disabled(),
                Forms\Components\TextInput::make('model_id')
                    ->label(__('field.model_id'))
                    ->disabled(),
                Forms\Components\KeyValue::make('changes')
                    ->label(__('field.changes'))
                    ->disabled(),
                Forms\Components\TextInput::make('ip_address')
                    ->label(__('field.ip_address'))
                    ->disabled(),
                Forms\Components\TextInput::make('user_agent')
                    ->label(__('field.user_agent'))
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('field.user'))
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->label(__('field.action'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'create' => 'success',
                        'update' => 'warning',
                        'delete' => 'danger',
                        default  => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_type')
                    ->label(__('field.model_type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_id')
                    ->label(__('field.model_id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label(__('field.ip_address'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('field.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label(__('field.action'))
                    ->options([
                        'create' => 'Create / إنشاء',
                        'update' => 'Update / تعديل',
                        'delete' => 'Delete / حذف',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Read-only, no bulk delete
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
        ];
    }
}
