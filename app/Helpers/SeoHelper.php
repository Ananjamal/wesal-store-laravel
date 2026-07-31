<?php

namespace App\Helpers;

use App\Models\Product;

class SeoHelper
{
    /**
     * Generate JSON-LD Schema for a Product.
     */
    public static function generateProductSchema(Product $product): array
    {
        return [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => $product->images->pluck('image_path')->map(fn($p) => asset('storage/' . $p))->toArray(),
            'description' => strip_tags($product->description ?? $product->name),
            'sku' => $product->sku ?? "WISAL-{$product->id}",
            'offers' => [
                '@type' => 'Offer',
                'url' => route('products.show', $product->slug),
                'priceCurrency' => 'ILS',
                'price' => number_format($product->price_cents / 100, 2, '.', ''),
                'availability' => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'وِصال - WISAL STORE'
                ]
            ]
        ];
    }

    /**
     * Generate Organization Schema for Wisal Store.
     */
    public static function generateOrganizationSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'وِصال - Wisal Store',
            'url' => config('app.url'),
            'logo' => asset('images/logo.png'),
            'slogan' => 'بين كل هدية وذكرى — وِصال',
            'sameAs' => [
                'https://instagram.com/wisalstore',
                'https://facebook.com/wisalstore'
            ]
        ];
    }
}
