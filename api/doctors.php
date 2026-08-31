<?php
// ============================================================
//  API: Doctors + Staff  — Full CRUD with staff_type support
// ============================================================
require_once __DIR__ . '/config.php';
$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();

if ($method === 'GET') {
    // Optional filter
    $typeFilter = isset($_GET['type']) ? "AND d.staff_type='" . $db->real_escape_string($_GET['type']) . "'" : '';
    $sql = "SELECT d.*, s.name AS specialty_name, s.name_ar AS specialty_ar,
                   l.name AS dept_name, l.name_ar AS dept_ar, l.code AS dept_code
            FROM doctors d
            LEFT JOIN specialties s ON s.id = d.specialty_id
            LEFT JOIN locations l   ON l.id = d.department_id
            WHERE d.is_active = 1 $typeFilter
            ORDER BY d.name ASC";
    $rs = $db->query($sql);
    $rows = [];
    while ($r = $rs->fetch_assoc()) $rows[] = $r;
    echo json_encode(['success' => true, 'data' => $rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
    $b    = json_decode(file_get_contents('php://input'), true) ?? [];
    $spId = isset($b['specialty_id']) && $b['specialty_id'] ? (int)$b['specialty_id'] : null;
    $dpId = isset($b['department_id']) && $b['department_id'] ? (int)$b['department_id'] : null;
    $type = $b['staff_type'] ?? 'doctor';

    // Check if doctors table has staff_type column; add if missing
    $colCheck = $db->query("SHOW COLUMNS FROM doctors LIKE 'staff_type'");
    if ($colCheck->num_rows === 0) {
        $db->query("ALTER TABLE doctors ADD COLUMN staff_type VARCHAR(30) DEFAULT 'doctor' AFTER gender");
    }

    $stmt = $db->prepare("INSERT INTO doctors (name,name_ar,specialty_id,level,gender,staff_type,phone,extension,department_id) VALUES (?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssiisssis',
        $b['name']       ?? '',
        $b['name_ar']    ?? '',
        $spId,
        $b['level']      ?? 'Consultant',
        $b['gender']     ?? 'male',
        $type,
        $b['phone']      ?? '',
        $b['extension']  ?? '',
        $dpId
    );
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $db->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $db->error]);
    }
    $db->close(); exit;
}

if ($method === 'PUT') {
    $id = (int)($_GET['id'] ?? 0);
    $b  = json_decode(file_get_contents('php://input'), true) ?? [];
    $allowed = ['name','name_ar','level','gender','staff_type','phone','extension','specialty_id','department_id'];
    $sets = [];
    foreach ($allowed as $f) {
        if (isset($b[$f])) {
            if (in_array($f, ['specialty_id','department_id'])) {
                $sets[] = "`$f`=" . (int)$b[$f];
            } else {
                $sets[] = "`$f`='" . $db->real_escape_string($b[$f]) . "'";
            }
        }
    }
    if ($sets) $db->query("UPDATE doctors SET " . implode(',', $sets) . " WHERE id=$id");
    echo json_encode(['success' => true]);
    $db->close(); exit;
}

if ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    $db->query("UPDATE doctors SET is_active=0 WHERE id=$id");
    echo json_encode(['success' => true]);
    $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Method not supported']);
$db->close();
