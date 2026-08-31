<?php
/**
 * DB connection diagnostic endpoint (v3.1.2 — comprehensive).
 *
 * Usage:  https://your-app.onrender.com/db-check.php
 */
require_once __DIR__ . '/api/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "=== Hospital Call System — DB Diagnostic (v3.1.2) ===\n\n";

echo "PHP version:         " . PHP_VERSION . "\n";
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
echo "  DB_SSL  = " . (DB_SSL ? 'YES' : 'NO') . (DB_SSL && strpos(DB_HOST, '.aivencloud.com') !== false ? ' (auto-detected Aiven)' : '') . "\n";
echo "  CA cert = " . (is_file(DB_CA_CERT) ? 'EXISTS (' . filesize(DB_CA_CERT) . ' bytes)' : 'MISSING') . "\n";
echo "  CA path = " . DB_CA_CERT . "\n\n";

// Test 1: DNS
echo "Test 1: DNS resolution for '" . DB_HOST . "':\n";
$ip = @gethostbyname(DB_HOST);
if ($ip === DB_HOST) {
    echo "  ❌ Could not resolve hostname (DNS may be failing)\n";
} else {
    echo "  ✓ Resolved to IP: " . $ip . "\n";
}
echo "\n";

// Test 2: TCP port
echo "Test 2: TCP connectivity (port " . DB_PORT . "):\n";
$fp = @fsockopen(DB_HOST, DB_PORT, $errno, $errstr, 8);
if ($fp) {
    echo "  ✓ TCP connection succeeded\n";
    fclose($fp);
} else {
    echo "  ❌ TCP connection failed: ($errno) $errstr\n";
    if ($errno === 111) echo "     → 'Connection refused' means no service on this port OR firewall blocking.\n";
    if ($errno === 110) echo "     → 'Connection timed out' means firewall or wrong host.\n";
}
echo "\n";

// Test 3: Try all 4 connection methods
echo "Test 3: MySQL connection attempts:\n";

$methods = [];

// Method 1: SSL + CA + DON'T_VERIFY
if (DB_SSL && is_file(DB_CA_CERT)) {
    $c = @new mysqli();
    $c->ssl_set(null, null, DB_CA_CERT, null, null);
    @$c->real_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
    $ok = !$c->connect_error;
    $methods[] = ['SSL+CA (no-verify)', $ok, $ok ? '' : $c->connect_error];
    if ($ok) $c->close();
}

// Method 2: SSL + CA + VERIFY
if (DB_SSL && is_file(DB_CA_CERT) && !$methods[count($methods)-1][1]) {
    $c = @new mysqli();
    $c->ssl_set(null, null, DB_CA_CERT, null, null);
    @$c->real_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT, null, MYSQLI_CLIENT_SSL);
    $ok = !$c->connect_error;
    $methods[] = ['SSL+CA (verify)', $ok, $ok ? '' : $c->connect_error];
    if ($ok) $c->close();
}

// Method 3: SSL without CA
if (DB_SSL && (count($methods) === 0 || !$methods[count($methods)-1][1])) {
    $c = @new mysqli();
    @$c->real_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
    $ok = !$c->connect_error;
    $methods[] = ['SSL (no CA)', $ok, $ok ? '' : $c->connect_error];
    if ($ok) $c->close();
}

// Method 4: Plain TCP
if (count($methods) === 0 || !$methods[count($methods)-1][1]) {
    $c = @new mysqli();
    @$c->real_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
    $ok = !$c->connect_error;
    $methods[] = ['Plain TCP', $ok, $ok ? '' : $c->connect_error];
    if ($ok) $c->close();
}

foreach ($methods as $m) {
    echo "  " . ($m[1] ? '✓' : '✗') . " " . $m[0] . ": " . ($m[1] ? 'SUCCESS' : $m[2]) . "\n";
}
echo "\n";

// Test 4: Schema + seeds
$anySuccess = false;
foreach ($methods as $m) { if ($m[1]) { $anySuccess = true; break; } }

if (!$anySuccess) {
    echo "=== Diagnostic complete (FAILED) ===\n\n";
    echo "❌ All connection methods failed.\n\n";
    echo "Possible causes & fixes:\n";
    echo "  1. DB_PASS not set in Render Environment Variables.\n";
    echo "     → Go to Render → Web Service → Environment → Add DB_PASS\n";
    echo "     → Value: your Aiven password (AVNS_... from Aiven console)\n\n";
    echo "  2. DB_SSL not set (or set to 0).\n";
    echo "     → Add DB_SSL=1 in Render Environment\n";
    echo "     → (Or rely on auto-detection for *.aivencloud.com hosts)\n\n";
    echo "  3. Aiven service is paused (free tier pauses after inactivity).\n";
    echo "     → Login to aiven.io → Restart the service\n\n";
    echo "  4. Wrong DB_PORT (Aiven uses non-standard ports like 23366).\n";
    echo "     → Verify the port in Aiven console matches DB_PORT env var.\n\n";
    echo "  5. Aiven IP allowlist blocks Render.\n";
    echo "     → Aiven Console → Service → Allow IP (or 0.0.0.0/0 for all)\n";
    exit(1);
}

// Trigger schema auto-creation
echo "Test 4: Schema creation + seed data:\n";
$db = getDB();
$res = $db->query("SHOW TABLES");
$tableCount = $res ? $res->num_rows : 0;
echo "  Tables in DB: $tableCount\n\n";

if ($tableCount > 0 && $res) {
    while ($row = $res->fetch_array()) {
        $r2 = $db->query("SELECT COUNT(*) AS c FROM `" . $row[0] . "`");
        $c = $r2 ? (int)$r2->fetch_assoc()['c'] : 0;
        printf("    %-32s %d rows\n", $row[0], $c);
    }
}

echo "\n=== Diagnostic complete ===\n";
if ($tableCount >= 16) {
    echo "✅ All good! Your deployment is ready.\n";
    echo "Open: " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . "://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/\n";
} else {
    echo "⚠️  Some tables are missing. Re-load this page once more.\n";
}
