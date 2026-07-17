<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root view template loaded on first visit.
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'avatar' => $request->user()->avatar ? asset('storage/' . $request->user()->avatar) : null,
                    'is_admin' => $request->user()->hasRole(['Admin', 'Manager']),
                ] : null,
            ],
            'locale' => app()->getLocale(),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],
            'currency' => function () {
                /** @var \App\Services\CurrencyService $service */
                $service = app(\App\Services\CurrencyService::class);
                return [
                    'code' => $service->code(),
                    'symbol' => $service->symbol(),
                    'exchange_rate' => $service->rate(),
                ];
            },
            'currencies' => function () {
                return \App\Models\Currency::where('is_active', true)->get()->map(fn($c) => [
                    'code' => $c->code,
                    'name' => $c->name,
                    'symbol' => $c->symbol,
                ]);
            },
            'translations' => function () {
                $locale = app()->getLocale();
                $translations = [];
                $dir = lang_path($locale);
                if (is_dir($dir)) {
                    foreach (glob("{$dir}/*.php") as $file) {
                        $key = basename($file, '.php');
                        $translations[$key] = require $file;
                    }
                }
                $jsonPath = lang_path("{$locale}.json");
                if (file_exists($jsonPath)) {
                    $jsonTrans = json_decode(file_get_contents($jsonPath), true);
                    if (is_array($jsonTrans)) {
                        $translations = array_merge($translations, $jsonTrans);
                    }
                }
                return $translations;
            },
            'menus' => function () {
                return \Illuminate\Support\Facades\Cache::remember('shared_menus_' . app()->getLocale(), 60, function () {
                    return \App\Models\Menu::where('is_active', true)
                        ->with(['items' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
                        ->get()
                        ->mapWithKeys(fn($menu) => [
                            $menu->location => [
                                'name' => $menu->name,
                                'items' => $menu->items->whereNull('parent_id')->map(fn($item) => [
                                    'id' => $item->id,
                                    'title' => $item->title,
                                    'url' => $item->url,
                                    'target' => $item->target,
                                    'icon' => $item->icon,
                                    'children' => $menu->items->where('parent_id', $item->id)->map(fn($child) => [
                                        'id' => $child->id,
                                        'title' => $child->title,
                                        'url' => $child->url,
                                        'target' => $child->target,
                                        'icon' => $child->icon,
                                    ])->values()->all(),
                                ])->values()->all(),
                            ],
                        ]);
                });
            },
            'categories' => function () {
                return \App\Models\Category::where('is_active', true)
                    ->whereNull('parent_id')
                    ->get()
                    ->map(fn($c) => [
                        'id' => $c->id,
                        'name' => $c->name,
                        'slug' => $c->slug,
                    ]);
            },
        ];
    }
}
