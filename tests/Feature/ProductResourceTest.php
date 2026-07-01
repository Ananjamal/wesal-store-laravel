<?php

namespace Tests\Feature;

use App\Filament\Resources\ProductResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class ProductResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $this->user = User::factory()->create();
        $this->user->assignRole($role);
    }

    public function test_admin_can_view_product_list(): void
    {
        $this->actingAs($this->user)
            ->get(ProductResource::getUrl('index'))
            ->assertSuccessful();
    }

    public function test_admin_can_view_product_create_page(): void
    {
        $this->actingAs($this->user)
            ->get(ProductResource::getUrl('create'))
            ->assertSuccessful();
    }
}
