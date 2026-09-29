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
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('store_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('sale_id')->constrained()->restrictOnDelete();
            $table->foreignUlid('customer_id')->nullable()->constrained()->nullOnDelete();

            $table->string('return_no')->unique();

            $table->dateTime('return_date');

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('refund_amount', 15, 2)->default(0);

            $table->text('reason')->nullable();
            $table->text('note')->nullable();

            $table->string('status')->default('completed');

            $table->foreignUlid('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
    }
};
