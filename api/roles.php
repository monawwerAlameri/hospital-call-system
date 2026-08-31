<?php
// ============================================================
//  API: Staff Roles — CRUD
//  v3.1.2: Returns fallback data when DB is unavailable.
//  /api/roles.php
// ============================================================
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// Default fallback roles
$FALLBACK_ROLES = [
    ['id' => 1,  'name' => 'Hospital Director On Call',   'name_ar' => 'مدير المستشفى المناوب',     'code' => 'DIR', 'category' => 'admin',   'default_gender' => 'male', 'is_active' => 1],
    ['id' => 2,  'name' => 'Administrative Supervisor',   'name_ar' => 'المشرف الإداري',            'code' => 'ADM', 'category' => 'admin',   'default_gender' => 'any',  'is_active' => 1],
    ['id' => 3,  'name' => 'Security Supervisor',          'name_ar' => 'مشرف الأمن',                'code' => 'SEC', 'category' => 'admin',   'default_gender' => 'male', 'is_active' => 1],
    ['id' => 4,  'name' => 'Maintenance Supervisor',       'name_ar' => 'مشرف الصيانة',              'code' => 'MNT', 'category' => 'support', 'default_gender' => 'male', 'is_active' => 1],
    ['id' => 5,  'name' => 'IT Support',                   'name_ar' => 'دعم تقنية المعلومات',        'code' => 'IT',  'category' => 'support', 'default_gender' => 'any',  'is_active' => 1],
    ['id' => 6,  'name' => 'Nursing Supervisor',            'name_ar' => 'مشرفة التمريض',              'code' => 'NS',  'category' => 'medical', 'default_gender' => 'female','is_active' => 1],
    ['id' => 7,  'name' => 'Head Nurse',                    'name_ar' => 'رئيسة التمريض',              'code' => 'HN',  'category' => 'medical', 'default_gender' => 'female','is_active' => 1],
    ['id' => 8,  'name' => 'Laboratory Technician',         'name_ar' => 'فني المختبر',                'code' => 'LAB', 'category' => 'medical', 'default_gender' => 'any',  'is_active' => 1],
    ['id' => 9,  'name' => 'Radiology Technician',          'name_ar' => 'فني الأشعة',                 'code' => 'RAD', 'category' => 'medical', 'default_gender' => 'any',  'is_active' => 1],
    ['id' => 10, 'name' => 'Respiratory Therapist',         'name_ar' => 'أخصائي العلاج التنفسي',      'code' => 'RT',  'category' => 'medical', 'default_gender' => 'any',  'is_active' => 1],
    ['id' => 11, 'name' => 'OR Technician',                  'name_ar' => 'فني غرفة العمليات',          'code' => 'OR',  'category' => 'medical', 'default_gender' => 'any',  'is_active' => 1],
    ['id' => 12, 'name' => 'Dialysis Technician',            'name_ar' => 'فني الغسيل الكلوي',          'code' => 'DLY', 'category' => 'medical', 'default_gender' => 'any',  'is_active' => 1],
    ['id' => 13, 'name' => 'Pharmacist On Call',             'name_ar' => 'الصيدلاني المناوب',           'code' => 'PHR', 'category' => 'medical', 'default_gender' => 'any',  'is_active' => 1],
    ['id' => 14, 'name' => 'Social Worker',                  'name_ar' => 'الأخصائي الاجتماعي',         'code' => 'SOC', 'category' => 'admin',   'default_gender' => 'any',  'is_active' => 1],
    ['id' => 15, 'name' => 'Security',                       'name_ar' => 'الأمن',                       'code' => 'SEC', 'category' => 'admin',   'default_gender' => 'male', 'is_active' => 1],
    ['id' => 16, 'name' => 'Housekeeping',                    'name_ar' => 'النظافة',                     'code' => 'HSK', 'category' => 'support', 'default_gender' => 'any',  'is_active' => 1],
    ['id' => 17, 'name' => 'Maintenance',                    'name_ar' => 'الصيانة',                     'code' => 'MNT', 'category' => 'support', 'default_gender' => 'male', 'is_active' => 1],
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
        echo json_encode(['success' => true, 'data' => $FALLBACK_ROLES, 'source' => 'fallback']);
        exit;
    }
    $rs = $db->query("SELECT * FROM staff_roles WHERE is_active=1 ORDER BY name ASC");
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    if (empty($rows)) $rows = $FALLBACK_ROLES;
    echo json_encode(['success' => true, 'data' => $rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $b = json_decode(file_get_contents('php://input'), true) ?? [];
    $stmt = $db->prepare("INSERT INTO staff_roles (name, name_ar, code, category, default_gender) VALUES (?,?,?,?,?)");
    $cat = $b['category'] ?? 'medical';
    $gen = $b['default_gender'] ?? 'any';
    $code = $b['code'] ?? '';
    $stmt->bind_param('sssss', $b['name'] ?? '', $b['name_ar'] ?? '', $code, $cat, $gen);
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
    $db->query("UPDATE staff_roles SET is_active=0 WHERE id=$id");
    echo json_encode(['success' => true]);
    $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed']);
