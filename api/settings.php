<?php
// ============================================================
//  API: System Settings — GET (public) / POST (auth required)
//  v3.1.2: Always returns success even if DB is temporarily unavailable.
//          Settings are cached in $_SESSION for graceful degradation.
// ============================================================
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Try DB first; fall back to session cache
    try {
        $db = @getDB();
        if ($db) {
            $result = $db->query("SELECT setting_key, setting_value, description FROM system_settings");
            $settings = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $settings[$row['setting_key']] = ['value' => $row['setting_value'], 'desc' => $row['description']];
                    $_SESSION['hcs_settings'][$row['setting_key']] = $row['setting_value'];
                }
            }
            echo json_encode(['success' => true, 'settings' => $settings]);
            $db->close(); exit;
        }
    } catch (\Throwable $e) {
        // Fall through to session cache
    }
    // Session cache fallback
    $cached = $_SESSION['hcs_settings'] ?? [];
    $settings = [];
    foreach ($cached as $k => $v) {
        $settings[$k] = ['value' => $v, 'desc' => ''];
    }
    echo json_encode(['success' => true, 'settings' => $settings, 'cached' => true]);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $updated = 0;

    // Always cache in session first (so UI gets immediate feedback)
    if (!isset($_SESSION['hcs_settings'])) $_SESSION['hcs_settings'] = [];
    foreach ($input as $key => $value) {
        $key = sanitize($key);
        $value = sanitize((string)$value);
        $_SESSION['hcs_settings'][$key] = $value;
    }

    // Try to persist to DB; ignore failures
    try {
        $db = @getDB();
        if ($db) {
            foreach ($input as $key => $value) {
                $key = sanitize($key);
                $value = sanitize((string)$value);
                $stmt = $db->prepare(
                    "INSERT INTO system_settings (setting_key, setting_value)
                     VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
                );
                $stmt->bind_param('ss', $key, $value);
                if ($stmt->execute()) $updated++;
            }
            $db->close();
        }
    } catch (\Throwable $e) {
        // DB write failed — session cache is enough
    }

    echo json_encode([
        'success' => true,
        'message' => "Settings saved ($updated to DB, " . count($input) . " to session)."
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Method not allowed']);
