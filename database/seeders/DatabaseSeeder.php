<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);

        // 2. Create Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@wisal-store.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        // Assign Role
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
        }

        // Output info if run via command line
        $this->command->info('Admin user seeded: email: admin@wisal-store.com, password: password123');
    }
}
