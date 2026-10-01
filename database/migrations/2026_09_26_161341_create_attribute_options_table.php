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
        Schema::create('attribute_options', function (Blueprint $table) {
            $table->ulid('id')->primary();
 
            $table->foreignUlid('attribute_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('slug');

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
            
            $table->unique(['attribute_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_options');
    }
};
