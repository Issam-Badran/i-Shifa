<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin account
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@ishifa.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Doctor account
        User::create([
            'first_name' => 'Ahmed',
            'last_name' => 'Hassan',
            'email' => 'doctor@ishifa.com',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
            'latitude' => 35.5123456,
            'longitude' => 35.7801234,
        ]);

        // Patient account
        User::create([
            'first_name' => 'Ali',
            'last_name' => 'Khaled',
            'email' => 'patient@ishifa.com',
            'password' => Hash::make('password123'),
            'role' => 'patient',
            'latitude' => 35.5012345,
            'longitude' => 35.7905678,
        ]);

    }
}
