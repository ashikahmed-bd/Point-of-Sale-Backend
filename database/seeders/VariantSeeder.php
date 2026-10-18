<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\Variant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $color = Attribute::with('options')
            ->where('slug', 'color')
            ->firstOrFail();

        $size = Attribute::with('options')
            ->where('slug', 'size')
            ->firstOrFail();

        // Select 4 colors and 4 sizes
        $colors = $color->options->take(4);
        $sizes = $size->options->take(4);

        if ($colors->count() < 4 || $sizes->count() < 4) {
            throw new \RuntimeException(
                'At least 4 color options and 4 size options are required.'
            );
        }

        $product = Product::create([
            'name' => 'Nike Dri-FIT Sports T-Shirt',
            'slug' => Str::slug('Nike Dri-FIT Sports T-Shirt'),
            'sku' => 'NK-TSH-001',
            'code' => '10039604',

            'category_id' => Category::inRandomOrder()->value('id'),
            'brand_id' => Brand::inRandomOrder()->value('id'),
            'tax_id' => Tax::inRandomOrder()->value('id'),
            'unit_id' => Unit::inRandomOrder()->value('id'),

            'barcode' => fake()->unique()->numerify('890100#####'),
            'description' => fake()->sentence(12),

            'cost_price' => 650,
            'price' => 1200,
            'base_price' => 1200,

            'stock' => 50,
            'min_stock' => 5,
            'max_stock' => 500,

            'track_stock' => true,
            'track_serial' => true,

            'allow_backorder' => false,
            'has_variants' => true,

            'status' => 'active',
        ]);

        // Save available options for this product
        foreach ([[$color, $colors], [$size, $sizes],] as [$attribute, $options]) {
            foreach ($options as $index => $option) {
                $product->options()->create([
                    'attribute_id' => $attribute->id,
                    'attribute_option_id' => $option->id,
                    'sort_order' => $index,
                ]);
            }
        }

        // Create 16 variants: 4 colors × 4 sizes
        foreach ($colors as $colorOption) {
            foreach ($sizes as $sizeOption) {
                $colorName = $colorOption->name;
                $sizeName = $sizeOption->name;

                $variant = Variant::create([
                    'product_id' => $product->id,
                    'name' => "{$colorName} / {$sizeName}",

                    'sku' => 'NK-TSH-' .
                        strtoupper(Str::slug($colorName)) . '-' .
                        strtoupper(Str::slug($sizeName)),
                    'barcode' => fake()->unique()->numerify('890100#####'),

                    'options' => [
                        'color' => $colorName,
                        'size' => $sizeName,
                    ],

                    'cost_price' => 650,
                    'price' => 1200,
                    'base_price' => 1200,

                    'stock' => 10,

                    'is_default' => $colorOption->is($colors->first())
                        && $size->is($sizes->first()),

                    'active' => true,
                ]);

                // Save selected attributes for this variant
                $variant->options()->createMany([
                    [
                        'attribute_id' => $color->id,
                        'attribute_option_id' => $colorOption->id,
                    ],
                    [
                        'attribute_id' => $size->id,
                        'attribute_option_id' => $sizeOption->id,
                    ],
                ]);
            }
        }

        $store = Store::inRandomOrder()->value('id');

        $product->serials()->createMany([
            [
                'store_id' => $store,
                'variant_id' => $variants[0]->id ?? null,
                'serial_number' => 'NK-TSH-00001',
                'imei' => fake()->unique()->numerify('###############'),
                'status' => 'in_stock',
                'condition' => 'new',
            ],
            [
                'store_id' => $store,
                'variant_id' => $variants[0]->id ?? null,
                'serial_number' => 'NK-TSH-00002',
                'imei' => fake()->unique()->numerify('###############'),
                'status' => 'in_stock',
                'condition' => 'new',
            ],
            [
                'store_id' => $store,
                'variant_id' => $variants[1]->id ?? null,
                'serial_number' => 'NK-TSH-00003',
                'imei' => fake()->unique()->numerify('###############'),
                'status' => 'in_stock',
                'condition' => 'new',
            ],
            [
                'store_id' => $store,
                'variant_id' => $variants[1]->id ?? null,
                'serial_number' => 'NK-TSH-00004',
                'imei' => fake()->unique()->numerify('###############'),
                'status' => 'in_stock',
                'condition' => 'new',
            ],
        ]);
    }
}
