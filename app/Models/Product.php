<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, Searchable;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'message',
        'short_description',
        'description',
        'price_cents',
        'compare_at_price_cents',
        'stock_quantity',
        'low_stock_threshold',
        'status',
        'is_published',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price_cents'         => 'integer',
        'compare_at_price_cents' => 'integer',
        'stock_quantity'      => 'integer',
        'status'              => ProductStatus::class,
        'is_published'        => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            $product->slug ??= Str::slug($product->name);
        });
    }

    // ─── Relationships ────────────────────────────────────────────────────────

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

    /** ألوان المنتج (Many-to-Many) */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'color_product');
    }

    /** مقاسات المنتج (Many-to-Many) */
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    /** المقالات المرتبطة بالمنتج (Many-to-Many) */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'article_product', 'product_id', 'post_id');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getPriceAttribute(): float
    {
        return app(\App\Services\CurrencyService::class)->convert($this->price_cents);
    }

    public function getFormattedPriceAttribute(): string
    {
        return app(\App\Services\CurrencyService::class)->format($this->price_cents);
    }

    public function toSearchableArray(): array
    {
        // For Laravel Scout's database driver, we must only return actual table columns
        // that are string-based, because it uses these keys to build a LIKE query.
        // It also calls this method on an empty model instance to get the keys!
        return [
            'name' => $this->name ?? '',
            'short_description' => $this->short_description ?? '',
            'description' => $this->description ? strip_tags($this->description) : '',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', ProductStatus::Published);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_published', true)->where('status', ProductStatus::Published);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    // ─── Media ────────────────────────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product-images')
            ->useDisk('public');

        $this->addMediaCollection('product-cover')
            ->singleFile()
            ->useDisk('public');
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
