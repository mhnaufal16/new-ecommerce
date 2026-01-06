// database/migrations/2024_01_01_000005_create_products_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 100)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('specifications')->nullable();
            $table->enum('type', ['simple', 'configurable', 'digital', 'bundle'])->default('simple');
            $table->enum('status', ['draft', 'active', 'inactive', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_virtual')->default(false);
            $table->foreignId('tax_class_id')->nullable();
            $table->integer('minimum_order_qty')->default(1);
            $table->integer('maximum_order_qty')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->timestamps();
            
            $table->index(['slug']);
            $table->index(['status']);
            $table->index(['sku']);
            // $table->fullText(['name', 'short_description', 'description']); // Disabled for Railway compatibility
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};