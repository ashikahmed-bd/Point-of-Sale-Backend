<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'name' => 'Piece',
                'short_name' => 'pc',
                'is_decimal' => false,
            ],
            [
                'name' => 'Set',
                'short_name' => 'set',
                'is_decimal' => false,
            ],
            [
                'name' => 'Pair',
                'short_name' => 'pair',
                'is_decimal' => false,
            ],
            [
                'name' => 'Dozen',
                'short_name' => 'doz',
                'is_decimal' => false,
            ],
            [
                'name' => 'Box',
                'short_name' => 'box',
                'is_decimal' => false,
            ],
            [
                'name' => 'Pack',
                'short_name' => 'pack',
                'is_decimal' => false,
            ],
            [
                'name' => 'Carton',
                'short_name' => 'ctn',
                'is_decimal' => false,
            ],
            [
                'name' => 'Meter',
                'short_name' => 'm',
                'is_decimal' => true,
            ],
            [
                'name' => 'Centimeter',
                'short_name' => 'cm',
                'is_decimal' => true,
            ],
            [
                'name' => 'Yard',
                'short_name' => 'yd',
                'is_decimal' => true,
            ],
            [
                'name' => 'Kilogram',
                'short_name' => 'kg',
                'is_decimal' => true,
            ],
            [
                'name' => 'Gram',
                'short_name' => 'g',
                'is_decimal' => true,
            ],
            [
                'name' => 'Liter',
                'short_name' => 'L',
                'is_decimal' => true,
            ],
            [
                'name' => 'Milliliter',
                'short_name' => 'ml',
                'is_decimal' => true,
            ],
        ];

        foreach ($units as $unit) {
            Unit::create([
                'name' => $unit['name'],
                'short_name' => $unit['short_name'],
                'is_decimal' => $unit['is_decimal'],
                'is_active' => true,
            ]);
        }
    }
}
