<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'article_category_id',
        'title',
        'slug',
        'summary',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'keywords',
        'is_featured',
        'status',
        'published_at',
    ];

    protected $casts = [
        'status'       => PostStatus::class,
        'published_at' => 'datetime',
        'is_featured'  => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            $post->slug ??= Str::slug($post->title);
        });
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function articleCategory(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    /** المنتجات المرتبطة بالمقال (Many-to-Many) */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'article_product', 'post_id', 'product_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::Published);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ─── Media ────────────────────────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('post-images')
            ->useDisk('public');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(400)
              ->height(300)
              ->format('webp')
              ->queued();

        $this->addMediaConversion('medium')
              ->width(800)
              ->height(600)
              ->format('webp')
              ->queued();
    }
}
