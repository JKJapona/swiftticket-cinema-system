<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users
        DB::table('users')->insert([
            [
                'full_name' => 'Admin User',
                'email' => 'admin@asd.asd',
                'password_hash' => Hash::make('asdasd'),
                'role' => 'admin',
                'created_at' => now(),
            ]
        ]);
        
    }
}