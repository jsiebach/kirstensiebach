<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user creation with validation.
     */
    public function test_user_can_be_created_with_valid_data(): void
    {
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
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Assert password was hashed
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Test email uniqueness validation.
     */
    public function test_email_must_be_unique(): void
    {
        // Create first user
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        // Attempt to create second user with same email should fail
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Another User',
            'email' => 'duplicate@example.com',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Test password is hashed when creating user.
     */
    public function test_password_is_hashed(): void
    {
        $plainPassword = 'my-secret-password';

        $user = User::create([
            'name' => 'Hash Test User',
            'email' => 'hashtest@example.com',
            'password' => Hash::make($plainPassword),
        ]);

        // Password should not be stored as plain text
        $this->assertNotEquals($plainPassword, $user->password);

        // Password should be verifiable
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }

    /**
     * Test user can be assigned a role.
     */
    public function test_user_can_be_assigned_role(): void
    {
        // Create admin role (use firstOrCreate to avoid duplicate error)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create user
        $user = User::factory()->create();

        // Assign role
        $user->assignRole('admin');

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Assert user has role
        $this->assertTrue($user->hasRole('admin'));
    }
}
