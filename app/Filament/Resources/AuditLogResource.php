<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Filament\Resources\AuditLogResource\RelationManagers;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'سجل التدقيق';
    protected static ?string $pluralModelLabel = 'سجلات التدقيق';
    protected static ?string $modelLabel = 'سجل تدقيق';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 2;

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
                    ->label('المستخدم')
                    ->relationship('user', 'name')
                    ->disabled(),
                Forms\Components\TextInput::make('action')
                    ->label('الإجراء')
                    ->disabled(),
                Forms\Components\TextInput::make('model_type')
                    ->label('نوع الموديل')
                    ->disabled(),
                Forms\Components\TextInput::make('model_id')
                    ->label('معرف السجل')
                    ->disabled(),
                Forms\Components\KeyValue::make('changes')
                    ->label('التغييرات')
                    ->disabled(),
                Forms\Components\TextInput::make('ip_address')
                    ->label('عنوان IP')
                    ->disabled(),
                Forms\Components\TextInput::make('user_agent')
                    ->label('متصفح المستخدم')
                    ->disabled()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->placeholder('مجهول / زائر')
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->label('الإجراء')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'create' => 'success',
                        'update' => 'warning',
                        'delete' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_type')
                    ->label('نوع الموديل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_id')
                    ->label('معرف السجل')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('عنوان IP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإجراء')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label('الإجراء')
                    ->options([
                        'create' => 'إنشاء',
                        'update' => 'تعديل',
                        'delete' => 'حذف',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض التفاصيل'),
            ])
            ->bulkActions([
                // Read-only, no bulk delete
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
            'index' => Pages\ListAuditLogs::route('/'),
        ];
    }
}
