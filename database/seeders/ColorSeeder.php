<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'أبيض',      'hex_code' => '#FFFFFF', 'sort_order' => 1],
            ['name' => 'أسود',      'hex_code' => '#000000', 'sort_order' => 2],
            ['name' => 'رمادي',     'hex_code' => '#808080', 'sort_order' => 3],
            ['name' => 'أحمر',      'hex_code' => '#EF4444', 'sort_order' => 4],
            ['name' => 'وردي',      'hex_code' => '#EC4899', 'sort_order' => 5],
            ['name' => 'برتقالي',   'hex_code' => '#F97316', 'sort_order' => 6],
            ['name' => 'أصفر',      'hex_code' => '#EAB308', 'sort_order' => 7],
            ['name' => 'أخضر',      'hex_code' => '#22C55E', 'sort_order' => 8],
            ['name' => 'أزرق',      'hex_code' => '#3B82F6', 'sort_order' => 9],
            ['name' => 'أزرق فاتح', 'hex_code' => '#06B6D4', 'sort_order' => 10],
            ['name' => 'بنفسجي',   'hex_code' => '#A855F7', 'sort_order' => 11],
            ['name' => 'بني',       'hex_code' => '#92400E', 'sort_order' => 12],
            ['name' => 'بيج',       'hex_code' => '#D4B896', 'sort_order' => 13],
            ['name' => 'ذهبي',      'hex_code' => '#D4AF37', 'sort_order' => 14],
            ['name' => 'فضي',       'hex_code' => '#C0C0C0', 'sort_order' => 15],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(
                ['name' => $color['name']],
                array_merge($color, ['is_active' => true])
            );
        }
    }
}
