<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Office Rent',
                'description' => 'Office or shop rental expenses',
            ],
            [
                'name' => 'Utilities',
                'description' => 'Electricity, water, gas and utility bills',
            ],
            [
                'name' => 'Internet & Phone',
                'description' => 'Internet connection and telephone bills',
            ],
            [
                'name' => 'Salary & Wages',
                'description' => 'Employee salaries, wages and bonuses',
            ],
            [
                'name' => 'Transportation',
                'description' => 'Travel, fuel and transportation costs',
            ],
            [
                'name' => 'Office Supplies',
                'description' => 'Stationery and office supplies',
            ],
            [
                'name' => 'Marketing & Advertising',
                'description' => 'Advertising, promotions and marketing costs',
            ],
            [
                'name' => 'Software & Subscriptions',
                'description' => 'Software licenses, hosting and subscriptions',
            ],
            [
                'name' => 'Maintenance & Repairs',
                'description' => 'Equipment, computer and office repairs',
            ],
            [
                'name' => 'Food & Refreshments',
                'description' => 'Office meals, snacks and refreshments',
            ],

            [
                'name' => 'Taxes & Fees',
                'description' => 'Business taxes, licenses and government fees',
            ],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::query()->create(array_merge($category, [
                'is_active' => true,
            ]));
        }
    }
}
