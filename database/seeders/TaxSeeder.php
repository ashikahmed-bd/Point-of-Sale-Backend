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
                'rate' => 0,
                'type' => 'percentage',
            ],
            [
                'name' => 'VAT 5%',
                'rate' => 5,
                'type' => 'percentage',
            ],
            [
                'name' => 'VAT 7.5%',
                'rate' => 7.5,
                'type' => 'percentage',
            ],
            [
                'name' => 'VAT 10%',
                'rate' => 10,
                'type' => 'percentage',
            ],
            [
                'name' => 'VAT 15%',
                'rate' => 15,
                'type' => 'percentage',
            ],
        ];

        foreach ($taxes as $tax) {
            Tax::create([
                'name' => $tax['name'],
                'rate' => $tax['rate'],
                'type' => $tax['type'],
                'is_active' => true,
            ]);
        }
    }
}
