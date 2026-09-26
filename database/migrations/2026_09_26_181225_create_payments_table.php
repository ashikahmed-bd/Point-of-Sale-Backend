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
        Schema::create('payments', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('store_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUlid('account_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Sale, Purchase, Expense, Return, etc.
            $table->nullableMorphs('payable');

            $table->string('type'); // payment, refund

            $table->decimal('amount', 15, 2);

            $table->string('method'); // cash, bank, card, mobile_banking

            $table->string('reference')->nullable();

            $table->text('note')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->foreignUlid('created_by')
                ->nullable()
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
        Schema::dropIfExists('payments');
    }
};
