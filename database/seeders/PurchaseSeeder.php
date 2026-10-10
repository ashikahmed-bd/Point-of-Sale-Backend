<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $store = Store::query()->first();
        $supplier = Supplier::query()->first();
        $user = User::query()->first();
        $products = Product::query()->take(3)->get();

        if (! $store || $products->isEmpty()) {
            return;
        }

        $purchases = [
            [
                'supplier_id' => $supplier?->id,
                'date' => now()->subDays(5),
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
            ],
            [
                'supplier_id' => $supplier?->id,
                'date' => now()->subDays(2),
                'subtotal' => 3000,
                'discount' => 100,
                'tax' => 150,
                'shipping' => 50,
                'total' => 3100,
                'paid_amount' => 3100,
                'due_amount' => 0,
                'currency' => config('app.currency', 'BDT'),
                'status' => 'ordered',
                'note' => 'Regular stock purchase',
            ],
            [
                'supplier_id' => $supplier?->id,
                'date' => now(),
                'subtotal' => 2000,
                'discount' => 0,
                'tax' => 100,
                'shipping' => 0,
                'total' => 2100,
                'paid_amount' => 0,
                'due_amount' => 2100,
                'currency' => config('app.currency', 'BDT'),
                'status' => 'draft',
                'note' => 'Draft purchase',
            ],
        ];

        foreach ($purchases as $purchase) {
            DB::transaction(function () use ($purchase,  $store,  $user, $products) {
                $purchase = Purchase::query()->create(
                    array_merge($purchase, [
                        'store_id' => $store->id,
                        'purchase_no' => now()->format('Ymd-His'),
                        'created_by' => $user?->id,
                    ])
                );

                foreach ($products as $product) {
                    $quantity = 10;
                    $unitCost = 500;
                    $discount = 0;
                    $tax = 0;

                    $purchase->items()->create([
                        'product_id' => $product->id,
                        'variant_id' => null,
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'quantity' => $quantity,
                        'unit_cost' => $unitCost,
                        'discount' => $discount,
                        'tax' => $tax,
                        'total' => ($quantity * $unitCost) - $discount + $tax,
                    ]);
                }
            });
        }
    }
}
