<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('purchase_return_id')
                ->constrained('purchase_returns')
                ->cascadeOnDelete();

            $table->foreignUlid('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->foreignUlid('variant_id')
                ->nullable()
                ->constrained('variants')
                ->nullOnDelete();

            $table->unsignedInteger('quantity');

            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_return_items');
    }
};
