<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function overview(Request $request)
    {
        return response()->json([
            'summary' => [
                'total_balance' => 185000.00,
                'total_income' => 85000.00,
                'total_expenses' => 32500.00,
                'net_cash_flow' => 52500.00,
                'currency' => 'BDT',
            ],

            'accounts' => [
                [
                    'id' => '01m5s07dzxn15see6jf9eweqea',
                    'name' => 'Cash',
                    'number' => null,
                    'bank' => null,
                    'branch' => null,
                    'current_balance' => 25000.00,
                    'currency' => 'BDT',
                    'is_default' => true,
                    'active' => true,
                ],
                [
                    'id' => '01m5s07dzxn15see6jf9eweqeb',
                    'name' => 'Bank Account',
                    'number' => '1234567890',
                    'bank' => 'Eastern Bank PLC',
                    'branch' => 'Dhaka',
                    'current_balance' => 125000.00,
                    'currency' => 'BDT',
                    'is_default' => false,
                    'active' => true,
                ],
                [
                    'id' => '01m5s07dzxn15see6jf9eweqec',
                    'name' => 'bKash',
                    'number' => '017XXXXXXXX',
                    'bank' => null,
                    'branch' => null,
                    'current_balance' => 35000.00,
                    'currency' => 'BDT',
                    'is_default' => false,
                    'active' => true,
                ],
            ],

            'cash_flow' => [
                'period' => 'this_month',
                'income' => 85000.00,
                'expenses' => 32500.00,
                'net_cash_flow' => 52500.00,
                'currency' => 'BDT',
            ],

            'transactions' => [
                [
                    'id' => '01m5s07dzxn15see6jf9eweqed',
                    'transaction_no' => 'EXP-20261025-001',
                    'title' => 'Office Rent',
                    'type' => 'expense',
                    'date' => '2026-10-25T10:00:00',
                    'account' => [
                        'id' => '01m5s07dzxn15see6jf9eweqea',
                        'name' => 'Cash',
                    ],
                    'amount' => 15000.00,
                    'currency' => 'BDT',
                    'status' => 'completed',
                ],
                [
                    'id' => '01m5s07dzxn15see6jf9eweqee',
                    'transaction_no' => 'INC-20261025-001',
                    'title' => 'Customer Payment',
                    'type' => 'income',
                    'date' => '2026-10-25T11:30:00',
                    'account' => [
                        'id' => '01m5s07dzxn15see6jf9eweqec',
                        'name' => 'bKash',
                    ],
                    'amount' => 12500.00,
                    'currency' => 'BDT',
                    'status' => 'completed',
                ],
                [
                    'id' => '01m5s07dzxn15see6jf9eweqef',
                    'transaction_no' => 'EXP-20261024-002',
                    'title' => 'Internet Bill',
                    'type' => 'expense',
                    'date' => '2026-10-24T09:30:00',
                    'account' => [
                        'id' => '01m5s07dzxn15see6jf9eweqeb',
                        'name' => 'Bank Account',
                    ],
                    'amount' => 1200.00,
                    'currency' => 'BDT',
                    'status' => 'completed',
                ],
            ],
        ]);
    }

    public function accounts(Request $request) {}

    public function transactions(Request $request) {}

    public function cashFlow(Request $request) {}

    public function profitLoss(Request $request) {}

    public function balanceSheet(Request $request) {}
}
