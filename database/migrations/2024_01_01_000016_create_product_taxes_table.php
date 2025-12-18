// database/migrations/2024_01_01_000016_create_product_taxes_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_taxes', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('tax_id')->constrained()->onDelete('cascade');
            
            $table->primary(['product_id', 'tax_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_taxes');
    }
};