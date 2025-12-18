<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->string('sku', 100);
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'pre_order', 'back_order'])->default('in_stock');
            $table->foreignId('warehouse_id')->nullable();
            $table->string('location', 100)->nullable();
            $table->timestamps();
            
            $table->unique(['sku']);
            $table->index(['stock_status']);
            $table->index(['product_id', 'variant_id']);
        
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};