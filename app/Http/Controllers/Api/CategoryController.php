<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Return a cursor-paginated list of active root categories.
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->withCount('products')
            ->cursorPaginate(20);

        return response()->json([
            'success' => true,
            'data'    => CategoryResource::collection($categories->items()),
            'meta'    => [
                'next_cursor' => $categories->nextCursor()?->encode(),
                'prev_cursor' => $categories->previousCursor()?->encode(),
                'per_page'    => $categories->perPage(),
            ],
        ]);
    }
}
