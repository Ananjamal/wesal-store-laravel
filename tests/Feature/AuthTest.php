<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Manager']);
        Role::create(['name' => 'Customer']);
    }

    public function test_user_can_register_and_receives_token_with_customer_role(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Lena Ahmad',
            'email'                 => 'lena@wisal.com',
            'phone'                 => '0501234567',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => ['user' => ['id', 'name', 'email', 'roles'], 'token'],
            ])
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('users', ['email' => 'lena@wisal.com']);

        $user = User::where('email', 'lena@wisal.com')->first();
        $this->assertTrue($user->hasRole('Customer'));
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'duplicate@wisal.com']);

        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Test',
            'email'                 => 'duplicate@wisal.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_login_and_receives_token(): void
    {
        $user = User::factory()->create([
            'email'     => 'user@wisal.com',
            'password'  => bcrypt('password123'),
            'is_active' => true,
        ]);
        $user->assignRole('Customer');

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'user@wisal.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'data' => ['token', 'user'],
            ]);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        User::factory()->create([
            'email'    => 'user@wisal.com',
            'password' => bcrypt('correctpassword'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'user@wisal.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['success' => false]);
    }

    public function test_authenticated_user_can_access_me_endpoint(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Customer');

        \Laravel\Sanctum\Sanctum::actingAs($user, ['*']);
        $response = $this->getJson('/api/me');

        $response->assertOk()
            ->assertJson(['success' => true])
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_unauthenticated_request_returns_401(): void
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create(['is_active' => true]);
        $user->assignRole('Customer');

        \Laravel\Sanctum\Sanctum::actingAs($user, ['*']);
        $response = $this->postJson('/api/auth/logout');

        $response->assertOk()
            ->assertJson(['success' => true]);
    }
}
