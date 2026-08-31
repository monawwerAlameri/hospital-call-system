<?php
// ============================================================
//  API: Locations (Departments)  — CRUD
//  v3.1.2: Returns fallback data when DB is unavailable so the UI
//          never shows "undefined / undefined / medical".
//  /api/locations.php
// ============================================================
require_once __DIR__ . '/config.php';

// Default fallback locations (used when DB is offline)
$FALLBACK_LOCS = [
    ['id' => 1, 'code' => 'ER',   'name' => 'Emergency Room',       'name_ar' => 'قسم الطوارئ',          'category' => 'medical', 'floor' => 'Ground Floor', 'extension' => '', 'is_active' => 1],
    ['id' => 2, 'code' => 'ICU',  'name' => 'Intensive Care Unit',  'name_ar' => 'وحدة العناية المركزة',  'category' => 'medical', 'floor' => '2nd Floor',    'extension' => '', 'is_active' => 1],
    ['id' => 3, 'code' => 'CCU',  'name' => 'Coronary Care Unit',   'name_ar' => 'وحدة عناية القلب',      'category' => 'medical', 'floor' => '2nd Floor',    'extension' => '', 'is_active' => 1],
    ['id' => 4, 'code' => 'NICU', 'name' => 'Neonatal ICU',         'name_ar' => 'وحدة عناية حديثي الولادة','category' => 'medical', 'floor' => '3rd Floor',    'extension' => '', 'is_active' => 1],
    ['id' => 5, 'code' => 'MMW',  'name' => 'Male Medical Ward',    'name_ar' => 'الجناح الطبي الرجالي',  'category' => 'medical', 'floor' => '3rd Floor',    'extension' => '', 'is_active' => 1],
    ['id' => 6, 'code' => 'FMW',  'name' => 'Female Medical Ward',  'name_ar' => 'الجناح الطبي النسائي',  'category' => 'medical', 'floor' => '4th Floor',    'extension' => '', 'is_active' => 1],
    ['id' => 7, 'code' => 'OR',   'name' => 'Operating Room',       'name_ar' => 'غرفة العمليات',         'category' => 'medical', 'floor' => '2nd Floor',    'extension' => '', 'is_active' => 1],
    ['id' => 8, 'code' => 'RAD',  'name' => 'Radiology Department', 'name_ar' => 'قسم الأشعة',            'category' => 'medical', 'floor' => 'Ground Floor', 'extension' => '', 'is_active' => 1],
    ['id' => 9, 'code' => 'LAB',  'name' => 'Laboratory',           'name_ar' => 'المختبر',               'category' => 'medical', 'floor' => 'Ground Floor', 'extension' => '', 'is_active' => 1],
    ['id' => 10,'code' => 'DLY',  'name' => 'Dialysis Unit',         'name_ar' => 'وحدة الغسيل الكلوي',     'category' => 'medical', 'floor' => '1st Floor',    'extension' => '', 'is_active' => 1],
    ['id' => 11,'code' => 'OPC',  'name' => 'Outpatient Clinics',   'name_ar' => 'العيادات الخارجية',     'category' => 'medical', 'floor' => '1st Floor',    'extension' => '', 'is_active' => 1],
    ['id' => 12,'code' => 'ADM',  'name' => 'Administration',       'name_ar' => 'الإدارة',               'category' => 'admin',   'floor' => '5th Floor',    'extension' => '', 'is_active' => 1],
    ['id' => 13,'code' => 'LOB',  'name' => 'Main Lobby',           'name_ar' => 'البهو الرئيسي',          'category' => 'general', 'floor' => 'Ground Floor', 'extension' => '', 'is_active' => 1],
];

$method = $_SERVER['REQUEST_METHOD'];

// Try DB connection — if it fails, use fallback data
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
        echo json_encode(['success' => true, 'data' => $FALLBACK_LOCS, 'source' => 'fallback']);
        exit;
    }
    $rs = $db->query("SELECT * FROM locations WHERE is_active=1 ORDER BY name ASC");
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    // If DB returned no rows, also use fallback
    if (empty($rows)) $rows = $FALLBACK_LOCS;
    echo json_encode(['success' => true, 'data' => $rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline — cannot save']); exit; }
    $b = json_decode(file_get_contents('php://input'), true) ?? [];
    $code = strtoupper(preg_replace('/[^A-Z0-9]/i','', $b['code'] ?? substr($b['name'],0,4)));
    $stmt = $db->prepare("INSERT INTO locations (name,name_ar,code,category,floor,extension) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('ssssss',
        $b['name']      ?? '',
        $b['name_ar']   ?? '',
        $code,
        $b['category']  ?? 'medical',
        $b['floor']     ?? '',
        $b['extension'] ?? ''
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
    $db->query("UPDATE locations SET is_active=0 WHERE id=$id");
    echo json_encode(['success'=>true]);
    $db->close(); exit;
}

if ($method === 'PUT') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $id = (int)($_GET['id'] ?? 0);
    $b  = json_decode(file_get_contents('php://input'), true) ?? [];
    $allowed = ['name','name_ar','category','floor','extension','is_active'];
    $sets = [];
    foreach ($allowed as $f) {
        if (isset($b[$f])) $sets[] = "`$f`='" . $db->real_escape_string($b[$f]) . "'";
    }
    if ($sets) $db->query("UPDATE locations SET " . implode(',', $sets) . " WHERE id=$id");
    echo json_encode(['success'=>true]);
    $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed']);
