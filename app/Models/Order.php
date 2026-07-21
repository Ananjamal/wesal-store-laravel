<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal_cents',
        'shipping_cents',
        'tax_cents',
        'discount_cents',
        'total_cents',
        'coupon_id',
        'address_id',
        'shipping_method_id',
        'notes',
        'shipping_name',
        'shipping_phone',
        'shipping_alt_phone',
        'shipping_city',
        'shipping_area',
        'shipping_address',
        'shipping_landmark',
        'payment_method',
        'payment_status',
        'currency_code',
        'exchange_rate',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'subtotal_cents' => 'integer',
        'shipping_cents' => 'integer',
        'tax_cents' => 'integer',
        'discount_cents' => 'integer',
        'total_cents' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function getSubtotalAttribute(): float
    {
        return app(\App\Services\CurrencyService::class)->convert($this->subtotal_cents);
    }

    public function getShippingAttribute(): float
    {
        return app(\App\Services\CurrencyService::class)->convert($this->shipping_cents);
    }

    public function getTaxAttribute(): float
    {
        return app(\App\Services\CurrencyService::class)->convert($this->tax_cents);
    }

    public function getDiscountAttribute(): float
    {
        return app(\App\Services\CurrencyService::class)->convert($this->discount_cents);
    }

    public function getTotalAttribute(): float
    {
        return app(\App\Services\CurrencyService::class)->convert($this->total_cents);
    }

    public function getFormattedTotalAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->total_cents);
    }
}
