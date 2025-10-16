<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Role;

uses()->group('browser', 'admin', 'authorization');

beforeEach(function (): void {
    // Ensure admin role exists with web guard
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
});

test('admin role can access all filament resources', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    loginAsAdmin($this, $admin)
        ->assertPathIs('/admin')
        ->waitForEvent('networkidle')
        ->assertSee('Research')
        ->assertSee('Publications');
});

test('non-admin cannot access protected resources', function (): void {
    $user = User::factory()->create();

    $this->visit('/admin/login')
        ->waitForText('Sign in')
        ->type('#form\\.email', $user->email)
        ->type('#form\\.password', 'password')
        ->submit()
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/login')
        ->assertSee('These credentials do not match our records');
});

test('role-based menu items display correctly for admin', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    loginAsAdmin($this, $admin)
        ->assertPathIs('/admin')
        ->assertSee('Pages')
        ->assertSee('Content')
        ->assertSee('Users');
});

test('admin can access user management resource', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    loginAsAdmin($this, $admin)
        ->assertPathIs('/admin')
        ->click('Users')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/users')
        ->assertSee('Users');
});
