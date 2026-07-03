<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'exchange_rate' => 'float',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Currency $currency) {
            $currency->code = strtoupper($currency->code);

            if ($currency->is_default) {
                $currency->exchange_rate = 1.000000;

                // Use a non-booted update query to avoid infinite loops
                static::where('id', '!=', $currency->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
