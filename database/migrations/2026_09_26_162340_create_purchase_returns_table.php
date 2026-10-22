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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('purchase_id')
                ->constrained('purchases')
                ->restrictOnDelete();

            $table->foreignUlid('supplier_id')
                ->constrained('suppliers')
                ->restrictOnDelete();

            $table->string('return_no', 100)->unique();

            $table->date('return_date');

            $table->string('reference')->nullable();

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('shipping', 15, 2)->default(0);
            $table->decimal('charge', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->char('currency', 3)->default(config('app.currency', 'BDT'));

            $table->enum('status', [
                'draft',
                'completed',
                'cancelled',
            ])->default('draft');

            $table->text('note')->nullable();

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
        Schema::dropIfExists('purchase_returns');
    }
};
