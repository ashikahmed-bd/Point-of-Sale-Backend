<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $purchase = Purchase::query()->create([
            'store_id' => Store::query()->first()?->id,
            'supplier_id' => Supplier::query()->first()?->id,
            'purchase_no' => now()->format('Ymd-His'),
            'purchase_date' => now(),
            'expected_date' => now()->subDays(5),
            'reference' => 'INV-SUP-001',

            'subtotal' => 5000,
            'discount' => 200,
            'tax' => 250,
            'shipping' => 100,
            'total' => 5150,
            'paid_amount' => 3000,
            'due_amount' => 2150,
            'currency' => config('app.currency', 'BDT'),

            'status' => 'received',
            'note' => 'Initial stock purchase',
            'created_by' => User::query()->first()?->id,
        ]);

        $products = Product::query()->limit(5)->get();

        foreach ($products as $product) {
            $quantity = fake()->numberBetween(1, 20);
            $unitCost = fake()->randomFloat(2, 100, 5000);
            $discount = fake()->randomFloat(2, 0, 200);
            $tax = fake()->randomFloat(2, 0, 100);

            $subtotal = $quantity * $unitCost;
            $total = $subtotal - $discount + $tax;

            $item = $purchase->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'discount' => $discount,
                'tax' => $tax,
                'subtotal' => $subtotal,
                'total' => $total,
            ]);


            for ($i = 0; $i < $quantity; $i++) {
                $product->serials()->create([
                    'store_id' => $purchase->store_id,
                    'purchase_item_id' => $item->id,

                    'serial_number' => fake()->unique()->bothify('SN-##########'),
                    'imei' => fake()->unique()->numerify('###############'),

                    'status' => 'in_stock',
                    'condition' => 'new',
                    'purchase_date' => now()->toDateString(),
                ]);
            }
        }
    }
}
