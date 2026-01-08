<?php

// Show errors during debugging on Vercel
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vercel read-only filesystem fix: Redirect storage to /tmp
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0755, true);
    mkdir($storagePath . '/app', 0755, true);
    mkdir($storagePath . '/framework', 0755, true);
    mkdir($storagePath . '/framework/cache', 0755, true);
    mkdir($storagePath . '/framework/sessions', 0755, true);
    mkdir($storagePath . '/framework/views', 0755, true);
    mkdir($storagePath . '/logs', 0755, true);
}

putenv('APP_STORAGE=' . $storagePath);

if (!getenv('APP_KEY')) {
    echo "<h1>Configuration Error</h1>";
    echo "<p><strong>APP_KEY is not set.</strong> Please add it to your Vercel Environment Variables.</p>";
    exit;
}

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>Laravel Boot Error (Captured)</h1>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . " on line " . $e->getLine() . "</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
