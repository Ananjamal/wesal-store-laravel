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

class CategoryController extends Controller
{
    /**
     * Render the specific category page with its products.
     */
    public function show(Request $request, string $slug): Response
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with('children')
            ->firstOrFail();

        $colors = Color::where('is_active', true)->get();
        $sizes  = Size::where('is_active', true)->get();

        $query = Product::where('status', ProductStatus::Published)
            ->where('category_id', $category->id)
            ->with(['category']);

        // Filtering
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

        return Inertia::render('Category', [
            'category' => (new CategoryResource($category))->resolve(),
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
            'filters' => $request->only(['color_id', 'size_id', 'search', 'price_min', 'price_max', 'sort']),
        ]);
    }
}
