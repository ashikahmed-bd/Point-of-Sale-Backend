<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men',
                'slug' => 'men',
                'sort_order' => 1,
                'children' => [
                    ['name' => 'T-Shirts', 'slug' => 'men-t-shirts'],
                    ['name' => 'Shirts', 'slug' => 'men-shirts'],
                    ['name' => 'Polo Shirts', 'slug' => 'men-polo-shirts'],
                    ['name' => 'Pants', 'slug' => 'men-pants'],
                    ['name' => 'Jeans', 'slug' => 'men-jeans'],
                    ['name' => 'Panjabi', 'slug' => 'men-panjabi'],
                    ['name' => 'Pajama', 'slug' => 'men-pajama'],
                    ['name' => 'Jackets', 'slug' => 'men-jackets'],
                    ['name' => 'Sweatshirts', 'slug' => 'men-sweatshirts'],
                    ['name' => 'Hoodies', 'slug' => 'men-hoodies'],
                ],
            ],

            [
                'name' => 'Women',
                'slug' => 'women',
                'sort_order' => 2,
                'children' => [
                    ['name' => 'Saree', 'slug' => 'women-saree'],
                    ['name' => 'Salwar Kameez', 'slug' => 'women-salwar-kameez'],
                    ['name' => 'Kurtis', 'slug' => 'women-kurtis'],
                    ['name' => 'Tops', 'slug' => 'women-tops'],
                    ['name' => 'Shirts', 'slug' => 'women-shirts'],
                    ['name' => 'T-Shirts', 'slug' => 'women-t-shirts'],
                    ['name' => 'Pants', 'slug' => 'women-pants'],
                    ['name' => 'Jeans', 'slug' => 'women-jeans'],
                    ['name' => 'Skirts', 'slug' => 'women-skirts'],
                    ['name' => 'Dresses', 'slug' => 'women-dresses'],
                    ['name' => 'Abaya', 'slug' => 'women-abaya'],
                    ['name' => 'Jackets', 'slug' => 'women-jackets'],
                ],
            ],

            [
                'name' => 'Kids',
                'slug' => 'kids',
                'sort_order' => 3,
                'children' => [
                    ['name' => 'Boys Clothing', 'slug' => 'boys-clothing'],
                    ['name' => 'Girls Clothing', 'slug' => 'girls-clothing'],
                    ['name' => 'Kids T-Shirts', 'slug' => 'kids-t-shirts'],
                    ['name' => 'Kids Shirts', 'slug' => 'kids-shirts'],
                    ['name' => 'Kids Pants', 'slug' => 'kids-pants'],
                    ['name' => 'Kids Dresses', 'slug' => 'kids-dresses'],
                    ['name' => 'Kids Panjabi', 'slug' => 'kids-panjabi'],
                ],
            ],

            [
                'name' => 'Footwear',
                'slug' => 'footwear',
                'sort_order' => 4,
                'children' => [
                    ['name' => 'Sneakers', 'slug' => 'sneakers'],
                    ['name' => 'Formal Shoes', 'slug' => 'formal-shoes'],
                    ['name' => 'Casual Shoes', 'slug' => 'casual-shoes'],
                    ['name' => 'Sandals', 'slug' => 'sandals'],
                    ['name' => 'Loafers', 'slug' => 'loafers'],
                    ['name' => 'Slippers', 'slug' => 'slippers'],
                    ['name' => 'Heels', 'slug' => 'heels'],
                    ['name' => 'Flats', 'slug' => 'flats'],
                ],
            ],

            [
                'name' => 'Bags',
                'slug' => 'bags',
                'sort_order' => 5,
                'children' => [
                    ['name' => 'Backpacks', 'slug' => 'backpacks'],
                    ['name' => 'Handbags', 'slug' => 'handbags'],
                    ['name' => 'Shoulder Bags', 'slug' => 'shoulder-bags'],
                    ['name' => 'Crossbody Bags', 'slug' => 'crossbody-bags'],
                    ['name' => 'Wallets', 'slug' => 'wallets'],
                    ['name' => 'Travel Bags', 'slug' => 'travel-bags'],
                ],
            ],

            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'sort_order' => 6,
                'children' => [
                    ['name' => 'Belts', 'slug' => 'belts'],
                    ['name' => 'Caps', 'slug' => 'caps'],
                    ['name' => 'Hats', 'slug' => 'hats'],
                    ['name' => 'Sunglasses', 'slug' => 'sunglasses'],
                    ['name' => 'Watches', 'slug' => 'watches'],
                    ['name' => 'Scarves', 'slug' => 'scarves'],
                    ['name' => 'Socks', 'slug' => 'socks'],
                    ['name' => 'Wallets', 'slug' => 'accessories-wallets'],
                ],
            ],

            [
                'name' => 'Innerwear',
                'slug' => 'innerwear',
                'sort_order' => 7,
                'children' => [
                    ['name' => 'Men Innerwear', 'slug' => 'men-innerwear'],
                    ['name' => 'Women Innerwear', 'slug' => 'women-innerwear'],
                    ['name' => 'Undergarments', 'slug' => 'undergarments'],
                ],
            ],

            [
                'name' => 'Sportswear',
                'slug' => 'sportswear',
                'sort_order' => 8,
                'children' => [
                    ['name' => 'Jerseys', 'slug' => 'jerseys'],
                    ['name' => 'Sports T-Shirts', 'slug' => 'sports-t-shirts'],
                    ['name' => 'Track Pants', 'slug' => 'track-pants'],
                    ['name' => 'Sports Shorts', 'slug' => 'sports-shorts'],
                    ['name' => 'Sports Shoes', 'slug' => 'sports-shoes'],
                ],
            ],

            [
                'name' => 'Winter Collection',
                'slug' => 'winter-collection',
                'sort_order' => 9,
                'children' => [
                    ['name' => 'Sweaters', 'slug' => 'sweaters'],
                    ['name' => 'Hoodies', 'slug' => 'winter-hoodies'],
                    ['name' => 'Jackets', 'slug' => 'winter-jackets'],
                    ['name' => 'Cardigans', 'slug' => 'cardigans'],
                    ['name' => 'Winter Shawls', 'slug' => 'winter-shawls'],
                ],
            ],

            [
                'name' => 'Traditional Wear',
                'slug' => 'traditional-wear',
                'sort_order' => 10,
                'children' => [
                    ['name' => 'Panjabi', 'slug' => 'traditional-panjabi'],
                    ['name' => 'Pajama', 'slug' => 'traditional-pajama'],
                    ['name' => 'Saree', 'slug' => 'traditional-saree'],
                    ['name' => 'Lehenga', 'slug' => 'lehenga'],
                    ['name' => 'Salwar Kameez', 'slug' => 'traditional-salwar-kameez'],
                    ['name' => 'Kurta', 'slug' => 'kurta'],
                ],
            ],
        ];

        foreach ($categories as $category) {
            $parent = Category::create([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'sort_order' => $category['sort_order'],
                'is_active' => true,
            ]);

            foreach ($category['children'] as $sortOrder => $child) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name' => $child['name'],
                    'slug' => $child['slug'],
                    'sort_order' => $sortOrder + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
