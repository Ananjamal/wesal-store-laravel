<?php

namespace App\Http\Controllers\Web;

use App\Enums\HomepageSectionType;
use App\Enums\ProductStatus;
use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Banner;
use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\Post;
use App\Models\Product;
use App\Models\Slider;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Render the dynamic homepage with CMS configuration.
     */
    public function index(): Response
    {
        $sliders = Slider::active()->get()->map(fn($s) => [
            'id' => $s->id,
            'title' => $s->title,
            'subtitle' => $s->subtitle,
            'description' => $s->description,
            'image' => $s->image ? (str_starts_with($s->image, 'http') ? $s->image : asset('storage/' . $s->image)) : 'https://images.unsplash.com/photo-1513151233558-d860c5398176?auto=format&fit=crop&w=1200&q=80',
            'button_text' => $s->button_text,
            'button_link' => $s->button_link,
        ]);

        $banners = Banner::active()->get()->map(fn($b) => [
            'id' => $b->id,
            'title' => $b->title,
            'description' => $b->description,
            'link' => $b->link,
            'desktop_image' => $b->desktop_image ? asset('storage/' . $b->desktop_image) : 'https://picsum.photos/seed/banner-' . $b->id . '/1200/400',
            'mobile_image' => $b->mobile_image ? asset('storage/' . $b->mobile_image) : ($b->desktop_image ? asset('storage/' . $b->desktop_image) : 'https://picsum.photos/seed/banner-mob-' . $b->id . '/600/400'),
        ]);

        $sections = HomepageSection::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($section) {
                $data = [];
                $limit = $section->items_count ?? 8;

                switch ($section->type->value) {
                    case 'featured_products':
                        // Fetch latest published products as featured
                        $data = ProductResource::collection(
                            Product::where('status', ProductStatus::Published)
                                ->with(['category'])
                                ->latest()
                                ->take($limit)
                                ->get()
                        )->resolve();
                        break;

                    case 'latest_products':
                        $data = ProductResource::collection(
                            Product::where('status', ProductStatus::Published)
                                ->with(['category'])
                                ->latest()
                                ->take($limit)
                                ->get()
                        )->resolve();
                        break;

                    case 'categories':
                        $data = CategoryResource::collection(
                            Category::where('is_active', true)
                                ->whereNull('parent_id')
                                ->withCount('products')
                                ->take($limit)
                                ->get()
                        )->resolve();
                        break;

                    case 'articles':
                        $posts = Post::where('status', PostStatus::Published)
                            ->with(['articleCategory'])
                            ->latest()
                            ->take($limit)
                            ->get();

                        $data = $posts->map(fn($post) => [
                            'id' => $post->id,
                            'title' => $post->title,
                            'slug' => $post->slug,
                            'summary' => $post->summary,
                            'published_at' => $post->published_at?->format('Y-m-d'),
                            'category_name' => $post->articleCategory?->name,
                            'image' => $post->getFirstMediaUrl('featured-image') ?: 'https://picsum.photos/seed/post-' . $post->id . '/800/600',
                        ]);
                        break;
                }

                return [
                    'id' => $section->id,
                    'type' => $section->type->value,
                    'title' => $section->title,
                    'subtitle' => $section->subtitle,
                    'description' => $section->description,
                    'items_count' => $section->items_count,
                    'settings' => $section->settings,
                    'data' => $data,
                ];
            });

        return Inertia::render('Welcome', [
            'sliders' => $sliders,
            'banners' => $banners,
            'homepageSections' => $sections,
        ]);
    }
}
