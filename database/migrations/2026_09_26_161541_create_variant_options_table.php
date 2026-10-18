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
        Schema::create('variant_options', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('variant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('attribute_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('attribute_option_id')->constrained()->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'variant_id',
                'attribute_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_options');
    }
};
