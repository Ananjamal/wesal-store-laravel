<?php

namespace App\Models;

use App\Enums\CouponType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend_cents',
        'max_discount_cents',
        'starts_at',
        'expires_at',
        'usage_limit',
        'usages_count',
        'is_active',
    ];

    protected $casts = [
        'type' => CouponType::class,
        'value' => 'integer',
        'min_spend_cents' => 'integer',
        'max_discount_cents' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'usage_limit' => 'integer',
        'usages_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coupon_user')->withPivot('used_at')->withTimestamps();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function hasLimitReached(): bool
    {
        return $this->usage_limit && $this->usages_count >= $this->usage_limit;
    }

    /**
     * Calculate discount amount in cents securely.
     */
    public function calculateDiscountCents(int $subtotalCents): int
    {
        $typeVal = is_object($this->type) ? $this->type->value : $this->type;

        if ($typeVal === 'percentage') {
            $discount = ($subtotalCents * $this->value) / 100;
            if ($this->max_discount_cents && $discount > $this->max_discount_cents) {
                $discount = $this->max_discount_cents;
            }
            return (int) round($discount);
        }

        // Fixed amount:
        // Value stored in DB as cents (e.g. 5000 = 50.00 ₪) or standard unit (e.g. 50)
        $fixedCents = $this->value >= 100 ? $this->value : ($this->value * 100);
        return (int) min($subtotalCents, $fixedCents);
    }

    /**
     * Get human-readable description of how discount was calculated.
     */
    public function getCalculationDescription(float $subtotal, float $discountAmount, string $currencySymbol = '₪'): string
    {
        $typeVal = is_object($this->type) ? $this->type->value : $this->type;

        if ($typeVal === 'percentage') {
            return "خصم {$this->value}% من المجموع (" . number_format($subtotal, 2) . " {$currencySymbol}) = " . number_format($discountAmount, 2) . " {$currencySymbol}";
        }

        return "خصم ثابت بمقدار " . number_format($discountAmount, 2) . " {$currencySymbol}";
    }
}
