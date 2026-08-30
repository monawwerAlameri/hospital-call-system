<?php
/**
 * DB connection diagnostic endpoint.
 * Visit this page after deployment to verify that the DB credentials
 * are correctly configured and the database schema has been created.
 *
 * Usage:  https://your-app.onrender.com/db-check.php
 */
require_once __DIR__ . '/api/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== Hospital Call System — DB Diagnostic ===\n\n";

echo "PHP version:        " . PHP_VERSION . "\n";
echo "Server time:         " . date('Y-m-d H:i:s') . "\n";
echo "Has mysqli:         " . (extension_loaded('mysqli') ? 'YES' : 'NO') . "\n";
echo "Has openssl:        " . (extension_loaded('openssl') ? 'YES' : 'NO') . "\n";
echo "Has curl:           " . (extension_loaded('curl') ? 'YES' : 'NO') . "\n\n";

echo "DB configuration:\n";
echo "  DB_HOST = " . DB_HOST . "\n";
echo "  DB_PORT = " . DB_PORT . "\n";
echo "  DB_USER = " . DB_USER . "\n";
echo "  DB_NAME = " . DB_NAME . "\n";
echo "  DB_PASS = " . (DB_PASS ? '***(' . strlen(DB_PASS) . ' chars)' : '(empty)') . "\n";
echo "  DB_SSL  = " . (DB_SSL ? 'YES' : 'NO') . "\n";
echo "  CA cert = " . (is_file(DB_CA_CERT) ? 'EXISTS (' . filesize(DB_CA_CERT) . ' bytes)' : 'MISSING') . "\n";
echo "  CA path = " . DB_CA_CERT . "\n\n";

// Test DNS resolution
echo "DNS resolution test for '" . DB_HOST . "':\n";
$ip = @gethostbyname(DB_HOST);
if ($ip === DB_HOST) {
    echo "  ⚠️  Could not resolve hostname (DNS may be failing)\n";
} else {
    echo "  ✓ Resolved to IP: " . $ip . "\n";
}
echo "\n";

// Test TCP connectivity
echo "TCP connectivity test (port " . DB_PORT . "):\n";
$fp = @fsockopen(DB_HOST, DB_PORT, $errno, $errstr, 5);
if ($fp) {
    echo "  ✓ TCP connection succeeded\n";
    fclose($fp);
} else {
    echo "  ❌ TCP connection failed: ($errno) $errstr\n";
    echo "  This usually means the host is unreachable from Render's network.\n";
}
echo "\n";

echo "Connecting to MySQL server...\n";
$conn = @new mysqli();
if (DB_SSL && is_file(DB_CA_CERT)) {
    $conn->ssl_set(null, null, DB_CA_CERT, null, null);
    @$conn->real_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
} else {
    @$conn->real_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
}

if ($conn->connect_error) {
    echo "  ❌ FAIL: " . $conn->connect_error . "\n";
    echo "\nTroubleshooting:\n";
    echo "  - Verify the DB_HOST / DB_PORT point to your MySQL provider.\n";
    echo "  - Verify DB_USER and DB_PASS are correct.\n";
    echo "  - Verify DB_SSL=1 if your provider requires SSL (Aiven, PlanetScale, etc.).\n";
    echo "  - Verify your MySQL provider allows external connections from Render's IPs.\n";
    echo "  - On Aiven, ensure you downloaded the CA certificate and put it at api/ca.pem.\n";
    echo "  - 'Connection refused' usually means firewall or wrong port.\n";
    echo "  - 'Connection timed out' usually means wrong host or DNS issue.\n";
    exit(1);
}
echo "  ✓ Connected to MySQL server.\n\n";

echo "Creating / selecting database '" . DB_NAME . "'...\n";
@$conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
if (!$conn->select_db(DB_NAME)) {
    echo "  ⚠️  Database not found and cannot be created.\n";
    echo "  On Aiven, you must use the default database ('defaultdb').\n";
    echo "  Trying 'defaultdb'...\n";
    if ($conn->select_db('defaultdb')) {
        echo "  ✓ Connected to 'defaultdb'. Please set DB_NAME=defaultdb in your env vars.\n";
    } else {
        echo "  ❌ FAIL: No usable database.\n";
        exit(1);
    }
} else {
    echo "  ✓ Database selected.\n\n";
}

// Trigger schema auto-creation by calling getDB()
echo "Triggering api/config.php (auto-creates tables + seeds)...\n";
$db = getDB();

$res = $db->query("SHOW TABLES");
$tableCount = $res ? $res->num_rows : 0;
echo "Tables in DB: $tableCount\n";
if ($tableCount > 0) {
    if ($res) {
        while ($row = $res->fetch_array()) {
            $r2 = $db->query("SELECT COUNT(*) AS c FROM `" . $row[0] . "`");
            $c = $r2 ? (int)$r2->fetch_assoc()['c'] : 0;
            printf("    - %-30s %d rows\n", $row[0], $c);
        }
    }
}

echo "\n=== Diagnostic complete ===\n";
if ($tableCount >= 16) {
    echo "✅ All good! Your deployment is ready.\n";
    echo "Open: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/\n";
} else {
    echo "⚠️  Some tables are missing. Re-load this page once more.\n";
}
