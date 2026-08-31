<?php
// ============================================================
//  API: Scheduled Announcements  — CRUD
//  v3.1.2: Returns empty array (with success=true) when DB is offline
//          so the UI doesn't crash trying to read undefined.
//  /api/scheduled.php
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
    $sql = "SELECT sa.*, d.name AS doctor_name, l.name AS location_name
            FROM scheduled_announcements sa
            LEFT JOIN doctors d ON d.id = sa.target_doctor_id
            LEFT JOIN locations l ON l.id = sa.target_location_id
            WHERE sa.is_active = 1
            ORDER BY sa.scheduled_time ASC";
    $rs = $db->query($sql);
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    echo json_encode(['success'=>true,'data'=>$rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline — cannot save scheduled announcement']); exit; }
    $b = json_decode(file_get_contents('php://input'), true) ?? [];
    $stmt = $db->prepare("INSERT INTO scheduled_announcements
        (title,message_text,target_role,target_doctor_id,target_location_id,voice_gender,scheduled_time,repeat_type)
        VALUES (?,?,?,?,?,?,?,?)");
    $drId = ($b['target_doctor_id']   ?? 0) ?: null;
    $lcId = ($b['target_location_id'] ?? 0) ?: null;
    $schedTime = ($b['scheduled_time'] ?? '') ?: null;
    $stmt->bind_param('sssiisss',
        $b['title']           ?? 'Announcement',
        $b['message_text']    ?? '',
        $b['target_role']     ?? '',
        $drId,
        $lcId,
        $b['voice_gender']    ?? 'female',
        $schedTime,
        $b['repeat_type']     ?? 'once'
    );
    if ($stmt->execute()) {
        echo json_encode(['success'=>true,'id'=>$db->insert_id]);
    } else {
        echo json_encode(['success'=>false,'error'=>$db->error]);
    }
    $db->close(); exit;
}

if ($method === 'DELETE') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $id = (int)($_GET['id'] ?? 0);
    $db->query("UPDATE scheduled_announcements SET is_active=0 WHERE id=$id");
    echo json_encode(['success'=>true]);
    $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed']);
