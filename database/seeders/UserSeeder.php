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
        User::insert([
            [
                'name'     => 'Admin',
                'email'    => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role'     => '0',
            ],
            [
                'name'     => 'User',
                'email'    => 'user@user.com',
                'password' => Hash::make('password'),
                'role'     => '1',
            ]
        ]);

    }
}
