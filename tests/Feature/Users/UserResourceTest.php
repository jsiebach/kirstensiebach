<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

test('user can be created with valid data', function (): void {
    // Create admin role for auth (use firstOrCreate to avoid duplicate error)
    Role::firstOrCreate(['name' => 'admin']);
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Clear permission cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create a new user
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
    ];

    $user = User::create([
        'name' => $userData['name'],
        'email' => $userData['email'],
        'password' => Hash::make($userData['password']),
    ]);

    // Assert user was created
    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');

    // Assert password was hashed
    expect(Hash::check('password123', $user->password))->toBeTrue();
});

test('email must be unique', function (): void {
    // Create first user
    User::factory()->create([
        'email' => 'duplicate@example.com',
    ]);

    // Attempt to create second user with same email should fail
    expect(fn () => User::create([
        'name' => 'Another User',
        'email' => 'duplicate@example.com',
        'password' => Hash::make('password'),
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

test('password is hashed', function (): void {
    $plainPassword = 'my-secret-password';

    $user = User::create([
        'name' => 'Hash Test User',
        'email' => 'hashtest@example.com',
        'password' => Hash::make($plainPassword),
    ]);

    // Password should not be stored as plain text
    expect($user->password)->not->toBe($plainPassword);

    // Password should be verifiable
    expect(Hash::check($plainPassword, $user->password))->toBeTrue();
});

test('user can be assigned role', function (): void {
    // Create admin role (use firstOrCreate to avoid duplicate error)
    $adminRole = Role::firstOrCreate(['name' => 'admin']);

    // Create user
    $user = User::factory()->create();

    // Assign role
    $user->assignRole('admin');

    // Clear cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Assert user has role
    expect($user->hasRole('admin'))->toBeTrue();
});
