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
        Schema::create('purchases', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('store_id')->constrained()->restrictOnDelete();
            $table->foreignUlid('supplier_id')->nullable()->constrained()->nullOnDelete();

            $table->string('purchase_no')->nullable();
            $table->date('purchase_date')->useCurrent();
            $table->date('expected_date')->nullable();
            $table->string('reference')->nullable();

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('due_amount', 15, 2)->default(0);

            $table->char('currency', 3)->default(config('app.currency'));

            $table->enum('status', ['draft', 'ordered', 'received', 'cancelled'])->default('draft');

            $table->text('note')->nullable();

            $table->foreignUlid('created_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['store_id', 'purchase_no'],
                'purchases_store_purchase_no_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
