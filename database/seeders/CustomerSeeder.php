<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create(
            [
                'phone' => '01800000000',
                'name' => 'Walk-in Customer',
                'email' => 'info@example.com',

                'address' => null,
                'city' => null,
                'state' => null,
                'postcode' => null,

                'opening_balance' => 0,
                'credit_limit' => null,

                'notes' => 'Default walk-in customer',

                'is_active' => true,
            ],
        );
    }
}
