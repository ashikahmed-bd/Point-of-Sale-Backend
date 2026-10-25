<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StoreSeeder::class,
            UserSeeder::class,
            AccountSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            UnitSeeder::class,
            TaxSeeder::class,
            AttributeSeeder::class,

            ProductSeeder::class,
            VariantSeeder::class,

            SupplierSeeder::class,
            PurchaseSeeder::class,
            CustomerSeeder::class,

            ExpenseCategorySeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
