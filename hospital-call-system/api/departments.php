<?php
require_once 'config.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch($action) {
    case 'list':
        $r = $db->query("SELECT d.*, (SELECT COUNT(*) FROM department_employees e WHERE e.department_id=d.id AND e.is_active=1) as employee_count FROM departments d WHERE d.is_active=1 ORDER BY d.name");
        $rows = [];
        while($row = $r->fetch_assoc()) $rows[] = $row;
        echo json_encode(['success'=>true,'data'=>$rows]);
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $r = $db->query("SELECT * FROM departments WHERE id=$id");
        $dept = $r->fetch_assoc();
        $emps = [];
        $er = $db->query("SELECT * FROM department_employees WHERE department_id=$id AND is_active=1 ORDER BY name");
        while($e = $er->fetch_assoc()) $emps[] = $e;
        echo json_encode(['success'=>true,'department'=>$dept,'employees'=>$emps]);
        break;

    case 'create':
        $d = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("INSERT INTO departments (code,name,name_ar,category,floor,extension,head_name,head_name_ar,head_title,head_title_ar) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssssssss", $d['code'],$d['name'],$d['name_ar'],$d['category'],$d['floor'],$d['extension'],$d['head_name'],$d['head_name_ar'],$d['head_title'],$d['head_title_ar']);
        $stmt->execute();
        echo json_encode(['success'=>true,'id'=>$db->insert_id]);
        break;

    case 'update':
        $d = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("UPDATE departments SET code=?,name=?,name_ar=?,category=?,floor=?,extension=?,head_name=?,head_name_ar=?,head_title=?,head_title_ar=? WHERE id=?");
        $stmt->bind_param("ssssssssssi", $d['code'],$d['name'],$d['name_ar'],$d['category'],$d['floor'],$d['extension'],$d['head_name'],$d['head_name_ar'],$d['head_title'],$d['head_title_ar'],$d['id']);
        $stmt->execute();
        echo json_encode(['success'=>true]);
        break;

    case 'delete':
        $id = intval($_GET['id'] ?? 0);
        $db->query("UPDATE departments SET is_active=0 WHERE id=$id");
        echo json_encode(['success'=>true]);
        break;

    case 'employees':
        $did = intval($_GET['department_id'] ?? 0);
        $r = $db->query("SELECT * FROM department_employees WHERE department_id=$did AND is_active=1 ORDER BY name");
        $rows = [];
        while($row = $r->fetch_assoc()) $rows[] = $row;
        echo json_encode(['success'=>true,'data'=>$rows]);
        break;

    case 'add_employee':
        $d = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("INSERT INTO department_employees (department_id,name,name_ar,employee_id,role,role_ar,phone,extension,email,gender) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("isssssssss", $d['department_id'],$d['name'],$d['name_ar'],$d['employee_id'],$d['role'],$d['role_ar'],$d['phone'],$d['extension'],$d['email'],$d['gender']);
        $stmt->execute();
        echo json_encode(['success'=>true,'id'=>$db->insert_id]);
        break;

    case 'update_employee':
        $d = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("UPDATE department_employees SET name=?,name_ar=?,employee_id=?,role=?,role_ar=?,phone=?,extension=?,email=?,gender=? WHERE id=?");
        $stmt->bind_param("sssssssssi", $d['name'],$d['name_ar'],$d['employee_id'],$d['role'],$d['role_ar'],$d['phone'],$d['extension'],$d['email'],$d['gender'],$d['id']);
        $stmt->execute();
        echo json_encode(['success'=>true]);
        break;

    case 'delete_employee':
        $id = intval($_GET['id'] ?? 0);
        $db->query("UPDATE department_employees SET is_active=0 WHERE id=$id");
        echo json_encode(['success'=>true]);
        break;

    case 'save_schedule':
        $d = json_decode(file_get_contents('php://input'), true);
        $stmt_check = $db->prepare("SELECT id FROM department_schedules WHERE department_id=? AND schedule_month=? AND schedule_year=?");
        $dept_id = intval($d['department_id']);
        $smonth = $d['schedule_month'] ?? '';
        $syear = intval($d['schedule_year']);
        $stmt_check->bind_param("isi", $dept_id, $smonth, $syear);
        $stmt_check->execute();
        $ex = $stmt_check->get_result();
        if ($ex && $ex->num_rows > 0) {
            $sid = $ex->fetch_assoc()['id'];
            $stmt = $db->prepare("UPDATE department_schedules SET title=?,title_ar=?,shift_definitions=?,schedule_data=?,approved_by=?,approved_by_ar=?,approver_title=?,approver_title_ar=?,notes=? WHERE id=?");
            if (!$stmt) { echo json_encode(['success'=>false,'error'=>$db->error]); break; }
            $stmt->bind_param("sssssssssi", $d['title'],$d['title_ar'],$d['shift_definitions'],$d['schedule_data'],$d['approved_by'],$d['approved_by_ar'],$d['approver_title'],$d['approver_title_ar'],$d['notes'],$sid);
            if (!$stmt->execute()) { echo json_encode(['success'=>false,'error'=>$stmt->error]); break; }
            echo json_encode(['success'=>true,'id'=>$sid]);
        } else {
            $stmt = $db->prepare("INSERT INTO department_schedules (department_id,schedule_month,schedule_year,title,title_ar,shift_definitions,schedule_data,approved_by,approved_by_ar,approver_title,approver_title_ar,notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("isisssssssss", $d['department_id'],$d['schedule_month'],$d['schedule_year'],$d['title'],$d['title_ar'],$d['shift_definitions'],$d['schedule_data'],$d['approved_by'],$d['approved_by_ar'],$d['approver_title'],$d['approver_title_ar'],$d['notes']);
            $stmt->execute();
            echo json_encode(['success'=>true,'id'=>$db->insert_id]);
        }
        break;

    case 'get_schedule':
        $did = intval($_GET['department_id'] ?? 0);
        $month = $db->real_escape_string($_GET['month'] ?? '');
        $year = intval($_GET['year'] ?? date('Y'));
        $r = $db->query("SELECT * FROM department_schedules WHERE department_id=$did AND schedule_month='$month' AND schedule_year=$year");
        $sched = $r ? $r->fetch_assoc() : null;
        echo json_encode(['success'=>true,'schedule'=>$sched]);
        break;

    case 'list_schedules':
        $did = intval($_GET['department_id'] ?? 0);
        $r = $db->query("SELECT id,department_id,schedule_month,schedule_year,title,title_ar,created_at FROM department_schedules WHERE department_id=$did ORDER BY schedule_year DESC, schedule_month DESC");
        $rows = [];
        while($row = $r->fetch_assoc()) $rows[] = $row;
        echo json_encode(['success'=>true,'data'=>$rows]);
        break;

    case 'save_shift_timer':
        $d = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("INSERT INTO shift_timers (department_id,employee_name,employee_name_ar,shift_type,start_time,end_time,auto_announce,operation_number) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("isssssis", $d['department_id'],$d['employee_name'],$d['employee_name_ar'],$d['shift_type'],$d['start_time'],$d['end_time'],$d['auto_announce'],$d['operation_number']);
        $stmt->execute();
        echo json_encode(['success'=>true,'id'=>$db->insert_id]);
        break;

    case 'active_timers':
        $r = $db->query("SELECT t.*, d.name as dept_name, d.name_ar as dept_name_ar FROM shift_timers t LEFT JOIN departments d ON d.id=t.department_id WHERE t.status='active' AND t.end_time > NOW() ORDER BY t.end_time ASC");
        $rows = [];
        while($row = $r->fetch_assoc()) $rows[] = $row;
        echo json_encode(['success'=>true,'data'=>$rows]);
        break;

    case 'save_handover':
        $d = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("INSERT INTO handover_records (department_id,department_name,shift_from,shift_to,outgoing_staff,incoming_staff,notes,priority) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("isssssss", $d['department_id'],$d['department_name'],$d['shift_from'],$d['shift_to'],$d['outgoing_staff'],$d['incoming_staff'],$d['notes'],$d['priority']);
        $stmt->execute();
        echo json_encode(['success'=>true,'id'=>$db->insert_id]);
        break;

    case 'handover_list':
        $r = $db->query("SELECT * FROM handover_records ORDER BY created_at DESC LIMIT 50");
        $rows = [];
        while($row = $r->fetch_assoc()) $rows[] = $row;
        echo json_encode(['success'=>true,'data'=>$rows]);
        break;

    case 'save_quiet_hours':
        $d = json_decode(file_get_contents('php://input'), true);
        $db->query("DELETE FROM quiet_hours_config");
        $stmt = $db->prepare("INSERT INTO quiet_hours_config (is_enabled,start_time,end_time,repeat_days,allowed_codes) VALUES (?,?,?,?,?)");
        $stmt->bind_param("issss", $d['is_enabled'],$d['start_time'],$d['end_time'],$d['repeat_days'],$d['allowed_codes']);
        $stmt->execute();
        echo json_encode(['success'=>true]);
        break;

    case 'get_quiet_hours':
        $r = $db->query("SELECT * FROM quiet_hours_config LIMIT 1");
        $config = $r ? $r->fetch_assoc() : null;
        echo json_encode(['success'=>true,'config'=>$config]);
        break;

    case 'save_tv_message':
        $d = json_decode(file_get_contents('php://input'), true);
        $expires = $d['duration'] > 0 ? "DATE_ADD(NOW(), INTERVAL {$d['duration']} SECOND)" : "DATE_ADD(NOW(), INTERVAL 24 HOUR)";
        $db->query("INSERT INTO tv_board_messages (message_en,message_ar,priority,duration,expires_at) VALUES ('{$db->real_escape_string($d['message_en'])}','{$db->real_escape_string($d['message_ar'])}','{$db->real_escape_string($d['priority'])}',{$d['duration']},$expires)");
        echo json_encode(['success'=>true,'id'=>$db->insert_id]);
        break;

    case 'tv_messages':
        $r = $db->query("SELECT * FROM tv_board_messages WHERE is_active=1 AND (expires_at > NOW() OR duration=0) ORDER BY created_at DESC LIMIT 10");
        $rows = [];
        while($row = $r->fetch_assoc()) $rows[] = $row;
        echo json_encode(['success'=>true,'data'=>$rows]);
        break;

    case 'search':
        $q = $db->real_escape_string($_GET['q'] ?? '');
        $results = [];
        $dr = $db->query("SELECT id,'employee' as type,name,name_ar,role FROM department_employees WHERE is_active=1 AND (name LIKE '%$q%' OR name_ar LIKE '%$q%' OR employee_id LIKE '%$q%') LIMIT 10");
        while($row = $dr->fetch_assoc()) $results[] = $row;
        $dp = $db->query("SELECT id,'department' as type,name,name_ar,code FROM departments WHERE is_active=1 AND (name LIKE '%$q%' OR name_ar LIKE '%$q%' OR code LIKE '%$q%') LIMIT 10");
        while($row = $dp->fetch_assoc()) $results[] = $row;
        $dc = $db->query("SELECT id,'doctor' as type,name,name_ar,level as role FROM doctors WHERE is_active=1 AND (name LIKE '%$q%' OR name_ar LIKE '%$q%') LIMIT 10");
        while($row = $dc->fetch_assoc()) $results[] = $row;
        echo json_encode(['success'=>true,'data'=>$results]);
        break;

    default:
        echo json_encode(['success'=>false,'error'=>'Unknown action']);
}
$db->close();
