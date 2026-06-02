<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@javajek.local',
                'phone' => '080000000001',
                'address' => 'JavaJek Admin',
                'password' => Hash::make('admin'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['username' => 'merchant'],
            [
                'name' => 'Merchant Demo',
                'email' => 'merchant@javajek.local',
                'phone' => '080000000002',
                'address' => 'JavaJek Merchant',
                'password' => Hash::make('merchant'),
                'role' => 'merchant',
            ]
        );

        User::updateOrCreate(
            ['username' => 'driver'],
            [
                'name' => 'Driver Demo',
                'email' => 'driver@javajek.local',
                'phone' => '080000000003',
                'address' => 'JavaJek Driver',
                'password' => Hash::make('driver'),
                'role' => 'driver',
            ]
        );

        User::updateOrCreate(
            ['username' => 'customer'],
            [
                'name' => 'Customer Demo',
                'email' => 'customer@javajek.local',
                'phone' => '080000000004',
                'address' => 'JavaJek Customer',
                'password' => Hash::make('customer'),
                'role' => 'customer',
            ]
        );
    }
}