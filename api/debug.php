echo "<h1>Vercel Deployment Debug</h1>";

$vars = ['APP_KEY', 'LOG_CHANNEL', 'CACHE_STORE', 'SESSION_DRIVER', 'APP_STORAGE'];
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Variable</th><th>Status</th><th>Source</th></tr>";

foreach ($vars as $var) {
    $val = getenv($var);
    $source = 'getenv';
    
    if (!$val && isset($_ENV[$var])) {
        $val = $_ENV[$var];
        $source = '$_ENV';
    }
    
    if (!$val && isset($_SERVER[$var])) {
        $val = $_SERVER[$var];
        $source = '$_SERVER';
    }

    echo "<tr>";
    echo "<td>$var</td>";
    echo "<td>" . ($val ? "<span style='color:green'>Found</span>" : "<span style='color:red'>Not Found</span>") . "</td>";
    echo "<td>" . ($val ? $source : "N/A") . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h2>PHP Info Below</h2>";
phpinfo();
