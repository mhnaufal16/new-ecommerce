// database/migrations/2024_01_01_000020_create_order_addresses_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('address_type', ['billing', 'shipping']);
            $table->string('recipient_name');
            $table->string('phone', 20);
            $table->integer('province_id');
            $table->string('province_name', 100);
            $table->integer('city_id');
            $table->string('city_name', 100);
            $table->string('district', 100)->nullable();
            $table->string('subdistrict', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->text('address');
            
            $table->index(['order_id', 'address_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_addresses');
    }
};