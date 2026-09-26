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
        Schema::create('return_items', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('sale_return_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('sale_item_id')->constrained()->restrictOnDelete();
            $table->foreignUlid('product_id')->constrained()->restrictOnDelete();
            $table->foreignUlid('variant_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('quantity', 15, 3);

            $table->decimal('unit_price', 15, 2);
            $table->decimal('refund_amount', 15, 2);

            $table->text('reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_items');
    }
};
