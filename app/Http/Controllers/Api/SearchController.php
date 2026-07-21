<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->query('q', '');

        if (empty($query)) {
            return response()->json(['data' => []]);
        }

        // Search using Laravel Scout
        $products = Product::search($query)
            ->where('is_published', true)
            ->where('status', 'published') // Depending on Enums mapping in Scout
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'image' => $product->images()->first()?->thumb ?? 'https://picsum.photos/seed/product-' . $product->id . '/100/100',
                    'category' => $product->category?->name,
                ];
            });

        return response()->json(['data' => $products]);
    }
}
