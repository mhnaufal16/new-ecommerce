<?php
// fix_payment_migration.php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    Schema::table('payments', function (Blueprint $table) {
        if (!Schema::hasColumn('payments', 'proof_of_payment')) {
            $table->string('proof_of_payment')->nullable()->after('payment_details');
        }
        if (!Schema::hasColumn('payments', 'verification_status')) {
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('proof_of_payment');
        }
        if (!Schema::hasColumn('payments', 'rejection_reason')) {
            $table->text('rejection_reason')->nullable()->after('verification_status');
        }
    });
    echo "Migration for proof_of_payment executed successfully!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
