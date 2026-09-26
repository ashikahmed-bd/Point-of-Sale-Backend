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
        Schema::create('variants', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('product_id')->constrained()->cascadeOnDelete();

            $table->string('name');

            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();

            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->decimal('compare_price', 15, 2)->nullable();

            $table->decimal('weight', 12, 3)->nullable();

            $table->unsignedInteger('min_stock')->default(0);

            $table->string('image')->nullable();

            $table->string('status')->default('active');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
