<?php
// ============================================================
//  API: Authentication (JSON body support)
//  /api/auth.php
// ============================================================
require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

// Support both JSON body and POST form
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$post = array_merge($_POST, $body);

$action = $post['action'] ?? $_GET['action'] ?? '';

// ── LOGIN ────────────────────────────────────────────────────
if ($action === 'login') {
    $email    = sanitize($post['email'] ?? '');
    $password = $post['password'] ?? '';

    if (!$email || !$password) {
        echo json_encode(['success' => false, 'error' => 'Email and password are required']);
        exit;
    }

    $db   = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $db->query("UPDATE users SET last_login = NOW() WHERE id = {$user['id']}");
        unset($user['password']);
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid email or password']);
    }
    $db->close(); exit;
}

// ── REGISTER ─────────────────────────────────────────────────
if ($action === 'register') {
    $name       = sanitize($post['name']       ?? '');
    $email      = sanitize($post['email']      ?? '');
    $password   = $post['password']            ?? '';
    $gender     = sanitize($post['gender']     ?? 'male');
    $department = sanitize($post['department'] ?? '');
    $phone      = sanitize($post['phone']      ?? '');
    $role       = 'operator';

    if (!$name || !$email || !$password) {
        echo json_encode(['success' => false, 'error' => 'Name, email, and password are required']);
        exit;
    }
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters']);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    $db = getDB();

    $check = $db->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->bind_param('s', $email);
    $check->execute();
    if ($check->get_result()->fetch_assoc()) {
        echo json_encode(['success' => false, 'error' => 'Email already registered']);
        $db->close(); exit;
    }

    $empId = 'EMP' . strtoupper(substr(md5($email),0,6));
    $stmt  = $db->prepare("INSERT INTO users (name,email,password,role,gender,department,employee_id,phone) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssssss', $name, $email, $hashed, $role, $gender, $department, $empId, $phone);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Account created successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Registration failed: ' . $db->error]);
    }
    $db->close(); exit;
}

// ── LOGOUT ───────────────────────────────────────────────────
if ($action === 'logout') {
    $_SESSION = []; session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

// ── SESSION CHECK ────────────────────────────────────────────
if ($action === 'check') {
    if (!empty($_SESSION['user_id'])) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id,name,email,role,gender,department FROM users WHERE id=? LIMIT 1");
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $db->close();
        echo json_encode(['success' => true, 'logged_in' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => true, 'logged_in' => false]);
    }
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid action']);