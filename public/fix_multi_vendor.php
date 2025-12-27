<?php
// public/fix_multi_vendor.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;

// We manually bootstrap the application if needed, but in web context it should be fine.
// For Laravel 11+, we handle request to bootstrap.
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<pre>";
try {
    echo "Checking products table...\n";
    if (!Schema::hasColumn('products', 'vendor_id')) {
        echo "Adding vendor_id column...\n";
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->after('slug')->constrained('users')->onDelete('set null');
        });
        echo "Column added successfully.\n";
    } else {
        echo "Column vendor_id already exists.\n";
    }

    echo "Associating products with vendor...\n";
    $vendor = User::where('type', 'vendor')->first();
    if ($vendor) {
        $updatedCount = Product::whereNull('vendor_id')->update(['vendor_id' => $vendor->id]);
        echo "Successfully updated $updatedCount products with vendor ID: " . $vendor->id . "\n";
    } else {
        echo "No vendor found to associate products with.\n";
    }
    
    // Manually mark migration as done if file exists
    $migrationName = '2025_12_27_114500_add_vendor_ref_to_products_table';
    if (!DB::table('migrations')->where('migration', $migrationName)->exists()) {
        DB::table('migrations')->insert(['migration' => $migrationName, 'batch' => 999]);
        echo "Marked migration as completed in database.\n";
    }
    echo "\nDONE.";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
echo "</pre>";
