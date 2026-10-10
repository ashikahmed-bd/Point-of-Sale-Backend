<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Store::create([
            'name' => 'Main Store',
            'code' => 'MAIN',

            'phone' => '+8801700000000',
            'email' => 'info@example.com',

            'address' => 'Dhaka, Bangladesh',
            'city' => 'Dhaka',
            'state' => 'Dhaka',
            'postcode' => '1200',
            'country' => 'Bangladesh',

            'is_active' => true,
        ]);
    }
}
