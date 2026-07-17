<?php

namespace App\Http\Controllers\Web;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\ArticleCategory;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display a paginated listing of blog posts.
     */
    public function index(Request $request): Response
    {
        $categories = ArticleCategory::where('is_active', true)->get();

        $query = Post::where('status', PostStatus::Published)
            ->with(['articleCategory', 'user'])
            ->latest();

        if ($request->filled('category_id')) {
            $query->where('article_category_id', $request->integer('category_id'));
        }

        if ($request->filled('search')) {
            $query->where(fn($q) => $q->where('title', 'like', '%' . $request->string('search') . '%')
                                      ->orWhere('content', 'like', '%' . $request->string('search') . '%'));
        }

        $posts = $query->paginate(9)->withQueryString();

        return Inertia::render('Blog/Index', [
            'posts' => [
                'data' => $posts->items(),
                'meta' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                    'links' => $posts->linkCollection()->toArray(),
                ],
            ],
            'categories' => $categories->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
            ]),
            'filters' => $request->only(['category_id', 'search']),
        ]);
    }

    /**
     * Display a single blog post details.
     */
    public function show(string $slug): Response
    {
        $post = Post::where('slug', $slug)
            ->where('status', PostStatus::Published)
            ->with(['articleCategory', 'user', 'products' => fn($q) => $q->with('category')])
            ->firstOrFail();

        // Similar posts in the same article category
        $similar = Post::where('article_category_id', $post->article_category_id)
            ->where('id', '!=', $post->id)
            ->where('status', PostStatus::Published)
            ->latest()
            ->take(3)
            ->get();

        // Convert models to appropriate presentation format
        $postData = [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'summary' => $post->summary,
            'content' => $post->content,
            'published_at' => $post->published_at?->format('Y-m-d'),
            'category_name' => $post->articleCategory?->name,
            'author_name' => $post->user?->name ?? 'وِصال',
            'image' => $post->getFirstMediaUrl('featured-image') ?: 'https://picsum.photos/seed/post-detail-' . $post->id . '/1200/600',
            'products' => ProductResource::collection($post->products)->resolve(),
        ];

        $similarData = $similar->map(fn($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'summary' => $item->summary,
            'published_at' => $item->published_at?->format('Y-m-d'),
            'image' => $item->getFirstMediaUrl('featured-image') ?: 'https://picsum.photos/seed/post-' . $item->id . '/800/600',
        ]);

        return Inertia::render('Blog/Show', [
            'post' => $postData,
            'similarPosts' => $similarData,
        ]);
    }
}
