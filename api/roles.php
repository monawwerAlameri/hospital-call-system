<?php
require_once __DIR__ . '/config.php';
$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

if ($method === 'GET') {
    $rs = $db->query("SELECT * FROM staff_roles WHERE is_active=1 ORDER BY name ASC");
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    echo json_encode(['success'=>true,'data'=>$rows]);
} elseif ($method === 'POST') {
    $b = json_decode(file_get_contents('php://input'), true) ?? [];
    $name = $b['name'] ?? 'Unknown';
    $name_ar = $b['name_ar'] ?? '';
    $code = strtoupper(preg_replace('/[^A-Z]/','', substr($name, 0, 8)));
    $category = $b['category'] ?? 'medical';
    $default_gender = $b['default_gender'] ?? 'any';
    $stmt = $db->prepare("INSERT INTO staff_roles (name,name_ar,code,category,default_gender) VALUES (?,?,?,?,?)");
    $stmt->bind_param('sssss', $name, $name_ar, $code, $category, $default_gender);
    if ($stmt->execute()) echo json_encode(['success'=>true,'id'=>$db->insert_id]);
    else echo json_encode(['success'=>false,'error'=>$db->error]);
} elseif ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    $db->query("UPDATE staff_roles SET is_active=0 WHERE id=$id");
    echo json_encode(['success'=>true]);
}
$db->close();
