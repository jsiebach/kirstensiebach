<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign admin role to all existing users
        User::all()->each(function ($user) use ($adminRole) {
            if (!$user->hasRole('admin')) {
                $user->assignRole($adminRole);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove admin role from all users (if needed for rollback)
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            User::all()->each(function ($user) use ($adminRole) {
                $user->removeRole($adminRole);
            });
        }
    }
};
