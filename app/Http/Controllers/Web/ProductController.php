<?php

namespace App\Http\Controllers\Web;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Render the shop catalogue with products and filters.
     */
    public function index(Request $request): Response
    {
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->get();
        $colors     = Color::where('is_active', true)->get();
        $sizes      = Size::where('is_active', true)->get();

        $query = Product::where('status', ProductStatus::Published)
            ->with(['category']);

        // Filtering
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('color_id')) {
            $query->whereHas('colors', fn($q) => $q->where('colors.id', $request->integer('color_id')));
        }

        if ($request->filled('size_id')) {
            $query->whereHas('sizes', fn($q) => $q->where('sizes.id', $request->integer('size_id')));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->filled('price_min')) {
            $query->where('price_cents', '>=', $request->integer('price_min') * 100);
        }

        if ($request->filled('price_max')) {
            $query->where('price_cents', '<=', $request->integer('price_max') * 100);
        }

        // Sorting
        $sort = $request->string('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price_cents', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_cents', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return Inertia::render('Shop', [
            'products' => [
                'data' => ProductResource::collection($products->items())->resolve(),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'links' => $products->linkCollection()->toArray(),
                ],
            ],
            'categories' => CategoryResource::collection($categories)->resolve(),
            'colors' => $colors->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'hex_code' => $c->hex_code,
            ]),
            'sizes' => $sizes->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'label' => $s->label,
            ]),
            'filters' => $request->only(['category_id', 'color_id', 'size_id', 'search', 'price_min', 'price_max', 'sort']),
        ]);
    }

    /**
     * Render the single product detail page.
     */
    public function show(string $slug): Response
    {
        $product = Product::where('slug', $slug)
            ->where('status', ProductStatus::Published)
            ->with(['category', 'colors', 'sizes', 'articles'])
            ->firstOrFail();

        // Similar products in the same category
        $similar = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', ProductStatus::Published)
            ->with(['category'])
            ->latest()
            ->take(4)
            ->get();

        return Inertia::render('ProductShow', [
            'product' => (new ProductResource($product))->resolve(),
            'similarProducts' => ProductResource::collection($similar)->resolve(),
        ]);
    }
}
