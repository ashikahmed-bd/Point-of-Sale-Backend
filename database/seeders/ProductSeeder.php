<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Premium Cotton T-Shirt',
                'slug' => 'premium-cotton-t-shirt',
                'sku' => 'TSH-001',
            ],
            [
                'name' => 'Classic Slim Fit Shirt',
                'slug' => 'classic-slim-fit-shirt',
                'sku' => 'SHT-001',
            ],
            [
                'name' => 'Men Premium Denim Jeans',
                'slug' => 'men-premium-denim-jeans',
                'sku' => 'JNS-001',
            ],
            [
                'name' => 'Women Casual Long Dress',
                'slug' => 'women-casual-long-dress',
                'sku' => 'DRS-001',
            ],
            [
                'name' => 'Premium Leather Casual Shoes',
                'slug' => 'premium-leather-casual-shoes',
                'sku' => 'SHO-001',
            ],
            [
                'name' => 'Premium Oversized T-Shirt',
                'slug' => 'premium-oversized-t-shirt',
                'sku' => 'TSH-002',
            ],
            [
                'name' => 'Classic Polo Shirt',
                'slug' => 'classic-polo-shirt',
                'sku' => 'POL-001',
            ],
            [
                'name' => 'Regular Fit Casual Shirt',
                'slug' => 'regular-fit-casual-shirt',
                'sku' => 'SHT-002',
            ],
            [
                'name' => 'Premium Chino Pants',
                'slug' => 'premium-chino-pants',
                'sku' => 'PNT-001',
            ],
            [
                'name' => 'Slim Fit Formal Pants',
                'slug' => 'slim-fit-formal-pants',
                'sku' => 'PNT-002',
            ],
            [
                'name' => 'Men Casual Hoodie',
                'slug' => 'men-casual-hoodie',
                'sku' => 'HOD-001',
            ],
            [
                'name' => 'Premium Cotton Panjabi',
                'slug' => 'premium-cotton-panjabi',
                'sku' => 'PNJ-001',
            ],
            [
                'name' => 'Women Premium Kurti',
                'slug' => 'women-premium-kurti',
                'sku' => 'KRT-001',
            ],
            [
                'name' => 'Women Casual Top',
                'slug' => 'women-casual-top',
                'sku' => 'TOP-001',
            ],
            [
                'name' => 'Women Wide Leg Pants',
                'slug' => 'women-wide-leg-pants',
                'sku' => 'WPN-001',
            ],
            [
                'name' => 'Women Printed Saree',
                'slug' => 'women-printed-saree',
                'sku' => 'SAR-001',
            ],
            [
                'name' => 'Kids Cotton T-Shirt',
                'slug' => 'kids-cotton-t-shirt',
                'sku' => 'KTS-001',
            ],
            [
                'name' => 'Kids Casual Jeans',
                'slug' => 'kids-casual-jeans',
                'sku' => 'KJN-001',
            ],
            [
                'name' => 'Classic Leather Belt',
                'slug' => 'classic-leather-belt',
                'sku' => 'BLT-001',
            ],
            [
                'name' => 'Premium Canvas Backpack',
                'slug' => 'premium-canvas-backpack',
                'sku' => 'BAG-001',
            ],
        ];

        foreach ($products as $product) {
            Product::query()->create(array_merge($product, [
                'code' => fake()->unique()->numerify('100#####'),
                'category_id' => fake()->randomElement(Category::query()->pluck('id')->toArray()),
                'brand_id' => fake()->randomElement(Brand::query()->pluck('id')->toArray()),
                'tax_id' => fake()->randomElement(Tax::query()->pluck('id')->toArray()),
                'unit_id' => fake()->randomElement(Unit::query()->pluck('id')->toArray()),

                'barcode' => fake()->unique()->numerify('890100#####'),
                'description' => fake()->sentence(12),

                'cost_price' => fake()->randomFloat(2, 300, 1500),
                'selling_price' => fake()->randomFloat(2, 500, 3000),
                'compare_price' => fake()->randomFloat(2, 1000, 3500),

                'min_stock' => fake()->numberBetween(2, 10),
                'max_stock' => fake()->numberBetween(20, 100),

                'track_stock' => true,
                'allow_backorder' => false,
                'has_variants' => fake()->boolean(70),

                'status' => 'active',

                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
