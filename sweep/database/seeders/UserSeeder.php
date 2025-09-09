<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin3@example.com',
            'password' => Hash::make('12345678'),
            'role' => 1,
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user3@example.com',
            'password' => Hash::make("QWer!@34"),
            'role' => 0,
        ]);
    }
}