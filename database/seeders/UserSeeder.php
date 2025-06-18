<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id' => (string) Str::uuid(),
                'name' => 'Nguyen Van A',
                'phone' => '0900000001',
                'email' => 'a@example.com',
                'password' => Hash::make('password123'),
                'avatar' => 'default-avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Tran Thi B',
                'phone' => '0900000002',
                'email' => 'b@example.com',
                'password' => Hash::make('password123'),
                'avatar' => 'default-avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Le Van C',
                'phone' => '0900000003',
                'email' => 'c@example.com',
                'password' => Hash::make('password123'),
                'avatar' => 'default-avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Pham Thi D',
                'phone' => '0900000004',
                'email' => 'd@example.com',
                'password' => Hash::make('password123'),
                'avatar' => 'default-avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Hoang Van E',
                'phone' => '0900000005',
                'email' => 'e@example.com',
                'password' => Hash::make('password123'),
                'avatar' => 'default-avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
