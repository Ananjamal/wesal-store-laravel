<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roles
        $adminRole    = Role::firstOrCreate(['name' => 'Admin',    'guard_name' => 'web']);
        $managerRole  = Role::firstOrCreate(['name' => 'Manager',  'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);

        // 2. Create Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@wisal-store.com'],
            [
                'name'      => 'مدير النظام',
                'password'  => Hash::make('1234567890'),
                'is_active' => true,
            ]
        );

        // Always ensure admin has the Admin role
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole($adminRole);
        }

        // Create Default Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@wisal-store.com'],
            [
                'name'      => 'مدير العمليات',
                'password'  => Hash::make('password123'),
                'is_active' => true,
            ]
        );

        // Always ensure manager has the Manager role
        if (!$manager->hasRole('Manager')) {
            $manager->assignRole($managerRole);
        }
    }
}
