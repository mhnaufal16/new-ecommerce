// database/migrations/2024_01_01_000017_create_carts_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id', 255)->nullable();
            $table->string('coupon_code', 50)->nullable();
            $table->char('currency', 3)->default('IDR');
            $table->timestamps();
            
            $table->index(['session_id']);
            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};