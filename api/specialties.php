<?php
// ============================================================
//  API: Specialties — CRUD
//  v3.1.2: Returns fallback data when DB is unavailable.
//  /api/specialties.php
// ============================================================
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// Default fallback specialties
$FALLBACK_SPECS = [
    ['id' => 1,  'name' => 'Internal Medicine',        'name_ar' => 'الطب الباطني',          'code' => '', 'is_active' => 1],
    ['id' => 2,  'name' => 'Cardiology',                'name_ar' => 'أمراض القلب',           'code' => '', 'is_active' => 1],
    ['id' => 3,  'name' => 'Neurology',                 'name_ar' => 'طب الأعصاب',            'code' => '', 'is_active' => 1],
    ['id' => 4,  'name' => 'Neurosurgery',              'name_ar' => 'جراحة الأعصاب',         'code' => '', 'is_active' => 1],
    ['id' => 5,  'name' => 'Gastroenterology',         'name_ar' => 'طب الجهاز الهضمي',      'code' => '', 'is_active' => 1],
    ['id' => 6,  'name' => 'Endocrinology',             'name_ar' => 'الغدد الصماء',           'code' => '', 'is_active' => 1],
    ['id' => 7,  'name' => 'General Surgery',          'name_ar' => 'الجراحة العامة',        'code' => '', 'is_active' => 1],
    ['id' => 8,  'name' => 'Orthopedic Surgery',       'name_ar' => 'جراحة العظام',          'code' => '', 'is_active' => 1],
    ['id' => 9,  'name' => 'Urology',                  'name_ar' => 'طب المسالك البولية',    'code' => '', 'is_active' => 1],
    ['id' => 10, 'name' => 'Pediatrics',                'name_ar' => 'طب الأطفال',             'code' => '', 'is_active' => 1],
    ['id' => 11, 'name' => 'Obstetrics and Gynecology','name_ar' => 'النساء والولادة',        'code' => '', 'is_active' => 1],
    ['id' => 12, 'name' => 'Anesthesia',                'name_ar' => 'التخدير',                'code' => '', 'is_active' => 1],
    ['id' => 13, 'name' => 'Psychiatry',                'name_ar' => 'الطب النفسي',            'code' => '', 'is_active' => 1],
    ['id' => 14, 'name' => 'Dermatology',               'name_ar' => 'الجلدية',                'code' => '', 'is_active' => 1],
    ['id' => 15, 'name' => 'Ophthalmology',             'name_ar' => 'طب العيون',              'code' => '', 'is_active' => 1],
    ['id' => 16, 'name' => 'ENT',                       'name_ar' => 'الأنف والأذن والحنجرة', 'code' => '', 'is_active' => 1],
    ['id' => 17, 'name' => 'Oncology',                  'name_ar' => 'الأورام',                'code' => '', 'is_active' => 1],
    ['id' => 18, 'name' => 'Pulmonology',               'name_ar' => 'أمراض الصدر',            'code' => '', 'is_active' => 1],
    ['id' => 19, 'name' => 'Nephrology',                'name_ar' => 'أمراض الكلى',            'code' => '', 'is_active' => 1],
    ['id' => 20, 'name' => 'Hematology',                'name_ar' => 'أمراض الدم',             'code' => '', 'is_active' => 1],
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
        echo json_encode(['success' => true, 'data' => $FALLBACK_SPECS, 'source' => 'fallback']);
        exit;
    }
    $rs = $db->query("SELECT * FROM specialties WHERE is_active=1 ORDER BY name ASC");
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    if (empty($rows)) $rows = $FALLBACK_SPECS;
    echo json_encode(['success' => true, 'data' => $rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $b = json_decode(file_get_contents('php://input'), true) ?? [];
    $stmt = $db->prepare("INSERT INTO specialties (name, name_ar, code) VALUES (?,?,?)");
    $code = $b['code'] ?? '';
    $stmt->bind_param('sss', $b['name'] ?? '', $b['name_ar'] ?? '', $code);
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
    $db->query("UPDATE specialties SET is_active=0 WHERE id=$id");
    echo json_encode(['success' => true]);
    $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed']);
