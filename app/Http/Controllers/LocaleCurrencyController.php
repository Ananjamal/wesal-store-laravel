<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class LocaleCurrencyController extends Controller
{
    public function setLocale(string $locale)
    {
        if (in_array($locale, ['ar', 'en'])) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }

    public function setCurrency(string $code)
    {
        $currencyExists = Currency::where('code', strtoupper($code))->where('is_active', true)->exists();
        if ($currencyExists) {
            session(['currency' => strtoupper($code)]);
        }

        return redirect()->back();
    }
}
