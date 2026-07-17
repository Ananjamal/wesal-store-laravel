<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'slug'             => $this->slug,
            'description'      => $this->description,
            'price'            => $this->price_cents / 100,
            'compare_at_price' => $this->compare_at_price_cents ? $this->compare_at_price_cents / 100 : null,
            'message'          => $this->message,
            'short_description'=> $this->short_description,
            'stock_quantity'   => $this->stock_quantity,
            'status'           => $this->status,
            'category'         => $this->relationLoaded('category') && $this->category ? (new CategoryResource($this->category))->resolve() : null,
            'colors'           => $this->whenLoaded('colors', function() {
                return $this->colors->map(fn($color) => [
                    'id' => $color->id,
                    'name' => $color->name,
                    'hex_code' => $color->hex_code,
                ]);
            }),
            'sizes'            => $this->whenLoaded('sizes', function() {
                return $this->sizes->map(fn($size) => [
                    'id' => $size->id,
                    'name' => $size->name,
                    'label' => $size->label,
                ]);
            }),
            'images'           => $this->getMedia('product-images')->map(fn($media) => [
                'original' => $media->getFullUrl(),
                'medium'   => $media->hasGeneratedConversion('medium') ? $media->getUrl('medium') : $media->getFullUrl(),
                'thumb'    => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getFullUrl(),
            ]),
            'articles'         => $this->whenLoaded('articles', function() {
                return $this->articles->map(fn($article) => [
                    'id' => $article->id,
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'summary' => $article->summary,
                    'image' => $article->getFirstMediaUrl('featured-image') ?: 'https://picsum.photos/seed/post-' . $article->id . '/800/600',
                ]);
            }),
        ];
    }
}
