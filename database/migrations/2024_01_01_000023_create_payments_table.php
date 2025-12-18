// database/migrations/2024_01_01_000023_create_payments_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_method', 100);
            $table->string('payment_gateway', 100)->nullable();
            $table->string('transaction_id', 255)->unique()->nullable();
            $table->enum('transaction_status', [
                'pending', 'capture', 'settlement', 'deny', 'cancel', 'expire', 'failure'
            ])->default('pending');
            $table->decimal('amount', 12, 2);
            $table->decimal('fee', 12, 2)->default(0);
            $table->char('currency', 3)->default('IDR');
            $table->json('payment_details')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index(['order_id']);
            $table->index(['transaction_status']);
            $table->index(['transaction_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};