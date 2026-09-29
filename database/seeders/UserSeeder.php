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
        $users = [
            [
                'name' => 'Owner',
                'email' => 'owner@example.com',
                'role' => 'owner',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@example.com',
                'role' => 'manager',
            ],
            [
                'name' => 'Cashier',
                'email' => 'cashier@example.com',
                'role' => 'cashier',
            ],
            [
                'name' => 'Accountant',
                'email' => 'accountant@example.com',
                'role' => 'accountant',
            ],
            [
                'name' => 'Inventory Manager',
                'email' => 'inventory@example.com',
                'role' => 'inventory',
            ],
        ];

        foreach ($users as $user) {
            User::query()->create(array_merge($user, [
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'birthday' => '1995-02-25',
                'gender' => 'male',
                'disabled' => false,
                'disk' => 'public',
                'last_seen_at' => now(),
            ]));
        }
    }
}
