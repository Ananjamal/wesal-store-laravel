<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreSettingResource\Pages;
use App\Models\StoreSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StoreSettingResource extends Resource
{
    protected static ?string $model = StoreSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('resource.store_settings');
    }
    public static function getModelLabel(): string
    {
        return __('resource.store_setting');
    }
    public static function getPluralModelLabel(): string
    {
        return __('resource.store_settings');
    }
    public static function getNavigationGroup(): ?string
    {
        return __('nav.settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label(__('field.key'))
                    ->required()
                    ->disabled(fn(string $operation) => $operation === 'edit')
                    ->maxLength(255)
                    ->unique(StoreSetting::class, 'key', ignoreRecord: true),
                Forms\Components\Select::make('type')
                    ->label(__('field.data_type'))
                    ->options([
                        'string'  => 'نص (String)',
                        'integer' => 'عدد صحيح (Integer)',
                        'boolean' => 'منطقي (Boolean)',
                        'json'    => 'مصفوفة/كود (JSON)',
                    ])
                    ->default('string')
                    ->required(),
                Forms\Components\Textarea::make('value')
                    ->label(__('field.setting_value'))
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label(__('field.key'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label(__('field.setting_value'))
                    ->limit(55)
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('field.data_type'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'boolean' => 'info',
                        'integer' => 'success',
                        'json'    => 'warning',
                        default   => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('field.updated_at'))
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStoreSettings::route('/'),
        ];
    }
}
