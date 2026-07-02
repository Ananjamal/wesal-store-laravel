<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreSettingResource\Pages;
use App\Filament\Resources\StoreSettingResource\RelationManagers;
use App\Models\StoreSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreSettingResource extends Resource
{
    protected static ?string $model = StoreSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'إعدادات المتجر';
    protected static ?string $pluralModelLabel = 'إعدادات المتجر';
    protected static ?string $modelLabel = 'إعداد';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('مفتاح الإعداد')
                    ->required()
                    ->disabled(fn(string $operation) => $operation === 'edit')
                    ->maxLength(255)
                    ->unique(StoreSetting::class, 'key', ignoreRecord: true),
                Forms\Components\Select::make('type')
                    ->label('نوع البيانات')
                    ->options([
                        'string' => 'نص (String)',
                        'integer' => 'عدد صحيح (Integer)',
                        'boolean' => 'منطقي (Boolean)',
                        'json' => 'مصفوفة/كود (JSON)',
                    ])
                    ->default('string')
                    ->required(),
                Forms\Components\Textarea::make('value')
                    ->label('قيمة الإعداد')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('مفتاح الإعداد')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('قيمة الإعداد')
                    ->limit(55)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('نوع البيانات')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'boolean' => 'info',
                        'integer' => 'success',
                        'json' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListStoreSettings::route('/'),
            'create' => Pages\CreateStoreSetting::route('/create'),
            'edit' => Pages\EditStoreSetting::route('/{record}/edit'),
        ];
    }
}
