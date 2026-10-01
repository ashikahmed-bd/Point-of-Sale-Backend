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
        Schema::create('products', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code', 10)->unique();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('sku')->unique();
            $table->string('barcode')->nullable()->unique();

            $table->text('description')->nullable();

            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->decimal('compare_price', 15, 2)->nullable();

            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('min_stock')->default(0);
            $table->unsignedInteger('max_stock')->nullable();
            $table->boolean('track_stock')->default(true);

            $table->boolean('allow_backorder')->default(false);

            $table->string('status')->default('active');

            $table->string('cover')->nullable();
            $table->json('gallery')->nullable();
            $table->string('disk')->default(config('filesystems.default'));


            $table->foreignUlid('category_id')->constrained()->restrictOnDelete();
            $table->foreignUlid('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('tax_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('unit_id')->constrained()->restrictOnDelete();

            $table->foreignUlid('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
