<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Jeff Siebach',
            'email' => 'jsiebach@gmail.com',
            'password' => \Hash::make(config('auth.default_user_password')),
        ]);
    }
}
