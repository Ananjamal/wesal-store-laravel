<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalizationAndCurrencyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle Locale
        $locale = session('locale');
        if (!$locale) {
            $locale = 'ar';
            session(['locale' => $locale]);
        }
        app()->setLocale($locale);

        // Handle Currency
        $currency = session('currency');
        if (!$currency) {
            $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();
            $currency = $defaultCurrency ? $defaultCurrency->code : 'SAR';
            session(['currency' => $currency]);
        }

        return $next($request);
    }
}
