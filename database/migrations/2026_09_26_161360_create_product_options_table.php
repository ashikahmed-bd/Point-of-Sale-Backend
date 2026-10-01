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
        Schema::create('product_options', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignUlid('attribute_id')
                ->constrained('attributes')
                ->cascadeOnDelete();

            $table->foreignUlid('attribute_option_id')
                ->constrained('attribute_options')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'product_id',
                'attribute_id',
                'attribute_option_id',
            ], 'product_option_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};
