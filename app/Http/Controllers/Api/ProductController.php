<?php

namespace App\Http\Controllers\Api;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Return a cursor-paginated list of published products with optional filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $products = Product::query()
            ->where('status', ProductStatus::Published)
            ->with(['category'])
            ->when($request->filled('category_id'), fn($q) => $q->where('category_id', $request->integer('category_id')))
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', "%{$request->string('search')}%"))
            ->when($request->filled('sort'), function ($q) use ($request) {
                $allowed = ['price_cents', 'created_at', 'stock_quantity'];
                $column  = in_array($request->string('sort'), $allowed) ? $request->string('sort') : 'created_at';
                $q->orderBy($column, $request->string('order', 'asc'));
            }, fn($q) => $q->latest())
            ->cursorPaginate(20);

        return response()->json([
            'success' => true,
            'data'    => ProductResource::collection($products->items()),
            'meta'    => [
                'next_cursor' => $products->nextCursor()?->encode(),
                'prev_cursor' => $products->previousCursor()?->encode(),
                'per_page'    => $products->perPage(),
            ],
        ]);
    }

    /**
     * Return a single product with its category and images.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return response()->json([
            'success' => true,
            'data'    => new ProductResource($product),
        ]);
    }
}
