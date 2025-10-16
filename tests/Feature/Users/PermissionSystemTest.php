<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Role;

test('admin user has correct role', function (): void {
    // Create admin role (use firstOrCreate to avoid duplicate error)
    $adminRole = Role::firstOrCreate(['name' => 'admin']);

    // Create user with admin role
    $user = User::factory()->create();
    $user->assignRole('admin');

    // Clear the permission cache to ensure fresh data
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Verify the user has the admin role
    expect($user->hasRole('admin'))->toBeTrue();

    // Verify auth check would pass
    $this->actingAs($user);
    expect(auth()->check())->toBeTrue();
    expect(auth()->user()->hasRole('admin'))->toBeTrue();
});

test('non admin user cannot access filament panel', function (): void {
    // Create user without admin role
    $user = User::factory()->create();

    // Act as the user and try to visit Filament dashboard
    $response = $this->actingAs($user)->get('/admin');

    // Should get 403 forbidden
    $response->assertStatus(403);
});

test('role assignment works', function (): void {
    // Create admin role (use firstOrCreate to avoid duplicate error)
    $adminRole = Role::firstOrCreate(['name' => 'admin']);

    // Create user
    $user = User::factory()->create();

    // Initially should not have admin role
    expect($user->hasRole('admin'))->toBeFalse();

    // Assign admin role
    $user->assignRole('admin');

    // Refresh user from database
    $user->refresh();

    // Should now have admin role
    expect($user->hasRole('admin'))->toBeTrue();
});

test('guest redirected to login', function (): void {
    // Try to visit Filament dashboard without authentication
    $response = $this->get('/admin');

    // Should redirect to login
    $response->assertRedirect('/admin/login');
});
