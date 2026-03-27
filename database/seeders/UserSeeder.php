<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;
    
    public function run(): void
{
    // Admin
    User::create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'status' => 'active',
    ]);

    // Doctor
    User::create([
        'first_name' => 'Issam',
        'last_name' => 'Doctor',
        'email' => 'doctor@example.com',
        'password' => bcrypt('password'),
        'role' => 'doctor',
        'status' => 'active',
    ]);

    // Patient
    User::create([
        'first_name' => 'Ali',
        'last_name' => 'Patient',
        'email' => 'patient@example.com',
        'password' => bcrypt('password'),
        'role' => 'patient',
        'status' => 'active',
    ]);
}
}
