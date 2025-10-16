<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Role;

uses()->group('browser', 'admin', 'authentication');

beforeEach(function (): void {
    // Ensure admin role exists
    Role::firstOrCreate(['name' => 'admin']);
});

test('admin user can log in and access admin panel', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->visit('/admin/login')
        ->waitForText('Sign in')
        ->assertSee('Siebach Lab')
        ->type('#form\\.email', $admin->email)
        ->type('#form\\.password', 'password')
        ->submit()
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin');
});

test('guest user redirects to login when accessing admin panel', function (): void {
    $this->visit('/admin')
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin/login')
        ->waitForText('Sign in')
        ->assertSee('Siebach Lab');
});

test('non-admin user cannot access admin panel', function (): void {
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

test('admin can log out successfully', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Login
    $this->visit('/admin/login')
        ->waitForText('Sign in')
        ->type('#form\\.email', $admin->email)
        ->type('#form\\.password', 'password')
        ->submit()
        ->waitForEvent('networkidle')
        ->assertPathIs('/admin');

    // TODO: Fix logout flow - user menu button selector needs investigation
    // ->click('button[aria-label="Open user menu"]')
    // ->waitForText('Sign out')
    // ->click('Sign out')
    // ->waitForEvent('networkidle')
    // ->assertPathIs('/admin/login');
})->skip('Logout flow needs user menu selector fix');
