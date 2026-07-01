<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'carrier',
        'cost_cents',
        'estimated_delivery_days',
        'is_active',
    ];

    protected $casts = [
        'cost_cents' => 'integer',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getCostAttribute(): float
    {
        return $this->cost_cents / 100;
    }
}
