<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hex_code',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'color_product');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
