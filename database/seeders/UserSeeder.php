<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('secret123'),
                'isAdmin' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@email.com'],
            [
                'name' => 'User',
                'email' => 'user@email.com',
                'password' => Hash::make('secret123'),
                'isAdmin' => false,
            ]
        );
    }
}
