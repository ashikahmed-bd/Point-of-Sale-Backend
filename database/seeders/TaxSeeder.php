<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = [
            [
                'name' => 'No Tax',
                'code' => 'NO-TAX',
                'rate' => 0,
                'type' => 'percentage',
                'calculation' => 'exclusive',
                'is_default' => true,
            ],
            [
                'name' => 'VAT 5%',
                'code' => 'VAT-5',
                'rate' => 5,
                'type' => 'percentage',
                'calculation' => 'exclusive',
                'is_default' => false,
            ],
            [
                'name' => 'VAT 7.5%',
                'code' => 'VAT-7.5',
                'rate' => 7.5,
                'type' => 'percentage',
                'calculation' => 'exclusive',
                'is_default' => false,
            ],
            [
                'name' => 'VAT 10%',
                'code' => 'VAT-10',
                'rate' => 10,
                'type' => 'percentage',
                'calculation' => 'exclusive',
                'is_default' => false,
            ],
            [
                'name' => 'VAT 15%',
                'code' => 'VAT-15',
                'rate' => 15,
                'type' => 'percentage',
                'calculation' => 'exclusive',
                'is_default' => false,
            ],
        ];

        foreach ($taxes as $tax) {
            Tax::create([
                'name' => $tax['name'],
                'code' => $tax['code'],
                'rate' => $tax['rate'],
                'type' => $tax['type'],
                'calculation' => $tax['calculation'],
                'is_default' => $tax['is_default'],
                'is_active' => true,
            ]);
        }
    }
}
