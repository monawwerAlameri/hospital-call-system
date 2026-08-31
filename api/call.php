<?php
require_once 'config.php';
header('Content-Type: application/json');
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        switch ($action) {
            case 'stats':
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
                break;

            case 'recent':
                $limit = intval($_GET['limit'] ?? 20);
                $limit = min($limit, 100);
                $type = $_GET['type'] ?? '';
                $where = "1=1";
                if ($type) {
                    $type = $db->real_escape_string($type);
                    $where .= " AND call_type='$type'";
                }
                $r = $db->query("SELECT * FROM call_logs WHERE $where ORDER BY created_at DESC LIMIT $limit");
                $rows = [];
                if ($r) { while ($row = $r->fetch_assoc()) $rows[] = $row; }
                echo json_encode(['success' => true, 'data' => $rows]);
                break;

            case 'history':
                $days = intval($_GET['days'] ?? 7);
                $days = min($days, 90);
                $from = date('Y-m-d', strtotime("-{$days} days"));
                $r = $db->query("SELECT DATE(created_at) as day, call_type, COUNT(*) as c FROM call_logs WHERE DATE(created_at) >= '$from' GROUP BY DATE(created_at), call_type ORDER BY day DESC");
                $rows = [];
                if ($r) { while ($row = $r->fetch_assoc()) $rows[] = $row; }
                echo json_encode(['success' => true, 'data' => $rows]);
                break;

            case 'operator_stats':
                $today = date('Y-m-d');
                $r = $db->query("SELECT operator_name, COUNT(*) as total_calls, 
                    SUM(CASE WHEN call_type='emergency_code' THEN 1 ELSE 0 END) as emergencies,
                    SUM(CASE WHEN call_type='call_doctor' THEN 1 ELSE 0 END) as doctor_pages,
                    SUM(CASE WHEN call_type='call_staff' THEN 1 ELSE 0 END) as staff_calls,
                    MAX(created_at) as last_call
                    FROM call_logs WHERE DATE(created_at)='$today' AND operator_name IS NOT NULL AND operator_name != '' 
                    GROUP BY operator_name ORDER BY total_calls DESC");
                $rows = [];
                if ($r) { while ($row = $r->fetch_assoc()) $rows[] = $row; }
                echo json_encode(['success' => true, 'data' => $rows]);
                break;

            default:
                echo json_encode(['success' => false, 'error' => 'Unknown action. Available: stats, recent, history, operator_stats']);
        }
        break;

    case 'POST':
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
        break;

    case 'DELETE':
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $db->query("DELETE FROM call_logs WHERE id=$id");
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID required']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Method not supported']);
}
