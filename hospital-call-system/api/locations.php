<?php
// ============================================================
//  API: Locations (Departments)  — CRUD
//  /api/locations.php
// ============================================================
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();

if ($method === 'GET') {
    $rs = $db->query("SELECT * FROM locations WHERE is_active=1 ORDER BY name ASC");
    $rows = [];
    while ($r = $rs->fetch_assoc()) $rows[] = $r;
    echo json_encode(['success'=>true,'data'=>$rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
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
    $id = (int)($_GET['id'] ?? 0);
    $db->query("UPDATE locations SET is_active=0 WHERE id=$id");
    echo json_encode(['success'=>true]);
    $db->close(); exit;
}

if ($method === 'PUT') {
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
