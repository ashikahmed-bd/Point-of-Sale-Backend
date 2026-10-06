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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('cart_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUlid('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUlid('variant_id')
                ->nullable()
                ->constrained('variants')
                ->nullOnDelete();

            $table->string('name');
            $table->string('sku')->nullable();

            $table->decimal('price', 15, 2)->default(0);
            $table->unsignedInteger('quantity')->default(1);

            $table->decimal('tax_rate', 10, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->timestamps();

            $table->unique([
                'cart_id',
                'product_id',
                'variant_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
