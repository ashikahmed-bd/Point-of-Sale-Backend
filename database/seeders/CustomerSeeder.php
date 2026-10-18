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

        $customers = [
            [
                'phone' => '01800000001',
                'name' => 'Walk-in Customer',
                'email' => 'info@example.com',
                'address' => '12 Mirpur Road',
                'city' => 'Dhaka',
                'state' => 'Dhaka Division',
                'postcode' => '1216',
                'opening_balance' => 0,
                'credit_limit' => null,
                'notes' => 'Default walk-in customer',
                'is_active' => true,
            ],
            [
                'phone' => '01800000002',
                'name' => 'Rahim Ahmed',
                'email' => 'rahim@example.com',
                'address' => 'House 15, Road 7, Dhanmondi',
                'city' => 'Dhaka',
                'state' => 'Dhaka Division',
                'postcode' => '1209',
                'opening_balance' => 1500,
                'credit_limit' => 10000,
                'notes' => 'Regular customer, prefers monthly purchases',
                'is_active' => true,
            ],
            [
                'phone' => '01800000003',
                'name' => 'Karim Hasan',
                'email' => 'karim@example.com',
                'address' => 'House 22, Block C, Mirpur 10',
                'city' => 'Dhaka',
                'state' => 'Dhaka Division',
                'postcode' => '1216',
                'opening_balance' => 500,
                'credit_limit' => 5000,
                'notes' => 'Frequent customer, pays regularly',
                'is_active' => true,
            ],
            [
                'phone' => '01800000004',
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat@example.com',
                'address' => 'Road 12, Uttara Sector 4',
                'city' => 'Dhaka',
                'state' => 'Dhaka Division',
                'postcode' => '1230',
                'opening_balance' => 0,
                'credit_limit' => 8000,
                'notes' => 'Prefers online order updates',
                'is_active' => true,
            ],
            [
                'phone' => '01800000005',
                'name' => 'Tanvir Hossain',
                'email' => 'tanvir@example.com',
                'address' => '45 Agrabad Commercial Area',
                'city' => 'Chattogram',
                'state' => 'Chattogram Division',
                'postcode' => '4100',
                'opening_balance' => 2500,
                'credit_limit' => 15000,
                'notes' => 'Business customer, bulk orders',
                'is_active' => true,
            ],
            [
                'phone' => '01800000006',
                'name' => 'Sadia Islam',
                'email' => 'sadia@example.com',
                'address' => 'House 8, Zindabazar',
                'city' => 'Sylhet',
                'state' => 'Sylhet Division',
                'postcode' => '3100',
                'opening_balance' => 300,
                'credit_limit' => 6000,
                'notes' => 'Regular retail customer',
                'is_active' => true,
            ],
            [
                'phone' => '01800000007',
                'name' => 'Imran Hossain',
                'email' => 'imran@example.com',
                'address' => '23 Shaheb Bazar',
                'city' => 'Rajshahi',
                'state' => 'Rajshahi Division',
                'postcode' => '6100',
                'opening_balance' => 1200,
                'credit_limit' => 10000,
                'notes' => 'Occasional bulk purchases',
                'is_active' => true,
            ],
            [
                'phone' => '01800000008',
                'name' => 'Farhana Akter',
                'email' => 'farhana@example.com',
                'address' => 'House 10, Sonadanga',
                'city' => 'Khulna',
                'state' => 'Khulna Division',
                'postcode' => '9100',
                'opening_balance' => 0,
                'credit_limit' => 7000,
                'notes' => 'Prefers cash payments',
                'is_active' => true,
            ],
            [
                'phone' => '01800000009',
                'name' => 'Sabbir Rahman',
                'email' => 'sabbir@example.com',
                'address' => '18 Station Road',
                'city' => 'Rangpur',
                'state' => 'Rangpur Division',
                'postcode' => '5400',
                'opening_balance' => 800,
                'credit_limit' => 5000,
                'notes' => 'Returning customer',
                'is_active' => true,
            ],
            [
                'phone' => '01800000010',
                'name' => 'Ayesha Siddika',
                'email' => 'ayesha@example.com',
                'address' => '32 Kandirpar',
                'city' => 'Cumilla',
                'state' => 'Chattogram Division',
                'postcode' => '3500',
                'opening_balance' => 200,
                'credit_limit' => 4000,
                'notes' => 'Small business customer',
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::query()->create(
                array_merge($customer, [
                    'is_active' => true,
                ])
            );
        }
    }
}
