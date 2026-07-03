<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GiftCardResource\Pages;
use App\Models\GiftCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GiftCardResource extends Resource
{
    protected static ?string $model = GiftCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationLabel = 'بطاقات الهدايا';
    protected static ?string $pluralModelLabel = 'بطاقات الهدايا';
    protected static ?string $modelLabel = 'بطاقة هدايا';
    protected static ?string $navigationGroup = 'التسويق';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('كود البطاقة')
                    ->required()
                    ->maxLength(255)
                    ->unique(GiftCard::class, 'code', ignoreRecord: true),
                Forms\Components\TextInput::make('initial_amount_cents')
                    ->label('القيمة المبدئية (بالهللة)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('remaining_amount_cents')
                    ->label('القيمة المتبقية (بالهللة)')
                    ->numeric()
                    ->required(),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('تاريخ الانتهاء'),
                Forms\Components\Toggle::make('is_active')
                    ->label('نشطة')
                    ->default(true),
                Forms\Components\Select::make('created_by')
                    ->label('منشئ الكود')
                    ->relationship('createdBy', 'name')
                    ->default(fn() => auth()->id())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('الكود')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('initial_amount_cents')
                    ->label('القيمة الابتدائية')
                    ->formatStateUsing(fn($state) => number_format($state / 100, 2) . ' SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_amount_cents')
                    ->label('المبلغ المتبقي')
                    ->formatStateUsing(fn($state) => number_format($state / 100, 2) . ' SAR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشطة')
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
            'index' => Pages\ListGiftCards::route('/'),
            'create' => Pages\CreateGiftCard::route('/create'),
            'edit' => Pages\EditGiftCard::route('/{record}/edit'),
        ];
    }
}
