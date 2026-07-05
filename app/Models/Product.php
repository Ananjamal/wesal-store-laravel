<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price_cents',
        'compare_at_price_cents',
        'stock_quantity',
        'low_stock_threshold',
        'status',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'compare_at_price_cents' => 'integer',
        'stock_quantity' => 'integer',
        'status' => ProductStatus::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            $product->slug ??= Str::slug($product->name);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function flashSales(): BelongsToMany
    {
        return $this->belongsToMany(FlashSale::class, 'flash_sale_product')
            ->withPivot('sale_price_cents', 'quantity_limit', 'sold_quantity')
            ->withTimestamps();
    }

    public function getPriceAttribute(): float
    {
        return app(\App\Services\CurrencyService::class)->convert($this->price_cents);
    }

    public function getFormattedPriceAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->price_cents);
    }

    public function scopePublished($query)
    {
        return $query->where('status', ProductStatus::Published);
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(400)
              ->format('webp')
              ->queued();

        $this->addMediaConversion('medium')
              ->width(800)
              ->height(800)
              ->format('webp')
              ->queued();
    }
}
