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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->decimal('cost_price', 10, 2)->nullable()->after('price');
            $table->integer('min_stock')->default(0)->after('stock');
            $table->integer('max_stock')->nullable()->after('min_stock');
            $table->string('supplier')->nullable()->after('max_stock');
            $table->string('image')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'category_id',
                'cost_price',
                'min_stock',
                'max_stock', 
                'supplier',
                'image'
            ]);
        });
    }
};
