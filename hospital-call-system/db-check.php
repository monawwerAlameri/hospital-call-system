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
echo "Server time:        " . date('Y-m-d H:i:s') . "\n\n";

echo "DB configuration:\n";
echo "  DB_HOST = " . DB_HOST . "\n";
echo "  DB_PORT = " . DB_PORT . "\n";
echo "  DB_USER = " . DB_USER . "\n";
echo "  DB_NAME = " . DB_NAME . "\n";
echo "  DB_PASS = " . (DB_PASS ? '***(' . strlen(DB_PASS) . ' chars)' : '(empty)') . "\n\n";

echo "Connecting to MySQL server...\n";
$conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
if ($conn->connect_error) {
    echo "  ❌ FAIL: " . $conn->connect_error . "\n";
    echo "\nTroubleshooting:\n";
    echo "  - Verify the DB_HOST / DB_PORT point to your MySQL provider.\n";
    echo "  - Verify DB_USER and DB_PASS are correct (check the env vars on Render).\n";
    echo "  - Verify your MySQL provider allows external connections from Render's IPs.\n";
    exit(1);
}
echo "  ✓ Connected to MySQL server.\n\n";

echo "Creating / selecting database '" . DB_NAME . "'...\n";
@$conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
if (!$conn->select_db(DB_NAME)) {
    echo "  ❌ FAIL: Database not found and cannot be created.\n";
    echo "  Please create the database '" . DB_NAME . "' manually in your hosting panel.\n";
    exit(1);
}
echo "  ✓ Database selected.\n\n";

// Verify schema by counting tables
$res = $conn->query("SHOW TABLES");
$tableCount = $res ? $res->num_rows : 0;
echo "Tables in $DB_NAME: $tableCount\n";
if ($tableCount < 16) {
    echo "  ⚠️  Expected 16+ tables. Triggering api/config.php to auto-create them...\n";
    // getDB() will create tables and seed data on first call
    $db = getDB();
    $res2 = $db->query("SHOW TABLES");
    $tableCount2 = $res2 ? $res2->num_rows : 0;
    echo "  After init: $tableCount2 tables.\n";
    // Print table list
    if ($res2) {
        echo "\n  Tables:\n";
        while ($row = $res2->fetch_array()) {
            echo "    - " . $row[0] . "\n";
        }
    }
}

// Count rows in emergency_codes
$ecRes = $conn->query("SELECT COUNT(*) AS c FROM emergency_codes");
$ecCount = $ecRes ? (int)$ecRes->fetch_assoc()['c'] : 0;
echo "\nEmergency codes seeded: $ecCount\n";

$vhRes = $conn->query("SELECT COUNT(*) AS c FROM visit_hours_config");
$vhCount = $vhRes ? (int)$vhRes->fetch_assoc()['c'] : 0;
echo "Visit hours config rows: $vhCount\n\n";

echo "=== Diagnostic complete ===\n";
if ($tableCount2 ?? $tableCount >= 16 && $ecCount >= 7 && $vhCount >= 1) {
    echo "✅ All good! Your deployment is ready.\n";
    echo "Open: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/\n";
} else {
    echo "⚠️  Some tables/seeds are missing — re-load this page once more to retry auto-init.\n";
}
