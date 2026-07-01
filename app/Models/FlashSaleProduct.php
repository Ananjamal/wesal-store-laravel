<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FlashSaleProduct extends Pivot
{
    protected $table = 'flash_sale_product';

    protected $fillable = [
        'flash_sale_id',
        'product_id',
        'sale_price_cents',
        'quantity_limit',
        'sold_quantity',
    ];

    protected $casts = [
        'sale_price_cents' => 'integer',
        'quantity_limit' => 'integer',
        'sold_quantity' => 'integer',
    ];

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSalePriceAttribute(): float
    {
        return $this->sale_price_cents / 100;
    }

    public function isSoldOut(): bool
    {
        return $this->sold_quantity >= $this->quantity_limit;
    }
}
