<?php
// ============================================================
//  API: Visit Hours Configuration  — v3.1
//  /api/visit_hours.php
//  Single-row configuration table that drives automatic
//  visit-start / visit-end-warn / visit-end announcements.
//  Announcements are bilingual (Arabic + English fallback).
// ============================================================
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

// ------------------------------------------------------------
//  GET — return the single visit_hours_config row
// ------------------------------------------------------------
if ($method === 'GET') {
    $rs = $db->query("SELECT * FROM visit_hours_config ORDER BY id ASC LIMIT 1");
    $row = $rs ? $rs->fetch_assoc() : null;
    if (!$row) {
        // Bootstrap with defaults if row is missing for some reason
        $db->query("INSERT INTO visit_hours_config (is_enabled, start_time, end_time, start_msg_ar, end_msg_ar, warn_msg_ar, start_msg_en, end_msg_en, warn_msg_en) VALUES (
            1, '16:00', '20:00',
            'بدأت ساعات الزيارة. يرجى من الزوار التوجه إلى الأقسام المخصصة.',
            'انتهت ساعات الزيارة. يرجى من الزوار مغادرة المستشفى. شاكرين لكم تفهمكم.',
            'تنتهي ساعات الزيارة خلال 10 دقائق. يرجى من الزوار الاستعداد للمغادرة.',
            'Visiting hours have begun. Visitors may proceed to the designated wards.',
            'Visiting hours have ended. Visitors are kindly requested to leave the hospital. Thank you.',
            'Visiting hours will end in 10 minutes. Visitors are kindly requested to prepare to leave.'
        )");
        $rs = $db->query("SELECT * FROM visit_hours_config ORDER BY id ASC LIMIT 1");
        $row = $rs ? $rs->fetch_assoc() : null;
    }
    echo json_encode(['success' => true, 'config' => $row]);
    $db->close(); exit;
}

// ------------------------------------------------------------
//  POST — upsert the single config row
// ------------------------------------------------------------
if ($method === 'POST') {
    $b = json_decode(file_get_contents('php://input'), true) ?? [];

    $is_enabled = isset($b['is_enabled']) ? (int)!!$b['is_enabled'] : 1;
    $start_time = substr(preg_replace('/[^0-9:]/', '', $b['start_time'] ?? '16:00'), 0, 5) ?: '16:00';
    $end_time   = substr(preg_replace('/[^0-9:]/', '', $b['end_time']   ?? '20:00'), 0, 5) ?: '20:00';
    $start_msg_ar = $b['start_msg_ar'] ?? 'بدأت ساعات الزيارة. يرجى من الزوار التوجه إلى الأقسام المخصصة.';
    $end_msg_ar   = $b['end_msg_ar']   ?? 'انتهت ساعات الزيارة. يرجى من الزوار مغادرة المستشفى. شاكرين لكم تفهمكم.';
    $warn_msg_ar  = $b['warn_msg_ar']  ?? 'تنتهي ساعات الزيارة خلال 10 دقائق. يرجى من الزوار الاستعداد للمغادرة.';
    $start_msg_en = $b['start_msg_en'] ?? 'Visiting hours have begun. Visitors may proceed to the designated wards.';
    $end_msg_en   = $b['end_msg_en']   ?? 'Visiting hours have ended. Visitors are kindly requested to leave the hospital. Thank you.';
    $warn_msg_en  = $b['warn_msg_en']  ?? 'Visiting hours will end in 10 minutes. Visitors are kindly requested to prepare to leave.';

    // Check if config row exists
    $exists = $db->query("SELECT id FROM visit_hours_config LIMIT 1");
    if ($exists && $exists->num_rows > 0) {
        $stmt = $db->prepare("UPDATE visit_hours_config SET
            is_enabled = ?, start_time = ?, end_time = ?,
            start_msg_ar = ?, end_msg_ar = ?, warn_msg_ar = ?,
            start_msg_en = ?, end_msg_en = ?, warn_msg_en = ?
            WHERE id = (SELECT id FROM (SELECT id FROM visit_hours_config LIMIT 1) AS tmp)");
        $stmt->bind_param('issssssss',
            $is_enabled, $start_time, $end_time,
            $start_msg_ar, $end_msg_ar, $warn_msg_ar,
            $start_msg_en, $end_msg_en, $warn_msg_en
        );
    } else {
        $stmt = $db->prepare("INSERT INTO visit_hours_config
            (is_enabled, start_time, end_time, start_msg_ar, end_msg_ar, warn_msg_ar, start_msg_en, end_msg_en, warn_msg_en)
            VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('issssssss',
            $is_enabled, $start_time, $end_time,
            $start_msg_ar, $end_msg_ar, $warn_msg_ar,
            $start_msg_en, $end_msg_en, $warn_msg_en
        );
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Visit hours configuration saved.']);
    } else {
        echo json_encode(['success' => false, 'error' => $db->error]);
    }
    $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed']);
$db->close();
