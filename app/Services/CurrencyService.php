<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;

class CurrencyService
{
    protected ?Currency $current = null;

    /**
     * Boot the active currency from session, falling back to the default one.
     */
    public function boot(): void
    {
        $this->current = Cache::remember(
            'currency_default',
            60,
            fn() =>
            Currency::where('is_default', true)->first()
                ?? Currency::first()
        );
    }

    /**
     * Get the active Currency model.
     */
    public function current(): ?Currency
    {
        if (!$this->current) {
            $this->boot();
        }
        return $this->current;
    }

    /**
     * Get the active currency code.
     */
    public function code(): string
    {
        return $this->current()?->code ?? 'ILS';
    }

    /**
     * Get the active currency symbol.
     */
    public function symbol(): string
    {
        return $this->current()?->symbol ?? '₪';
    }

    /**
     * Get the active currency exchange rate (relative to the default).
     */
    public function rate(): float
    {
        return (float)($this->current?->exchange_rate ?? 1.0);
    }

    /**
     * Convert an amount stored in the default-currency cents to the active currency.
     * Returns a formatted string with the currency symbol.
     *
     * @param  int   $cents   Amount in the default currency cents (e.g. price_cents).
     * @param  int   $decimals
     * @return string  e.g. "25.00 $"
     */
    public function format(int $cents, int $decimals = 2): string
    {
        $amount = ($cents / 100) * $this->rate();
        return number_format($amount, $decimals) . ' ' . $this->symbol();
    }

    /**
     * Convert cents to active currency float value (without formatting).
     */
    public function convert(int $cents): float
    {
        return ($cents / 100) * $this->rate();
    }
}
