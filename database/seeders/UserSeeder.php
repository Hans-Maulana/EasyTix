<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'phone_number' => '081234567890',
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Organizer PK Entertainment',
            'email' => 'organizer@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'phone_number' => '081234567891',
            'role' => 'organizer',
        ]);

        \App\Models\User::create([
            'name' => 'User Biasa',
            'email' => 'user@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'phone_number' => '081234567892',
            'role' => 'user',
        ]);
    }
}
