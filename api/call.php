<?php
// ============================================================
//  API: Call — stats / recent / history
//  v3.1.2: Returns empty data (success=true) when DB is offline.
// ============================================================
require_once 'config.php';
header('Content-Type: application/json');

// Try DB
$db = null;
$dbOk = false;
try {
    $db = @getDB();
    if ($db) $dbOk = true;
} catch (\Throwable $e) {
    $dbOk = false;
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        switch ($action) {
            case 'stats':
                if (!$dbOk) {
                    echo json_encode(['success' => true, 'stats' => ['total' => 0, 'emergency' => 0, 'doctor' => 0, 'staff' => 0, 'custom' => 0], 'by_hour' => [], 'top_locations' => [], 'top_codes' => [], 'source' => 'fallback']);
                    exit;
                }
                $today = date('Y-m-d');
                $total = $db->query("SELECT COUNT(*) as c FROM call_logs WHERE DATE(created_at)='$today'")->fetch_assoc()['c'] ?? 0;
                $emergency = $db->query("SELECT COUNT(*) as c FROM call_logs WHERE call_type='emergency_code' AND DATE(created_at)='$today'")->fetch_assoc()['c'] ?? 0;
                $doctor = $db->query("SELECT COUNT(*) as c FROM call_logs WHERE call_type='call_doctor' AND DATE(created_at)='$today'")->fetch_assoc()['c'] ?? 0;
                $staff = $db->query("SELECT COUNT(*) as c FROM call_logs WHERE call_type='call_staff' AND DATE(created_at)='$today'")->fetch_assoc()['c'] ?? 0;
                $custom = $db->query("SELECT COUNT(*) as c FROM call_logs WHERE call_type='custom' AND DATE(created_at)='$today'")->fetch_assoc()['c'] ?? 0;

                $byHour = [];
                $hr = $db->query("SELECT HOUR(created_at) as h, COUNT(*) as c FROM call_logs WHERE DATE(created_at)='$today' GROUP BY HOUR(created_at) ORDER BY h");
                if ($hr) { while ($row = $hr->fetch_assoc()) $byHour[] = $row; }

                $topLocations = [];
                $tl = $db->query("SELECT location_name, COUNT(*) as c FROM call_logs WHERE DATE(created_at)='$today' AND location_name!='' GROUP BY location_name ORDER BY c DESC LIMIT 10");
                if ($tl) { while ($row = $tl->fetch_assoc()) $topLocations[] = $row; }

                $topCodes = [];
                $tc = $db->query("SELECT code, COUNT(*) as c FROM call_logs WHERE call_type='emergency_code' AND DATE(created_at)='$today' GROUP BY code ORDER BY c DESC LIMIT 10");
                if ($tc) { while ($row = $tc->fetch_assoc()) $topCodes[] = $row; }

                echo json_encode([
                    'success' => true,
                    'stats' => [
                        'total' => (int)$total,
                        'emergency' => (int)$emergency,
                        'doctor' => (int)$doctor,
                        'staff' => (int)$staff,
                        'custom' => (int)$custom
                    ],
                    'by_hour' => $byHour,
                    'top_locations' => $topLocations,
                    'top_codes' => $topCodes
                ]);
                $db->close(); exit;

            case 'recent':
                $limit = (int)($_GET['limit'] ?? 20);
                $type  = isset($_GET['type']) ? $db->real_escape_string($_GET['type']) : '';
                if (!$dbOk) {
                    echo json_encode(['success' => true, 'data' => [], 'source' => 'fallback']);
                    exit;
                }
                $where = $type ? "WHERE call_type = '$type'" : '';
                $rs = $db->query("SELECT * FROM call_logs $where ORDER BY created_at DESC LIMIT $limit");
                $rows = [];
                if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
                echo json_encode(['success' => true, 'data' => $rows]);
                $db->close(); exit;

            case 'history':
                if (!$dbOk) {
                    echo json_encode(['success' => true, 'data' => [], 'source' => 'fallback']);
                    exit;
                }
                $days = (int)($_GET['days'] ?? 7);
                $rs = $db->query("SELECT DATE(created_at) as d, COUNT(*) as c FROM call_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL $days DAY) GROUP BY DATE(created_at) ORDER BY d ASC");
                $rows = [];
                if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
                echo json_encode(['success' => true, 'data' => $rows]);
                $db->close(); exit;

            case 'operator_stats':
                if (!$dbOk) {
                    echo json_encode(['success' => true, 'data' => [], 'source' => 'fallback']);
                    exit;
                }
                $today = date('Y-m-d');
                $rs = $db->query("SELECT operator_name, COUNT(*) as c FROM call_logs WHERE DATE(created_at)='$today' AND operator_name IS NOT NULL AND operator_name != '' GROUP BY operator_name ORDER BY c DESC LIMIT 10");
                $rows = [];
                if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
                echo json_encode(['success' => true, 'data' => $rows]);
                $db->close(); exit;
        }
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
        exit;

    case 'POST':
        if (!$dbOk) {
            echo json_encode(['success' => true, 'id' => 0, 'source' => 'fallback', 'note' => 'DB offline — log not persisted']);
            exit;
        }
        $d = json_decode(file_get_contents('php://input'), true);
        if (!$d || !isset($d['call_type'])) {
            echo json_encode(['success' => false, 'error' => 'call_type is required']);
            break;
        }

        $stmt = $db->prepare("INSERT INTO call_logs (call_type, code, location_name, specialty_name, staff_role_name, doctor_name, extension, announced_text, voice_gender, initiated_by, operator_name, ip_address, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $status = 'sent';
        $initiated = isset($d['initiated_by']) ? intval($d['initiated_by']) : 0;
        $stmt->bind_param("sssssssssisss",
            $d['call_type'],
            $d['code'] ?? '',
            $d['location_name'] ?? '',
            $d['specialty_name'] ?? '',
            $d['staff_role_name'] ?? '',
            $d['doctor_name'] ?? '',
            $d['extension'] ?? '',
            $d['announced_text'] ?? '',
            $d['voice_gender'] ?? 'female',
            $initiated,
            $d['operator_name'] ?? '',
            $ip,
            $status
        );
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $db->insert_id]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
