<?php
// ============================================================
//  API: System Settings — GET (public) / POST (auth required)
// ============================================================
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

if ($method === 'GET') {
    $result = $db->query("SELECT setting_key, setting_value, description FROM system_settings");
    $settings = [];
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = ['value' => $row['setting_value'], 'desc' => $row['description']];
    }
    echo json_encode(['success' => true, 'settings' => $settings]);
    $db->close(); exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $updated = 0;
    foreach ($input as $key => $value) {
        $key   = sanitize($key);
        $value = sanitize((string)$value);
        $stmt  = $db->prepare(
            "INSERT INTO system_settings (setting_key, setting_value)
             VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
        );
        $stmt->bind_param('ss', $key, $value);
        if ($stmt->execute()) $updated++;
    }
    echo json_encode(['success' => true, 'message' => "Settings saved ($updated keys)."]);
    $db->close(); exit;
}

echo json_encode(['success' => false, 'message' => 'Method not allowed']);
$db->close();
