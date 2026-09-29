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
        Schema::create('expenses', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('store_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('category_id')->constrained()->restrictOnDelete();

            $table->string('expense_no')->unique();

            $table->decimal('amount', 15, 2);

            $table->dateTime('expense_date');

            $table->string('payment_method');

            $table->text('description')->nullable();

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
        Schema::dropIfExists('expenses');
    }
};
