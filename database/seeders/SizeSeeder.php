<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            ['name' => 'XS',  'label' => 'صغير جداً',    'sort_order' => 1],
            ['name' => 'S',   'label' => 'صغير',          'sort_order' => 2],
            ['name' => 'M',   'label' => 'متوسط',         'sort_order' => 3],
            ['name' => 'L',   'label' => 'كبير',           'sort_order' => 4],
            ['name' => 'XL',  'label' => 'كبير جداً',     'sort_order' => 5],
            ['name' => 'XXL', 'label' => 'كبير جداً جداً', 'sort_order' => 6],
            ['name' => 'XXXL','label' => 'ضخم',           'sort_order' => 7],
        ];

        foreach ($sizes as $size) {
            Size::firstOrCreate(
                ['name' => $size['name']],
                array_merge($size, ['is_active' => true])
            );
        }
    }
}
