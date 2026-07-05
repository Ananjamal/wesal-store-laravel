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
            'stock_quantity'   => $this->stock_quantity,
            'status'           => $this->status,
            'category'         => new CategoryResource($this->whenLoaded('category')),
            'images'           => $this->getMedia('product-images')->map(fn($media) => [
                'original' => $media->getFullUrl(),
                'medium'   => $media->hasGeneratedConversion('medium') ? $media->getUrl('medium') : $media->getFullUrl(),
                'thumb'    => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media->getFullUrl(),
            ]),
        ];
    }
}
