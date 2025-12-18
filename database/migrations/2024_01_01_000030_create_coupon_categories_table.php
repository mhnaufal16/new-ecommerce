// database/migrations/2024_01_01_000030_create_coupon_categories_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupon_categories', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            $table->primary(['coupon_id', 'category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_categories');
    }
};