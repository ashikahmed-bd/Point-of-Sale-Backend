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

            // Example:
            // {"color":"Black","size":"L","type":"Home"}
            $table->json('options')->nullable();

            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('base_price', 15, 2)->nullable();

            $table->unsignedInteger('stock')->default(0);

            $table->string('image')->nullable();
            $table->string('disk')->default(config('filesystems.default'));

            $table->boolean('is_default')->default(false);
            $table->boolean('active')->default(true);

            $table->timestamps();
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
