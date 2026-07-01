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
}
