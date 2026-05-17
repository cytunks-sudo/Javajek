<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@javajek.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $user->roles()->updateOrCreate(
            ['role' => 'admin'],
            ['status' => 'approved']
        );

        $user->roles()->updateOrCreate(
            ['role' => 'customer'],
            ['status' => 'approved']
        );
    }
}