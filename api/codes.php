<?php
// ============================================================
//  API: Emergency Codes — CRUD
//  v3.1.2: Returns fallback data when DB is unavailable.
//  /api/codes.php
// ============================================================
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// Default fallback codes (v3.1 format: "Code <Name> in {loc}")
$FALLBACK_CODES = [
    ['id' => 1, 'code_key' => 'CODE_BLUE',   'name' => 'Code Blue',   'name_ar' => 'كود أزرق',   'description' => 'Cardiac/Respiratory Arrest', 'color' => '#2563eb', 'text_color' => '#ffffff', 'icon' => 'fa-heartbeat',       'priority' => 'critical', 'msg_en' => 'Code Blue in {loc}. Medical emergency team, respond immediately.',    'msg_ar' => 'كود أزرق في {loc_ar}. فريق الطوارئ الطبية، الاستجابة فورًا.',     'action_note' => 'Crash team respond immediately, bring crash cart',           'is_builtin' => 1, 'sort_order' => 1, 'is_active' => 1],
    ['id' => 2, 'code_key' => 'CODE_RED',    'name' => 'Code Red',    'name_ar' => 'كود أحمر',   'description' => 'Fire Emergency',              'color' => '#dc2626', 'text_color' => '#ffffff', 'icon' => 'fa-fire',             'priority' => 'critical', 'msg_en' => 'Code Red in {loc}. Fire emergency. Evacuate area and call security.', 'msg_ar' => 'كود أحمر في {loc_ar}. حالة حريق طارئة. إخلاء المنطقة واستدعاء الأمن.', 'action_note' => 'Evacuate area, call fire department 998',                       'is_builtin' => 1, 'sort_order' => 2, 'is_active' => 1],
    ['id' => 3, 'code_key' => 'CODE_BLACK',  'name' => 'Code Black',  'name_ar' => 'كود أسود',   'description' => 'Bomb Threat',                  'color' => '#1e1b4b', 'text_color' => '#ffffff', 'icon' => 'fa-skull-crossbones','priority' => 'critical', 'msg_en' => 'Code Black in {loc}. Bomb threat. Evacuate area immediately.',         'msg_ar' => 'كود أسود في {loc_ar}. تهديد بوجود قنبلة. إخلاء المنطقة فورًا.',     'action_note' => 'Do not touch, evacuate area, notify police 999',               'is_builtin' => 1, 'sort_order' => 3, 'is_active' => 1],
    ['id' => 4, 'code_key' => 'CODE_PINK',   'name' => 'Code Pink',   'name_ar' => 'كود وردي',   'description' => 'Infant/Child Abduction',       'color' => '#ec4899', 'text_color' => '#ffffff', 'icon' => 'fa-baby',             'priority' => 'critical', 'msg_en' => 'Code Pink in {loc}. Infant alert. Security lock down exits.',       'msg_ar' => 'كود وردي في {loc_ar}. تنبيه اختطاف طفل. الأمن يغلق المخارج فورًا.', 'action_note' => 'Lock all exits, check all persons leaving, call security',     'is_builtin' => 1, 'sort_order' => 4, 'is_active' => 1],
    ['id' => 5, 'code_key' => 'CODE_WHITE',  'name' => 'Code White',  'name_ar' => 'كود أبيض',   'description' => 'Violent/Aggressive Patient',  'color' => '#f8fafc', 'text_color' => '#1e293b', 'icon' => 'fa-hand-fist',        'priority' => 'high',     'msg_en' => 'Code White in {loc}. Security team, respond immediately.',           'msg_ar' => 'كود أبيض في {loc_ar}. فريق الأمن، الاستجابة فورًا.',               'action_note' => 'Security contain situation, do not approach alone',             'is_builtin' => 1, 'sort_order' => 5, 'is_active' => 1],
    ['id' => 6, 'code_key' => 'CODE_YELLOW', 'name' => 'Code Yellow', 'name_ar' => 'كود أصفر',   'description' => 'Missing Patient',               'color' => '#eab308', 'text_color' => '#1e293b', 'icon' => 'fa-person-walking', 'priority' => 'high',     'msg_en' => 'Code Yellow in {loc}. Missing patient. All staff be on alert.',      'msg_ar' => 'كود أصفر في {loc_ar}. مريض مفقود. جميع الكوادر في حالة تأهب.',     'action_note' => 'Search all areas, check CCTV, notify all security',              'is_builtin' => 1, 'sort_order' => 6, 'is_active' => 1],
    ['id' => 7, 'code_key' => 'CODE_RRT',    'name' => 'Rapid Response', 'name_ar' => 'فريق الاستجابة السريعة', 'description' => 'Rapid Response Team', 'color' => '#7c3aed', 'text_color' => '#ffffff', 'icon' => 'fa-bolt',           'priority' => 'high',     'msg_en' => 'Rapid Response Team in {loc}. R R T team, respond immediately.',     'msg_ar' => 'فريق الاستجابة السريعة في {loc_ar}. الاستجابة فورًا.',             'action_note' => 'RRT team respond with equipment including crash cart',         'is_builtin' => 1, 'sort_order' => 7, 'is_active' => 1],
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
        echo json_encode(['success' => true, 'data' => $FALLBACK_CODES, 'source' => 'fallback']);
        exit;
    }
    $rs = $db->query("SELECT * FROM emergency_codes WHERE is_active=1 ORDER BY sort_order ASC, id ASC");
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    if (empty($rows)) $rows = $FALLBACK_CODES;
    echo json_encode(['success' => true, 'data' => $rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $key  = strtoupper(preg_replace('/[^A-Z0-9_]/i', '_', $body['code_key'] ?? ''));
    if (!$key) { echo json_encode(['success'=>false,'error'=>'code_key required']); exit; }

    $stmt = $db->prepare("INSERT INTO emergency_codes
        (code_key,name,name_ar,description,color,text_color,icon,priority,msg_en,msg_ar,action_note,sort_order)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    $sort = (int)($body['sort_order'] ?? 99);
    $name = $body['name'] ?? $key;
    $name_ar = $body['name_ar'] ?? '';
    $desc = $body['description'] ?? '';
    $color = $body['color'] ?? '#1549c0';
    $text_color = $body['text_color'] ?? '#ffffff';
    $icon = $body['icon'] ?? 'fa-exclamation-triangle';
    $priority = $body['priority'] ?? 'high';
    $msg_en = $body['msg_en'] ?? '';
    $msg_ar = $body['msg_ar'] ?? '';
    $action_note = $body['action_note'] ?? '';
    $stmt->bind_param('sssssssssssi',
        $key, $name, $name_ar, $desc, $color, $text_color, $icon, $priority, $msg_en, $msg_ar, $action_note, $sort
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
    $db->query("UPDATE emergency_codes SET is_active=0 WHERE id=$id AND is_builtin=0");
    echo json_encode(['success'=>true]);
    $db->close(); exit;
}

if ($method === 'PUT') {
    if (!$dbOk) { echo json_encode(['success' => false, 'error' => 'Database offline']); exit; }
    $id   = (int)($_GET['id'] ?? 0);
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $allowed = ['name','name_ar','description','color','text_color','icon','priority','msg_en','msg_ar','action_note','is_active'];
    $sets = [];
    foreach ($allowed as $f) {
        if (isset($body[$f])) $sets[] = "`$f`='" . $db->real_escape_string($body[$f]) . "'";
    }
    if ($sets) $db->query("UPDATE emergency_codes SET " . implode(',', $sets) . " WHERE id=$id");
    echo json_encode(['success'=>true]);
    $db->close(); exit;
}

echo json_encode(['success' => false, 'error' => 'Method not allowed']);
