<?php
// database/migrations/2025_12_19_XXXXXX_create_shipping_rates_table_fixed.php
namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('shipping_rates')) {
            Schema::create('shipping_rates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('shipping_method_id')->constrained()->onDelete('cascade');
                $table->foreignId('shipping_zone_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('price_type', ['flat', 'weight_based', 'price_based'])->default('flat');
                $table->decimal('min_weight', 8, 2)->nullable();
                $table->decimal('max_weight', 8, 2)->nullable();
                $table->decimal('min_price', 12, 2)->nullable();
                $table->decimal('max_price', 12, 2)->nullable();
                $table->decimal('flat_price', 12, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                
                // Gunakan nama index pendek
                $table->index(
                    ['shipping_method_id', 'shipping_zone_id', 'is_active'],
                    'idx_ship_rate_active'
                );
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('shipping_rates');
    }
};