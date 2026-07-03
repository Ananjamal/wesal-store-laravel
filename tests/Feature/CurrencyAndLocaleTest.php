<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyAndLocaleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Currency::create([
            'code' => 'SAR',
            'name' => 'Saudi Riyal',
            'symbol' => 'ر.س',
            'exchange_rate' => 1.000000,
            'is_default' => true,
            'is_active' => true,
        ]);

        Currency::create([
            'code' => 'USD',
            'name' => 'US Dollar',
            'symbol' => '$',
            'exchange_rate' => 0.266667,
            'is_default' => false,
            'is_active' => true,
        ]);
    }

    public function test_can_create_currency()
    {
        $this->assertDatabaseHas('currencies', ['code' => 'SAR', 'is_default' => true]);
        $this->assertDatabaseHas('currencies', ['code' => 'USD', 'is_default' => false]);
    }

    public function test_currency_code_is_unique()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        Currency::create([
            'code' => 'SAR',
            'name' => 'Duplicate',
            'symbol' => '?',
            'exchange_rate' => 1,
            'is_default' => false,
            'is_active' => true,
        ]);
    }

    public function test_set_locale_ar_works()
    {
        $response = $this->get('/lang/ar');
        $response->assertRedirect();
        $this->assertEquals('ar', session('locale'));
    }

    public function test_set_locale_en_works()
    {
        $response = $this->get('/lang/en');
        $response->assertRedirect();
        $this->assertEquals('en', session('locale'));
    }

    public function test_set_currency_works()
    {
        $response = $this->get('/currency/USD');
        $response->assertRedirect();
        $this->assertEquals('USD', session('currency'));
    }

    public function test_invalid_locale_does_not_set_session()
    {
        $response = $this->get('/lang/fr');
        $response->assertRedirect();
        // 'fr' is not supported, so session('locale') should remain unset or null
        $this->assertNotEquals('fr', session('locale'));
    }

    public function test_invalid_currency_does_not_set_session()
    {
        $response = $this->get('/currency/XYZ');
        $response->assertRedirect();
        $this->assertNotEquals('XYZ', session('currency'));
    }

    public function test_home_page_returns_ok()
    {
        $response = $this->get('/');
        $response->assertOk();
    }
}
