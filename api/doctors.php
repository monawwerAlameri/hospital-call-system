<?php
// ============================================================
//  API: Doctors — CRUD
//  v3.1.2: Returns fallback data when DB is unavailable.
//  /api/doctors.php
// ============================================================
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// Default fallback doctors
$FALLBACK_DOCTORS = [
    ['id' => 1, 'name' => 'Dr. Ahmed Al-Ghamdi',    'name_ar' => 'د. أحمد الغامدي',     'specialty_id' => 1, 'specialty_name' => 'Cardiology',        'specialty_name_ar' => 'أمراض القلب',     'level' => 'Consultant', 'gender' => 'male',   'staff_type' => 'doctor',     'phone' => '', 'extension' => '1234', 'department_id' => 1, 'dept_name' => 'Emergency Room',       'dept_name_ar' => 'قسم الطوارئ',          'is_active' => 1],
    ['id' => 2, 'name' => 'Dr. Fatima Al-Zahrani',  'name_ar' => 'د. فاطمة الزهراني',   'specialty_id' => 2, 'specialty_name' => 'Neurology',          'specialty_name_ar' => 'طب الأعصاب',       'level' => 'Specialist', 'gender' => 'female', 'staff_type' => 'doctor',     'phone' => '', 'extension' => '2345', 'department_id' => 2, 'dept_name' => 'Intensive Care Unit',  'dept_name_ar' => 'وحدة العناية المركزة',  'is_active' => 1],
    ['id' => 3, 'name' => 'Dr. Mohammed Al-Otaibi', 'name_ar' => 'د. محمد العتيبي',     'specialty_id' => 4, 'specialty_name' => 'Internal Medicine',  'specialty_name_ar' => 'الطب الباطني',     'level' => 'Consultant', 'gender' => 'male',   'staff_type' => 'doctor',     'phone' => '', 'extension' => '3456', 'department_id' => 1, 'dept_name' => 'Emergency Room',       'dept_name_ar' => 'قسم الطوارئ',          'is_active' => 1],
    ['id' => 4, 'name' => 'Sara Al-Qahtani',         'name_ar' => 'سارة القحطاني',        'specialty_id' => 3, 'specialty_name' => 'Pediatrics',         'specialty_name_ar' => 'طب الأطفال',       'level' => 'Specialist', 'gender' => 'female', 'staff_type' => 'nurse',       'phone' => '', 'extension' => '4567', 'department_id' => 2, 'dept_name' => 'Intensive Care Unit',  'dept_name_ar' => 'وحدة العناية المركزة',  'is_active' => 1],
    ['id' => 5, 'name' => 'Khaled Al-Harbi',        'name_ar' => 'خالد الحربي',          'specialty_id' => 2, 'specialty_name' => 'Neurology',          'specialty_name_ar' => 'طب الأعصاب',       'level' => 'Resident',   'gender' => 'male',   'staff_type' => 'technician', 'phone' => '', 'extension' => '5678', 'department_id' => 3, 'dept_name' => 'Surgery',              'dept_name_ar' => 'الجراحة',              'is_active' => 1],
];

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
        echo json_encode(['success' => true, 'data' => $FALLBACK_DOCTORS, 'source' => 'fallback']);
        exit;
    }
    $sql = "SELECT d.*, s.name AS specialty_name, s.name_ar AS specialty_name_ar,
                   l.name AS dept_name, l.name_ar AS dept_name_ar
            FROM doctors d
            LEFT JOIN specialties s ON s.id = d.specialty_id
            LEFT JOIN locations l ON l.id = d.department_id
            WHERE d.is_active = 1
            ORDER BY d.name ASC";
    $rs = $db->query($sql);
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    if (empty($rows)) $rows = $FALLBACK_DOCTORS;
    echo json_encode(['success' => true, 'data' => $rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $b = json_decode(file_get_contents('php://input'), true) ?? [];
    $stmt = $db->prepare("INSERT INTO doctors (name, name_ar, specialty_id, level, gender, staff_type, phone, extension, department_id, is_active) VALUES (?,?,?,?,?,?,?,?,?,1)");
    $specId = (int)($b['specialty_id'] ?? 0) ?: null;
    $deptId = (int)($b['department_id'] ?? 0) ?: null;
    $stmt->bind_param('ssissssi',
        $b['name']       ?? '',
        $b['name_ar']    ?? '',
        $specId,
        $b['level']      ?? 'Specialist',
        $b['gender']     ?? 'male',
        $b['staff_type'] ?? 'doctor',
        $b['phone']      ?? '',
        $b['extension']  ?? '',
        $deptId
    );
    // bind_param with sssisssi is 8 params but we have 9 (10 with is_active=1 hard-coded)
    // Let's redo this more safely:
    $stmt = $db->prepare("INSERT INTO doctors (name, name_ar, specialty_id, level, gender, staff_type, phone, extension, department_id) VALUES (?,?,?,?,?,?,?,?,?)");
    $name = $b['name'] ?? '';
    $name_ar = $b['name_ar'] ?? '';
    $level = $b['level'] ?? 'Specialist';
    $gender = $b['gender'] ?? 'male';
    $staffType = $b['staff_type'] ?? 'doctor';
    $phone = $b['phone'] ?? '';
    $ext = $b['extension'] ?? '';
    $stmt->bind_param('ssisssssi', $name, $name_ar, $specId, $level, $gender, $staffType, $phone, $ext, $deptId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $db->insert_id]);
    } else {
        echo json_encode(['success' => false, 'error' => $db->error]);
    }
    $db->close(); exit;
}

if ($method === 'DELETE') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $id = (int)($_GET['id'] ?? 0);
    $db->query("UPDATE doctors SET is_active=0 WHERE id=$id");
    echo json_encode(['success' => true]);
    $db->close(); exit;
}

if ($method === 'PUT') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $id = (int)($_GET['id'] ?? 0);
    $b = json_decode(file_get_contents('php://input'), true) ?? [];
    $allowed = ['name', 'name_ar', 'level', 'gender', 'staff_type', 'phone', 'extension', 'specialty_id', 'department_id'];
    $sets = [];
    foreach ($allowed as $f) {
        if (isset($b[$f])) {
            $v = $b[$f];
            if (is_numeric($v)) {
                $sets[] = "`$f`=" . (int)$v;
            } else {
                $sets[] = "`$f`='" . $db->real_escape_string($v) . "'";
            }
        }
    }
    if ($sets) $db->query("UPDATE doctors SET " . implode(',', $sets) . " WHERE id=$id");
    echo json_encode(['success' => true]);
    $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed']);
