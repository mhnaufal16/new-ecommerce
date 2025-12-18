// database/migrations/2024_01_01_000002_create_user_addresses_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label', 50);
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
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'is_primary']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
};