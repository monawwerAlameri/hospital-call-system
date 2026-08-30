<?php
require_once __DIR__ . '/config.php';
$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

if ($method === 'GET') {
    $rs = $db->query("SELECT * FROM specialties ORDER BY name ASC");
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    echo json_encode(['success'=>true,'data'=>$rows]);
} elseif ($method === 'POST') {
    $b = json_decode(file_get_contents('php://input'), true) ?? [];
    $name = $b['name'] ?? 'Unknown';
    $name_ar = $b['name_ar'] ?? '';
    $code = strtoupper(preg_replace('/[^A-Z]/','', substr($name, 0, 6)));
    $stmt = $db->prepare("INSERT INTO specialties (name,name_ar,code) VALUES (?,?,?)");
    $stmt->bind_param('sss', $name, $name_ar, $code);
    if ($stmt->execute()) echo json_encode(['success'=>true,'id'=>$db->insert_id]);
    else echo json_encode(['success'=>false,'error'=>$db->error]);
} elseif ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    $db->query("DELETE FROM specialties WHERE id=$id");
    echo json_encode(['success'=>true]);
}
$db->close();
