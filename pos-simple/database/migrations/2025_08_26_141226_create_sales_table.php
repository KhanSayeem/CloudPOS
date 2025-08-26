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
        Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // cashier/admin who sold
        $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
        $table->decimal('subtotal', 10, 2);
        $table->decimal('discount_total', 10, 2)->default(0);
        $table->decimal('tax_total', 10, 2)->default(0);
        $table->decimal('total', 10, 2);
        $table->string('status')->default('completed'); // completed|held
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
