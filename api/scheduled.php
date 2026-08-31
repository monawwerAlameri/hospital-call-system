<?php
// ============================================================
//  API: Scheduled Announcements  — CRUD
//  /api/scheduled.php
// ============================================================
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();

if ($method === 'GET') {
    $sql = "SELECT sa.*, d.name AS doctor_name, l.name AS location_name
            FROM scheduled_announcements sa
            LEFT JOIN doctors d ON d.id = sa.target_doctor_id
            LEFT JOIN locations l ON l.id = sa.target_location_id
            WHERE sa.is_active = 1
            ORDER BY sa.scheduled_time ASC";
    $rs = $db->query($sql);
    $rows = [];
    while ($r = $rs->fetch_assoc()) $rows[] = $r;
    echo json_encode(['success'=>true,'data'=>$rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
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
    $id = (int)($_GET['id'] ?? 0);
    $db->query("UPDATE scheduled_announcements SET is_active=0 WHERE id=$id");
    echo json_encode(['success'=>true]);
    $db->close(); exit;
}
