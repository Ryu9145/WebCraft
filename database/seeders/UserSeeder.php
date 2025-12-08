<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. User Biasa
        User::create([
            'username' => 'BudiUser',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123456'), // Password aman (Bcrypt)
            'role' => 'user',
            'status' => 'active',
        ]);

        // 2. Admin
        User::create([
            'username' => 'SitiAdmin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 3. Super Admin
        User::create([
            'username' => 'BosBesar',
            'email' => 'super@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'superadmin',
            'status' => 'active',
        ]);
    }
}