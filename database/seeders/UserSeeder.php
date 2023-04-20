<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default Admin account
        UserFactory::new()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@store.dev',
            'password' => Hash::make('admin'),
            'is_admin' => true,
        ]);

        // Default User accounts
        UserFactory::new()->count(20)->create([
            'is_admin' => false,
        ]);
    }
}
