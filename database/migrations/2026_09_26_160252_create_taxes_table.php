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
        Schema::create('taxes', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('name');
            $table->string('code')->nullable()->unique();

            $table->decimal('rate', 8, 4)->default(0);
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');

            $table->enum('calculation', ['exclusive', 'inclusive'])->default('exclusive');

            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
