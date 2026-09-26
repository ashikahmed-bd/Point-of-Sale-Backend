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
        Schema::create('transactions', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('account_id')->constrained()->cascadeOnDelete();

            $table->enum('type', [
                'income',
                'expense',
                'payment',
                'refund',
                'transfer',
                'adjustment',
            ])->default('payment');

            $table->decimal('amount', 15, 2);

            $table->decimal('balance_before', 15, 2);
            $table->decimal('balance_after', 15, 2);

            $table->nullableUlidMorphs('reference');

            $table->text('description')->nullable();

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
        Schema::dropIfExists('transactions');
    }
};
