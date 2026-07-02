<?php

namespace Tests\Feature;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class AdminDay6Test extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($role);
    }

    public function test_admin_can_access_posts(): void
    {
        $this->actingAs($this->admin)
            ->get(PostResource::getUrl('index'))
            ->assertSuccessful();
    }

    public function test_admin_can_access_reports_page(): void
    {
        $this->actingAs($this->admin)
            ->get(\App\Filament\Pages\ReportsPage::getUrl())
            ->assertSuccessful();
    }

    public function test_admin_can_access_store_settings(): void
    {
        $this->actingAs($this->admin)
            ->get(\App\Filament\Resources\StoreSettingResource::getUrl('index'))
            ->assertSuccessful();
    }

    public function test_admin_can_access_audit_logs(): void
    {
        $this->actingAs($this->admin)
            ->get(\App\Filament\Resources\AuditLogResource::getUrl('index'))
            ->assertSuccessful();
    }
}
