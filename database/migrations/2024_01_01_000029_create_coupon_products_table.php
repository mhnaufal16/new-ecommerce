// database/migrations/2024_01_01_000029_create_coupon_products_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupon_products', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->primary(['coupon_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_products');
    }
};