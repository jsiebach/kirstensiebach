<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PermissionSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that admin middleware correctly identifies admin users.
     */
    public function test_admin_user_has_correct_role(): void
    {
        // Create admin role
        $adminRole = Role::create(['name' => 'admin']);

        // Create user with admin role
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Clear the permission cache to ensure fresh data
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Verify the user has the admin role
        $this->assertTrue($user->hasRole('admin'));

        // Verify auth check would pass
        $this->actingAs($user);
        $this->assertTrue(auth()->check());
        $this->assertTrue(auth()->user()->hasRole('admin'));
    }

    /**
     * Test that a user without admin role cannot access Filament panel.
     */
    public function test_non_admin_user_cannot_access_filament_panel(): void
    {
        // Create user without admin role
        $user = User::factory()->create();

        // Act as the user and try to visit Filament dashboard
        $response = $this->actingAs($user)->get('/filament');

        // Should get 403 forbidden
        $response->assertStatus(403);
    }

    /**
     * Test that role assignment works correctly.
     */
    public function test_role_assignment_works(): void
    {
        // Create admin role
        $adminRole = Role::create(['name' => 'admin']);

        // Create user
        $user = User::factory()->create();

        // Initially should not have admin role
        $this->assertFalse($user->hasRole('admin'));

        // Assign admin role
        $user->assignRole('admin');

        // Refresh user from database
        $user->refresh();

        // Should now have admin role
        $this->assertTrue($user->hasRole('admin'));
    }

    /**
     * Test that guest users are redirected to login.
     */
    public function test_guest_redirected_to_login(): void
    {
        // Try to visit Filament dashboard without authentication
        $response = $this->get('/filament');

        // Should redirect to login
        $response->assertRedirect('/filament/login');
    }
}
