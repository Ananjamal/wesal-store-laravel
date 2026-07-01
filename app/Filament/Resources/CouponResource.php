<?php

namespace App\Filament\Resources;

use App\Enums\CouponType;
use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'الكوبونات';
    protected static ?string $pluralModelLabel = 'الكوبونات';
    protected static ?string $modelLabel = 'كوبون';
    protected static ?string $navigationGroup = 'التسويق';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('كود الخصم')
                    ->required()
                    ->maxLength(100)
                    ->unique(Coupon::class, 'code', ignoreRecord: true)
                    ->placeholder('SAVE20'),
                Forms\Components\Select::make('type')
                    ->label('نوع الكوبون')
                    ->options(CouponType::class)
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('value')
                    ->label('قيمة الخصم')
                    ->required()
                    ->numeric()
                    ->helperText(fn(Forms\Get $get) => match ($get('type')) {
                        'percentage'   => 'أدخل النسبة المئوية (0-100)',
                        'fixed'        => 'أدخل القيمة بالهللة (مثال: 5000 تعني 50 ريال)',
                        'free_shipping' => 'لا يتطلب قيمة (سيتم تعيينها 0 تلقائياً)',
                        default        => '',
                    }),
                Forms\Components\TextInput::make('min_order_cents')
                    ->label('الحد الأدنى لقيمة الطلب (بالهللة)')
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('max_uses')
                    ->label('أقصى عدد مرات استخدام ومشاركة')
                    ->numeric()
                    ->placeholder('اتركه فارغاً لعدد غير محدود'),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('تاريخ وتوقيت الانتهاء'),
                Forms\Components\Toggle::make('is_active')
                    ->label('مرئي ونشط للعملاء')
                    ->default(true)
                    ->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('كود الخصم')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->color(fn(CouponType $state): string => match ($state) {
                        CouponType::Fixed        => 'info',
                        CouponType::Percentage   => 'success',
                        CouponType::FreeShipping => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('القيمة للخصم')
                    ->formatStateUsing(fn($state, Coupon $record) => match ($record->type) {
                        CouponType::Fixed        => number_format($state / 100, 2) . ' SAR',
                        CouponType::Percentage   => $state . '%',
                        CouponType::FreeShipping => 'شحن مجاني',
                    }),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('عدد مرات الاستخدام')
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_uses')
                    ->label('الحد الأقصى للاستخدام')
                    ->placeholder('∞'),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('تاريخ الانتهاء')
                    ->dateTime()
                    ->sortable()
                    ->color(fn($state) => $state && $state < now() ? 'danger' : null),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('النوع')
                    ->options(CouponType::class),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('الحالة نشطة')
                    ->boolean(),
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
            'index'  => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit'   => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
