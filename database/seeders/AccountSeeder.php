<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $store = Store::query()->first();

        if (!$store) {
            return;
        }

        $store->accounts()->create([
            'name' => 'Cash',
            'type' => 'cash',
            'account_no' => null,
            'bank_name' => null,
            'branch_name' => null,
            'opening_balance' => 0,
            'current_balance' => 0,
            'is_default' => true,
            'active' => true,
            'note' => 'Main cash account',
        ]);
    }
}
