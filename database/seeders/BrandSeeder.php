<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Aarong',
                'slug' => 'aarong',
                'description' => 'Bangladeshi lifestyle and fashion brand.',
            ],
            [
                'name' => 'Yellow',
                'slug' => 'yellow',
                'description' => 'Bangladeshi fashion brand offering contemporary clothing and lifestyle products.',
            ],
            [
                'name' => 'Ecstasy',
                'slug' => 'ecstasy',
                'description' => 'Bangladeshi fashion brand offering modern apparel and accessories.',
            ],
            [
                'name' => 'Cats Eye',
                'slug' => 'cats-eye',
                'description' => 'Bangladeshi menswear fashion brand.',
            ],
            [
                'name' => 'Richman',
                'slug' => 'richman',
                'description' => 'Bangladeshi menswear and lifestyle brand.',
            ],
            [
                'name' => 'Dorjibari',
                'slug' => 'dorjibari',
                'description' => 'Bangladeshi menswear brand.',
            ],
            [
                'name' => 'Le Reve',
                'slug' => 'le-reve',
                'description' => 'Bangladeshi fashion brand for men and women.',
            ],
            [
                'name' => 'Sailor',
                'slug' => 'sailor',
                'description' => 'Bangladeshi lifestyle and fashion brand.',
            ],
            [
                'name' => 'Lubnan',
                'slug' => 'lubnan',
                'description' => 'Bangladeshi menswear fashion brand.',
            ],
            [
                'name' => 'Artisan',
                'slug' => 'artisan',
                'description' => 'Bangladeshi fashion and lifestyle brand.',
            ],
            [
                'name' => 'Easy',
                'slug' => 'easy',
                'description' => 'Bangladeshi casual fashion brand.',
            ],
            [
                'name' => 'Fabrilife',
                'slug' => 'fabrilife',
                'description' => 'Bangladeshi online fashion and lifestyle brand.',
            ],
            [
                'name' => 'Ecstasy Kids',
                'slug' => 'ecstasy-kids',
                'description' => 'Fashion brand focused on kids clothing.',
            ],
            [
                'name' => 'H&M',
                'slug' => 'h-and-m',
                'description' => 'International fashion brand offering clothing, accessories and lifestyle products.',
            ],
            [
                'name' => 'Zara',
                'slug' => 'zara',
                'description' => 'International fashion brand offering contemporary apparel and accessories.',
            ],
            [
                'name' => 'Uniqlo',
                'slug' => 'uniqlo',
                'description' => 'Japanese casual wear and lifestyle brand.',
            ],
            [
                'name' => 'Levi’s',
                'slug' => 'levis',
                'description' => 'International denim and apparel brand.',
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'International sportswear and lifestyle brand.',
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'International sportswear and footwear brand.',
            ],
            [
                'name' => 'Puma',
                'slug' => 'puma',
                'description' => 'International sportswear and footwear brand.',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
                'description' => $brand['description'],
                'is_active' => true,
            ]);
        }
    }
}
