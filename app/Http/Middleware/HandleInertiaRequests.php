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
        ]);
    }
}
