<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Rahim Uddin',
                'company_name' => 'Rahim Traders',
                'phone' => '01711000001',
                'email' => 'rahim@example.com',
                'address' => '12 Nawabpur Road',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'postcode' => '1100',
            ],
            [
                'name' => 'Karim Ahmed',
                'company_name' => 'Karim Enterprise',
                'phone' => '01711000002',
                'email' => 'karim@example.com',
                'address' => '45 Chawkbazar',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'postcode' => '1211',
            ],
            [
                'name' => 'Abdul Hasan',
                'company_name' => 'Hasan Trading',
                'phone' => '01711000003',
                'email' => 'hasan@example.com',
                'address' => 'Station Road',
                'city' => 'Chattogram',
                'state' => 'Chattogram',
                'postcode' => '4000',
            ],
            [
                'name' => 'Mohammad Ali',
                'company_name' => 'Ali Distributors',
                'phone' => '01711000004',
                'email' => 'ali@example.com',
                'address' => 'Tongi Bazar',
                'city' => 'Gazipur',
                'state' => 'Gazipur',
                'postcode' => '1710',
            ],
            [
                'name' => 'Jamal Hossain',
                'company_name' => 'Jamal Supply House',
                'phone' => '01711000005',
                'email' => 'jamal@example.com',
                'address' => 'Station Road',
                'city' => 'Rajshahi',
                'state' => 'Rajshahi',
                'postcode' => '6000',
            ],
        ];

        foreach ($suppliers as $key => $supplier) {
            Supplier::query()->create(
                array_merge($supplier, [
                    'opening_balance' => 0,
                    'credit_limit' => null,
                    'notes' => null,
                    'is_active' => true,
                ])
            );
        }
    }
}
