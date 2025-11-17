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
        Schema::create('products', function (Blueprint $table) {
        $table->id(); // Auto-incrementing Primary Key
        $table->string('name');
        $table->text('description')->nullable(); // nullable() means it can be empty
        $table->decimal('price', 8, 2); // 8 total digits, 2 after the decimal
        $table->integer('stock')->default(0); // Default to 0 if not specified
        $table->timestamps(); // Creates `created_at` and `updated_at` columns

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
