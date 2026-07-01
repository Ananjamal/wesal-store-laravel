<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'initial_amount_cents',
        'remaining_amount_cents',
        'expires_at',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'initial_amount_cents' => 'integer',
        'remaining_amount_cents' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(GiftCardUsage::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getInitialAmountAttribute(): float
    {
        return $this->initial_amount_cents / 100;
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->remaining_amount_cents / 100;
    }
}
