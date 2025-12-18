// database/migrations/2024_01_01_000006_create_product_categories_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            
            $table->primary(['product_id', 'category_id']);
            $table->index(['category_id', 'is_primary']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
};