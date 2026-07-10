<?php

namespace Database\Seeders;

use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'store_name',  'value' => 'متجر وصال',             'type' => 'string'],
            ['key' => 'store_email', 'value' => 'info@wisal-store.com', 'type' => 'string'],
            ['key' => 'store_phone', 'value' => '',                     'type' => 'string'],
            ['key' => 'store_address', 'value' => '',                   'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            StoreSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
