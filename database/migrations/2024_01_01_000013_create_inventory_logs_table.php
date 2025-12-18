// database/migrations/2024_01_01_000013_create_inventory_logs_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['stock_in', 'stock_out', 'adjustment', 'reserve', 'release']);
            $table->integer('quantity_change');
            $table->integer('previous_quantity');
            $table->integer('new_quantity');
            $table->enum('reference_type', ['order', 'purchase', 'return', 'manual'])->nullable();
            $table->foreignId('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('performed_at')->useCurrent();
            
            $table->index(['inventory_id', 'performed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_logs');
    }
};