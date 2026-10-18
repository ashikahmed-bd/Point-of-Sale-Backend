<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'color',
                'options' => [
                    ['name' => 'Black', 'slug' => 'black'],
                    ['name' => 'White', 'slug' => 'white'],
                    ['name' => 'Red', 'slug' => 'red'],
                    ['name' => 'Green', 'slug' => 'green'],
                    ['name' => 'Blue', 'slug' => 'blue'],
                    ['name' => 'Yellow', 'slug' => 'yellow'],
                    ['name' => 'Pink', 'slug' => 'pink'],
                    ['name' => 'Purple', 'slug' => 'purple'],
                ],
            ],

            [
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'select',
                'options' => [
                    ['name' => 'XS', 'slug' => 'xs'],
                    ['name' => 'S', 'slug' => 's'],
                    ['name' => 'M', 'slug' => 'm'],
                    ['name' => 'L', 'slug' => 'l'],
                    ['name' => 'XL', 'slug' => 'xl'],
                    ['name' => 'XXL', 'slug' => 'xxl'],
                ],
            ],

            [
                'name' => 'Fabric',
                'slug' => 'fabric',
                'type' => 'select',
                'options' => [
                    ['name' => 'Cotton', 'slug' => 'cotton'],
                    ['name' => 'Polyester', 'slug' => 'polyester'],
                    ['name' => 'Linen', 'slug' => 'linen'],
                    ['name' => 'Denim', 'slug' => 'denim'],
                    ['name' => 'Silk', 'slug' => 'silk'],
                    ['name' => 'Wool', 'slug' => 'wool'],
                ],
            ],

        ];

        DB::transaction(function () use ($attributes) {
            foreach ($attributes as $item) {
                $attribute = Attribute::updateOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'name' => $item['name'],
                        'type' => $item['type'],
                    ]
                );

                foreach ($item['options'] as $option) {
                    AttributeOption::updateOrCreate(
                        [
                            'attribute_id' => $attribute->id,
                            'slug' => $option['slug'],
                        ],
                        [
                            'name' => $option['name']
                        ]
                    );
                }
            }
        });
    }
}
