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
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ] : null,
            ],
            'locale' => app()->getLocale(),
            'currency' => function () {
                $code = session('currency', 'SAR');
                $curr = \App\Models\Currency::where('code', $code)->first() ?? \App\Models\Currency::where('is_default', true)->first();
                return $curr ? [
                    'code' => $curr->code,
                    'symbol' => $curr->symbol,
                    'exchange_rate' => (float)$curr->exchange_rate,
                ] : [
                    'code' => 'SAR',
                    'symbol' => 'ر.س',
                    'exchange_rate' => 1.0,
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
                $path = lang_path("{$locale}.json");
                if (file_exists($path)) {
                    return json_decode(file_get_contents($path), true);
                }
                return [];
            },
        ]);
    }
}
