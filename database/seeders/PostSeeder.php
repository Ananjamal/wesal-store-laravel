<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        if (class_exists(\Faker\Factory::class)) {
            $admin = User::where('email', 'admin@wisal-store.com')->first();
            
            if ($admin && Post::where('user_id', $admin->id)->count() === 0) {
                Post::factory()->count(3)->create(['user_id' => $admin->id]);
            }
        }
    }
}
