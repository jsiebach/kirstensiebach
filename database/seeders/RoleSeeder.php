<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign admin role to jsiebach@gmail.com
        $user = User::where('email', 'jsiebach@gmail.com')->first();
        if ($user && !$user->hasRole('admin')) {
            $user->assignRole('admin');
            $this->command->info("Assigned admin role to {$user->email}");
        }

        // Assign admin role to ksiebach@gmail.com if exists
        $kirstenUser = User::where('email', 'ksiebach@gmail.com')->first();
        if ($kirstenUser && !$kirstenUser->hasRole('admin')) {
            $kirstenUser->assignRole('admin');
            $this->command->info("Assigned admin role to {$kirstenUser->email}");
        }
    }
}
