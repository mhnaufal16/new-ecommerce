<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('proof_of_payment')->nullable()->after('payment_details');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('proof_of_payment');
            $table->text('rejection_reason')->nullable()->after('verification_status');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['proof_of_payment', 'verification_status', 'rejection_reason']);
        });
    }
};
