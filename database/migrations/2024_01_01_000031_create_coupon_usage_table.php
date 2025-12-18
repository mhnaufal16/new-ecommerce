// database/migrations/2024_01_01_000031_create_coupon_usage_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('discount_amount', 12, 2);
            $table->timestamp('used_at')->useCurrent();
            
            $table->index(['coupon_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_usage');
    }
};