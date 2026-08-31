<?php
// ============================================================
//  API: Call Logs — GET with optional filters / POST / DELETE
//  v3.1.2: Returns empty array (success=true) when DB is offline.
// ============================================================
require_once __DIR__ . '/config.php';
$method = $_SERVER['REQUEST_METHOD'];

// Try DB
$db = null;
$dbOk = false;
try {
    $db = @getDB();
    if ($db) $dbOk = true;
} catch (\Throwable $e) {
    $dbOk = false;
}

if ($method === 'GET') {
    if (!$dbOk) {
        echo json_encode(['success' => true, 'data' => [], 'source' => 'fallback']);
        exit;
    }
    $limit  = (int)($_GET['limit'] ?? 100);
    $type   = isset($_GET['type']) ? $db->real_escape_string($_GET['type']) : '';
    $where  = $type ? "WHERE cl.call_type = '$type'" : '';
    $sql    = "SELECT cl.*, u.name AS user_name
               FROM call_logs cl
               LEFT JOIN users u ON u.id = cl.initiated_by
               $where
               ORDER BY cl.created_at DESC
               LIMIT $limit";
    $rs   = $db->query($sql);
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    echo json_encode(['success' => true, 'data' => $rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
    if (!$dbOk) {
        // Silently accept the log without saving — UI shouldn't crash
        echo json_encode(['success' => true, 'id' => 0, 'source' => 'fallback', 'note' => 'DB offline — log not persisted']);
        exit;
    }
    $b    = json_decode(file_get_contents('php://input'), true) ?? [];
    $ip   = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt = $db->prepare(
        "INSERT INTO call_logs
         (call_type,code,location_name,specialty_name,staff_role_name,
          doctor_name,extension,announced_text,voice_gender,
          initiated_by,operator_name,ip_address,status)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,'sent')"
    );
    $uid = ($b['initiated_by'] ?? null) ?: null;
    $stmt->bind_param('sssssssssiss',
        $b['call_type']       ?? 'custom',
        $b['code']            ?? '',
        $b['location_name']   ?? '',
        $b['specialty_name']  ?? '',
        $b['staff_role_name'] ?? '',
        $b['doctor_name']     ?? '',
        $b['extension']       ?? '',
        $b['announced_text']  ?? '',
        $b['voice_gender']    ?? 'female',
        $uid,
        $b['operator_name']   ?? '',
        $ip
    );
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $db->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $db->error]);
    }
    $db->close(); exit;
}

if ($method === 'DELETE') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $days = (int)($_GET['days'] ?? 30);
    $db->query("DELETE FROM call_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL $days DAY)");
    echo json_encode(['success' => true, 'message' => "Logs older than $days days deleted."]);
    $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Method not supported']);
