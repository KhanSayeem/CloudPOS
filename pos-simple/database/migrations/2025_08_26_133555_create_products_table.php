<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Core fields
            $table->string('sku', 64)->unique();
            $table->string('barcode', 64)->nullable()->index(); // optional
            $table->string('name');
            $table->text('description')->nullable();

            // Money & stock
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('stock')->default(0);

            // Flags
            $table->boolean('status')->default(true); // true = active/for sale

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
