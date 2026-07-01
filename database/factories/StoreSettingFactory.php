<?php

namespace Database\Factories;

use App\Models\StoreSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreSettingFactory extends Factory
{
    protected $model = StoreSetting::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(2, '_'),
            'value' => $this->faker->word(),
            'type' => 'string',
        ];
    }
}
