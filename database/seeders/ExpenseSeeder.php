<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ExpenseCategory::query()->where('is_active', true)->get();

        if ($categories->isEmpty()) {
            return;
        }

        $expenses = [
            [
                'amount' => 15000,
                'expense_date' => now()->subDays(1),
                'reference' => 'REF-EXP-001',
                'description' => 'Monthly office rent payment',
            ],
            [
                'amount' => 2500,
                'expense_date' => now()->subDays(2),
                'reference' => 'REF-EXP-002',
                'description' => 'Monthly electricity bill',
            ],
            [
                'amount' => 1200,
                'expense_date' => now()->subDays(3),
                'reference' => 'REF-EXP-003',
                'description' => 'Monthly internet bill',
            ],
            [
                'amount' => 800,
                'expense_date' => now()->subDays(4),
                'reference' => 'REF-EXP-004',
                'description' => 'Business transportation expenses',
            ],
            [
                'amount' => 1500,
                'expense_date' => now()->subDays(5),
                'reference' => 'REF-EXP-005',
                'description' => 'Office stationery purchase',
            ],
            [
                'amount' => 3500,
                'expense_date' => now()->subDays(6),
                'reference' => 'REF-EXP-006',
                'description' => 'Online advertising expenses',
            ],
            [
                'amount' => 2000,
                'expense_date' => now()->subDays(7),
                'reference' => 'REF-EXP-007',
                'description' => 'Software subscription payment',
            ],
            [
                'amount' => 1800,
                'expense_date' => now()->subDays(8),
                'reference' => 'REF-EXP-008',
                'description' => 'Computer maintenance expenses',
            ],
            [
                'amount' => 600,
                'expense_date' => now()->subDays(9),
                'reference' => 'REF-EXP-009',
                'description' => 'Office refreshments',
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::query()->create(array_merge($expense, [
                'expense_no' => 'EXP-' . now()->format('YmdHis'),
                'store_id' => Store::first()?->id,
                'category_id' =>  $categories->random()->id,
                'account_id' =>  Account::first()?->id,
                'created_by' => User::first()?->id,
            ]));
        }
    }
}
