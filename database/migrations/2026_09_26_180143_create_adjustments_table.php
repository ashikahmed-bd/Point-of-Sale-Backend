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
        Schema::create('adjustments', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('store_id')->constrained()->cascadeOnDelete();

            $table->string('adjustment_no')->unique();

            $table->string('type');

            $table->string('reason')->nullable();

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
        Schema::dropIfExists('adjustments');
    }
};
