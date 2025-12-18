// database/migrations/2024_01_01_000022_create_order_shipments_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('shipping_method', 100);
            $table->string('courier_name', 100);
            $table->string('courier_service', 100);
            $table->string('tracking_number', 255)->nullable();
            $table->decimal('shipping_cost', 12, 2);
            $table->decimal('insurance_cost', 12, 2)->default(0);
            $table->date('estimated_delivery')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['tracking_number']);
            $table->index(['order_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_shipments');
    }
};