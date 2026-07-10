<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'SAR', 'name' => 'ريال سعودي',       'symbol' => 'ر.س', 'exchange_rate' => 1.000000,  'is_default' => false, 'is_active' => true],
            ['code' => 'USD', 'name' => 'دولار أمريكي',      'symbol' => '$',   'exchange_rate' => 0.266667,  'is_default' => false, 'is_active' => true],
            ['code' => 'EGP', 'name' => 'جنيه مصري',        'symbol' => 'ج.م', 'exchange_rate' => 12.800000, 'is_default' => false, 'is_active' => true],
            ['code' => 'EUR', 'name' => 'يورو',             'symbol' => '€',   'exchange_rate' => 0.250000,  'is_default' => false, 'is_active' => true],
            ['code' => 'ILS', 'name' => 'شيكل إسرائيلي',     'symbol' => '₪',   'exchange_rate' => 0.970000,  'is_default' => true,  'is_active' => true],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }
    }
}
