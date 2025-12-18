// database/migrations/2024_01_01_000019_create_orders_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_email');
            $table->string('customer_phone', 20)->nullable();
            $table->enum('status', [
                'pending', 'processing', 'on_hold', 'completed', 
                'cancelled', 'refunded', 'failed', 'shipped', 'delivered'
            ])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'refunded', 'failed'])->default('pending');
            $table->enum('shipping_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->char('currency', 3)->default('IDR');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('insurance_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->decimal('total_paid', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['order_number']);
            $table->index(['status', 'payment_status', 'shipping_status']);
            $table->index(['user_id', 'created_at']);
            $table->index(['created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};