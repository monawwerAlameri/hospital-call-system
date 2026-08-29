<?php
require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();

if ($method === 'GET') {
    $rs = $db->query("SELECT * FROM emergency_codes WHERE is_active=1 ORDER BY sort_order ASC, id ASC");
    $rows = [];
    if ($rs) { while ($r = $rs->fetch_assoc()) $rows[] = $r; }
    echo json_encode(['success' => true, 'data' => $rows]);
    $db->close(); exit;
}

if ($method === 'POST') {
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
    $id = (int)($_GET['id'] ?? 0);
    $db->query("UPDATE emergency_codes SET is_active=0 WHERE id=$id AND is_builtin=0");
    echo json_encode(['success'=>true]);
    $db->close(); exit;
}

if ($method === 'PUT') {
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
