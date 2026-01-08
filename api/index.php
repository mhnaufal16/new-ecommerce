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

$appKey = getenv('APP_KEY') ?: ($_ENV['APP_KEY'] ?? ($_SERVER['APP_KEY'] ?? null));

if (!$appKey) {
    echo "<h1>Configuration Error</h1>";
    echo "<p><strong>APP_KEY is not set.</strong></p>";
    echo "<p>Vercel environment variables are only available after a <strong>Redeploy</strong>. Please go to the 'Deployments' tab and redeploy the latest version.</p>";
    exit;
}

try {
    // Check for database existence if using sqlite
    if (getenv('DB_CONNECTION') === 'sqlite' || !getenv('DB_CONNECTION')) {
        $dbPath = getenv('DB_DATABASE') ?: __DIR__ . '/../database/database.sqlite';
        if (!file_exists($dbPath)) {
            // Try to create an empty one in /tmp if we can't find it
            // but Laravel usually expects it in the specified path.
            // On Vercel, the app directory is read-only.
            echo "<h1>Database Error</h1>";
            echo "<p>SQLite database file not found at: <code>$dbPath</code></p>";
            echo "<p>If you are using SQLite, make sure to commit the database file or use a remote database (MySQL/PostgreSQL).</p>";
            exit;
        }
    }

    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    // If we catch it here, it might be the "Target class [view] does not exist" 
    // which happens inside Laravel's handler. 
    // Let's try to see if there's a previous exception.
    $originalError = $e;
    while ($originalError->getPrevious()) {
        $originalError = $originalError->getPrevious();
    }

    echo "<h1>Laravel Boot Error (Captured)</h1>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    
    if ($e !== $originalError) {
        echo "<p style='color: red;'><strong>Potential Root Cause:</strong> " . $originalError->getMessage() . "</p>";
    }

    echo "<p><strong>File:</strong> " . $e->getFile() . " on line " . $e->getLine() . "</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
