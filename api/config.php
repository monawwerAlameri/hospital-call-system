<?php
// ============================================================
//  HOSPITAL CALL SYSTEM — Database Configuration  v3.1
//  King Khalid Hospital, Hail
//  ----------------------------------------------------------------
//  Reads DB credentials from environment variables (for cloud
//  deployments like Render / Railway / Heroku / shared hosting)
//  and falls back to local dev defaults when env vars are absent.
//  ----------------------------------------------------------------
//  Aiven MySQL requires SSL — set DB_SSL=1 to enable.
//  The CA certificate lives at api/ca.pem (bundled in repo).
//  On Render, env vars take precedence over .env file.
// ============================================================

// Try to load .env file if it exists (for local dev). On Render,
// env vars are already set in the dashboard.
$_ENV_FILE = __DIR__ . '/../.env';
if (is_file($_ENV_FILE) && function_exists('parse_ini_file')) {
    $envVars = @parse_ini_file($_ENV_FILE);
    if (is_array($envVars)) {
        foreach ($envVars as $k => $v) {
            if (getenv($k) === false) putenv("$k=$v");
            if (!isset($_ENV[$k])) $_ENV[$k] = $v;
        }
    }
}

define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: getenv('DB_PASSWORD') ?: 'root');
define('DB_NAME', getenv('DB_NAME') ?: 'hospital_call_system');
define('DB_PORT', (int)(getenv('DB_PORT') ?: 3306));
define('DB_SSL', (getenv('DB_SSL') === '1' || getenv('DB_SSL') === 'true'));
define('DB_CA_CERT', __DIR__ . '/ca.pem');

function getDB() {
    mysqli_report(MYSQLI_REPORT_OFF);

    // Aiven and other managed MySQL providers require SSL.
    // We try SSL first; if it fails, we fall back to a plain connection
    // (some shared hosts don't support SSL).
    $conn = @new mysqli();
    $connected = false;
    $lastError = '';

    if (DB_SSL && is_file(DB_CA_CERT)) {
        // Method 1: SSL with CA verification (most secure, works on Aiven)
        try {
            $conn = @new mysqli();
            $conn->ssl_set(null, null, DB_CA_CERT, null, null);
            @$conn->real_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
            if (!$conn->connect_error) {
                $connected = true;
            } else {
                $lastError = 'SSL-with-CA: ' . $conn->connect_error;
            }
        } catch (\Throwable $e) {
            $lastError = 'SSL-with-CA exception: ' . $e->getMessage();
        }
    }

    if (!$connected) {
        // Method 2: Plain TCP (no SSL) — last resort for hosts without SSL
        $conn = @new mysqli();
        @$conn->real_connect(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
        if ($conn->connect_error) {
            $lastError .= ' | Plain: ' . $conn->connect_error;
            header('Content-Type: application/json; charset=utf-8');
            die(json_encode([
                'success' => false,
                'error' => 'Database offline: ' . $lastError,
                'debug' => [
                    'host' => DB_HOST,
                    'port' => DB_PORT,
                    'user' => DB_USER,
                    'ssl_enabled' => DB_SSL,
                    'ca_cert_exists' => is_file(DB_CA_CERT),
                    'ca_cert_path' => DB_CA_CERT
                ]
            ]));
        }
    }

    // Try to create database; some free hosts (e.g. db4free, Aiven) don't allow CREATE DATABASE,
    // in which case the database must already exist on the server.
    @$conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    if (!$conn->select_db(DB_NAME)) {
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode(['success' => false, 'error' => 'Database "' . DB_NAME . '" not found. Please create it in your hosting panel first.']));
    }
    $conn->set_charset('utf8mb4');

    $tables = [
        "CREATE TABLE IF NOT EXISTS specialties (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100),
            name_ar VARCHAR(100),
            code VARCHAR(20) DEFAULT '',
            is_active TINYINT DEFAULT 1
        )",
        "CREATE TABLE IF NOT EXISTS staff_roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100),
            name_ar VARCHAR(100),
            code VARCHAR(20) DEFAULT '',
            category VARCHAR(50) DEFAULT 'medical',
            default_gender VARCHAR(20) DEFAULT 'any',
            is_active TINYINT DEFAULT 1
        )",
        "CREATE TABLE IF NOT EXISTS locations (
            id INT AUTO_INCREMENT PRIMARY KEY, code VARCHAR(20), name VARCHAR(100), name_ar VARCHAR(100),
            category VARCHAR(50) DEFAULT 'medical', floor VARCHAR(50) DEFAULT '',
            extension VARCHAR(20) DEFAULT '', is_active TINYINT DEFAULT 1
        )",
        "CREATE TABLE IF NOT EXISTS doctors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150), name_ar VARCHAR(150),
            specialty_id INT, level VARCHAR(50), gender VARCHAR(20),
            staff_type VARCHAR(50) DEFAULT 'doctor',
            phone VARCHAR(50), extension VARCHAR(20),
            department_id INT, custom_message TEXT,
            last_paged DATETIME, is_active TINYINT DEFAULT 1
        )",
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150), email VARCHAR(150) UNIQUE,
            password VARCHAR(255), role VARCHAR(50) DEFAULT 'operator',
            gender VARCHAR(20) DEFAULT 'male',
            department VARCHAR(150) DEFAULT '',
            employee_id VARCHAR(50) DEFAULT '',
            phone VARCHAR(50) DEFAULT '',
            is_active TINYINT DEFAULT 1,
            last_login DATETIME NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS system_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            description VARCHAR(255),
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS call_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            call_type VARCHAR(50), code VARCHAR(100), location_name VARCHAR(150),
            specialty_name VARCHAR(150), staff_role_name VARCHAR(150),
            doctor_name VARCHAR(150), extension VARCHAR(30), announced_text TEXT,
            voice_gender VARCHAR(10), initiated_by INT, operator_name VARCHAR(150),
            ip_address VARCHAR(45), status VARCHAR(30) DEFAULT 'sent',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS scheduled_announcements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(200), message_text TEXT, target_role VARCHAR(100),
            target_doctor_id INT, target_location_id INT, voice_gender VARCHAR(10) DEFAULT 'female',
            scheduled_time DATETIME, repeat_type VARCHAR(20) DEFAULT 'once',
            is_active TINYINT DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS emergency_codes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code_key VARCHAR(60) UNIQUE NOT NULL, name VARCHAR(100), name_ar VARCHAR(100),
            description VARCHAR(255), color VARCHAR(20) DEFAULT '#e03131', text_color VARCHAR(20) DEFAULT '#ffffff',
            icon VARCHAR(60) DEFAULT 'fa-exclamation-triangle', priority VARCHAR(20) DEFAULT 'high',
            msg_en TEXT, msg_ar TEXT, action_note TEXT,
            is_builtin TINYINT DEFAULT 0, sort_order INT DEFAULT 99, is_active TINYINT DEFAULT 1
        )",
        "CREATE TABLE IF NOT EXISTS departments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(20) UNIQUE,
            name VARCHAR(150) NOT NULL,
            name_ar VARCHAR(150),
            category VARCHAR(50) DEFAULT 'medical',
            floor VARCHAR(50) DEFAULT '',
            extension VARCHAR(20) DEFAULT '',
            head_name VARCHAR(150) DEFAULT '',
            head_name_ar VARCHAR(150) DEFAULT '',
            head_title VARCHAR(100) DEFAULT '',
            head_title_ar VARCHAR(100) DEFAULT '',
            is_active TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS department_employees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            department_id INT NOT NULL,
            name VARCHAR(150) NOT NULL,
            name_ar VARCHAR(150),
            employee_id VARCHAR(50),
            role VARCHAR(100) DEFAULT 'Staff',
            role_ar VARCHAR(100),
            phone VARCHAR(50),
            extension VARCHAR(20),
            email VARCHAR(150),
            gender VARCHAR(20) DEFAULT 'male',
            is_active TINYINT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_dept (department_id)
        )",
        "CREATE TABLE IF NOT EXISTS department_schedules (
            id INT AUTO_INCREMENT PRIMARY KEY,
            department_id INT NOT NULL,
            schedule_month VARCHAR(20) NOT NULL,
            schedule_year INT NOT NULL,
            title VARCHAR(200),
            title_ar VARCHAR(200),
            shift_definitions TEXT,
            schedule_data LONGTEXT,
            approved_by VARCHAR(150),
            approved_by_ar VARCHAR(150),
            approver_title VARCHAR(150),
            approver_title_ar VARCHAR(150),
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_dept_month (department_id, schedule_month, schedule_year)
        )",
        "CREATE TABLE IF NOT EXISTS shift_timers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            department_id INT NOT NULL,
            employee_name VARCHAR(150),
            employee_name_ar VARCHAR(150),
            shift_type VARCHAR(50),
            start_time DATETIME,
            end_time DATETIME,
            auto_announce TINYINT DEFAULT 1,
            status VARCHAR(20) DEFAULT 'active',
            operation_number VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_dept_status (department_id, status)
        )",
        "CREATE TABLE IF NOT EXISTS handover_records (
            id INT AUTO_INCREMENT PRIMARY KEY,
            department_id INT,
            department_name VARCHAR(150),
            shift_from VARCHAR(50),
            shift_to VARCHAR(50),
            outgoing_staff VARCHAR(150),
            incoming_staff VARCHAR(150),
            notes TEXT,
            priority VARCHAR(20) DEFAULT 'routine',
            status VARCHAR(20) DEFAULT 'pending',
            created_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS quiet_hours_config (
            id INT AUTO_INCREMENT PRIMARY KEY,
            is_enabled TINYINT DEFAULT 0,
            start_time VARCHAR(10) DEFAULT '22:00',
            end_time VARCHAR(10) DEFAULT '06:00',
            repeat_days VARCHAR(100) DEFAULT 'Sun,Mon,Tue,Wed,Thu',
            allowed_codes TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS tv_board_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            message_en TEXT,
            message_ar TEXT,
            priority VARCHAR(20) DEFAULT 'normal',
            duration INT DEFAULT 60,
            is_active TINYINT DEFAULT 1,
            expires_at DATETIME,
            created_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        // v3.1 — Visit Hours configuration (single-row table)
        "CREATE TABLE IF NOT EXISTS visit_hours_config (
            id INT AUTO_INCREMENT PRIMARY KEY,
            is_enabled TINYINT DEFAULT 1,
            start_time VARCHAR(10) DEFAULT '16:00',
            end_time VARCHAR(10) DEFAULT '20:00',
            start_msg_ar TEXT,
            end_msg_ar TEXT,
            warn_msg_ar TEXT,
            start_msg_en TEXT,
            end_msg_en TEXT,
            warn_msg_en TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $sql) { $conn->query($sql); }

    // Upgrade specialties if missing columns
    foreach (['code VARCHAR(20) DEFAULT \'\'', 'is_active TINYINT DEFAULT 1'] as $colDef) {
        $colName = explode(' ', $colDef)[0];
        $chk = $conn->query("SHOW COLUMNS FROM specialties LIKE '$colName'");
        if ($chk && $chk->num_rows === 0) {
            $conn->query("ALTER TABLE specialties ADD COLUMN $colDef");
        }
    }

    // Upgrade users table if missing columns
    foreach (['gender VARCHAR(20) DEFAULT \'male\'', 'department VARCHAR(150) DEFAULT \'\'', 'employee_id VARCHAR(50) DEFAULT \'\'', 'phone VARCHAR(50) DEFAULT \'\'', 'is_active TINYINT DEFAULT 1', 'last_login DATETIME NULL', 'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP'] as $uColDef) {
        $uColName = explode(' ', $uColDef)[0];
        $uChk = $conn->query("SHOW COLUMNS FROM users LIKE '$uColName'");
        if ($uChk && $uChk->num_rows === 0) {
            $conn->query("ALTER TABLE users ADD COLUMN $uColDef");
        }
    }

    // Upgrade doctors
    $colCheck = $conn->query("SHOW COLUMNS FROM doctors LIKE 'staff_type'");
    if ($colCheck && $colCheck->num_rows === 0) {
        $conn->query("ALTER TABLE doctors ADD COLUMN staff_type VARCHAR(30) DEFAULT 'doctor' AFTER gender");
    }
    foreach (['category VARCHAR(50) DEFAULT \'medical\'', 'floor VARCHAR(50) DEFAULT \'\'', 'extension VARCHAR(20) DEFAULT \'\'', 'is_active TINYINT DEFAULT 1'] as $colDef) {
        $colName = explode(' ', $colDef)[0];
        $chk = $conn->query("SHOW COLUMNS FROM locations LIKE '$colName'");
        if ($chk && $chk->num_rows === 0) {
            $conn->query("ALTER TABLE locations ADD COLUMN $colDef");
        }
    }

    // Migrate old 'codes' table to 'emergency_codes' if needed
    $oldTable = $conn->query("SHOW TABLES LIKE 'codes'");
    if ($oldTable && $oldTable->num_rows > 0) {
        $newTable = $conn->query("SELECT COUNT(*) as c FROM emergency_codes");
        if ($newTable) {
            $cnt = $newTable->fetch_assoc()['c'];
            if ($cnt == 0) {
                $conn->query("INSERT INTO emergency_codes SELECT * FROM codes");
            }
        }
    }

    // Seed demo data
    $res = $conn->query("SELECT COUNT(*) as c FROM doctors");
    if ($res && $res->fetch_assoc()['c'] == 0) {
        $conn->query("INSERT IGNORE INTO locations (id, code, name, name_ar) VALUES (1, 'ER', 'Emergency Room', 'الطوارئ'), (2, 'ICU', 'Intensive Care', 'العناية المركزة'), (3, 'SUR', 'Surgery', 'الجراحة')");
        $conn->query("INSERT IGNORE INTO specialties (id, name, name_ar) VALUES (1, 'Cardiology', 'القلبية'), (2, 'Neurology', 'الأعصاب'), (3, 'Pediatrics', 'الأطفال'), (4, 'Internal Medicine', 'الباطنية'), (5, 'Orthopedics', 'العظام'), (6, 'General Surgery', 'الجراحة العامة')");
        $conn->query("INSERT IGNORE INTO doctors (name, name_ar, specialty_id, level, gender, staff_type, department_id, is_active) VALUES 
            ('Dr. Ahmed Al-Ghamdi', 'د. أحمد الغامدي', 1, 'Consultant', 'male', 'doctor', 1, 1),
            ('Dr. Fatima Al-Zahrani', 'د. فاطمة الزهراني', 2, 'Specialist', 'female', 'doctor', 2, 1),
            ('Dr. Mohammed Al-Otaibi', 'د. محمد العتيبي', 4, 'Consultant', 'male', 'doctor', 1, 1),
            ('Sara Al-Qahtani', 'سارة القحطاني', 3, 'Specialist', 'female', 'nurse', 2, 1),
            ('Khaled Al-Harbi', 'خالد الحربي', 2, 'Resident', 'male', 'technician', 3, 1)");
    }

    // Seed staff_roles
    $srRes = $conn->query("SELECT COUNT(*) as c FROM staff_roles");
    if ($srRes && $srRes->fetch_assoc()['c'] == 0) {
        $conn->query("INSERT INTO staff_roles (name, name_ar, code, category, default_gender) VALUES 
            ('Security', 'الأمن', 'SEC', 'admin', 'male'),
            ('Housekeeping', 'النظافة', 'HSK', 'support', 'any'),
            ('Maintenance', 'الصيانة', 'MNT', 'support', 'male'),
            ('Pharmacist', 'الصيدلي', 'PHR', 'medical', 'any'),
            ('Lab Technician', 'فني مختبر', 'LAB', 'medical', 'any'),
            ('Radiology Tech', 'فني أشعة', 'RAD', 'medical', 'any'),
            ('Social Worker', 'الأخصائي الاجتماعي', 'SOC', 'admin', 'any'),
            ('Dietitian', 'أخصائي تغذية', 'DIT', 'medical', 'any')");
    }

    // Seed emergency codes (v3.1 format: "Code <Name> in {loc}" — no "activated")
    $ecRes = $conn->query("SELECT COUNT(*) as c FROM emergency_codes");
    if ($ecRes && $ecRes->fetch_assoc()['c'] == 0) {
        $conn->query("INSERT INTO emergency_codes (code_key, name, name_ar, description, color, text_color, icon, priority, msg_en, msg_ar, action_note, is_builtin, sort_order) VALUES
            ('CODE_BLUE', 'Code Blue', 'كود أزرق', 'Cardiac/Respiratory Arrest', '#2563eb', '#ffffff', 'fa-heartbeat', 'critical', 'Code Blue in {loc}. Medical emergency team, respond immediately.', 'كود أزرق في {loc_ar}. فريق الطوارئ الطبية، الاستجابة فورًا.', 'Crash team respond immediately, bring crash cart and defibrillator', 1, 1),
            ('CODE_RED', 'Code Red', 'كود أحمر', 'Fire Emergency', '#dc2626', '#ffffff', 'fa-fire', 'critical', 'Code Red in {loc}. Fire emergency. Evacuate area and call security.', 'كود أحمر في {loc_ar}. حالة حريق طارئة. إخلاء المنطقة واستدعاء الأمن.', 'Evacuate area, call fire department 998, use extinguishers', 1, 2),
            ('CODE_BLACK', 'Code Black', 'كود أسود', 'Bomb Threat', '#1e1b4b', '#ffffff', 'fa-skull-crossbones', 'critical', 'Code Black in {loc}. Bomb threat. Evacuate area immediately.', 'كود أسود في {loc_ar}. تهديد بوجود قنبلة. إخلاء المنطقة فورًا.', 'Do not touch, evacuate area, notify police 999 immediately', 1, 3),
            ('CODE_PINK', 'Code Pink', 'كود وردي', 'Infant/Child Abduction', '#ec4899', '#ffffff', 'fa-baby', 'critical', 'Code Pink in {loc}. Infant alert. Security lock down exits.', 'كود وردي في {loc_ar}. تنبيه اختطاف طفل. الأمن يغلق المخارج فورًا.', 'Lock all exits, check all persons leaving, call security', 1, 4),
            ('CODE_WHITE', 'Code White', 'كود أبيض', 'Violent/Aggressive Patient', '#f8fafc', '#1e293b', 'fa-hand-fist', 'high', 'Code White in {loc}. Security team, respond immediately.', 'كود أبيض في {loc_ar}. فريق الأمن، الاستجابة فورًا.', 'Security contain situation, do not approach alone', 1, 5),
            ('CODE_YELLOW', 'Code Yellow', 'كود أصفر', 'Missing Patient', '#eab308', '#1e293b', 'fa-person-walking', 'high', 'Code Yellow in {loc}. Missing patient. All staff be on alert.', 'كود أصفر في {loc_ar}. مريض مفقود. جميع الكوادر في حالة تأهب.', 'Search all areas, check CCTV, notify all security', 1, 6),
            ('CODE_RRT', 'Rapid Response', 'فريق الاستجابة السريعة', 'Rapid Response Team', '#7c3aed', '#ffffff', 'fa-bolt', 'high', 'Rapid Response Team in {loc}. R R T team, respond immediately.', 'فريق الاستجابة السريعة في {loc_ar}. الاستجابة فورًا.', 'RRT team respond with equipment including crash cart', 1, 7)");
    } else {
        // v3.1 — Migrate existing emergency_codes rows from old "activated" format to new "in {loc}" format
        $ecRes2 = $conn->query("SELECT id, code_key, msg_en, msg_ar FROM emergency_codes");
        if ($ecRes2) {
            while ($row = $ecRes2->fetch_assoc()) {
                $msgEn = $row['msg_en'];
                $msgAr = $row['msg_ar'];
                $needsUpdate = false;
                $newEn = $msgEn;
                $newAr = $msgAr;

                // If msg_en contains "activated" or doesn't contain "in {loc}", update it
                if (stripos($msgEn, 'activated') !== false || stripos($msgEn, 'in {loc}') === false) {
                    $codeName = $row['code_key'];
                    $parts = explode('_', $codeName);
                    array_shift($parts);
                    $friendly = count($parts) > 0 ? 'Code ' . implode(' ', array_map('ucfirst', array_map('strtolower', $parts))) : $codeName;
                    if ($codeName === 'CODE_RRT') $friendly = 'Rapid Response Team';
                    $newEn = $friendly . ' in {loc}. ' . ($friendly === 'Rapid Response Team' ? 'R R T team, respond immediately.' : 'All staff respond immediately.');
                    $needsUpdate = true;
                }
                if (stripos($msgAr, 'تفعيل') !== false || stripos($msgAr, 'في {loc_ar}') === false) {
                    $codeName = $row['code_key'];
                    $parts = explode('_', $codeName);
                    array_shift($parts);
                    $friendlyAr = count($parts) > 0 ? 'كود ' . implode(' ', array_map('ucfirst', array_map('strtolower', $parts))) : $codeName;
                    if ($codeName === 'CODE_RRT') $friendlyAr = 'فريق الاستجابة السريعة';
                    $newAr = $friendlyAr . ' في {loc_ar}. الاستجابة فورًا.';
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    $stmtUpd = $conn->prepare("UPDATE emergency_codes SET msg_en = ?, msg_ar = ? WHERE id = ?");
                    $stmtUpd->bind_param('ssi', $newEn, $newAr, $row['id']);
                    $stmtUpd->execute();
                }
            }
        }
    }

    // v3.1 — Seed visit_hours_config with default Arabic announcement messages
    $vhRes = $conn->query("SELECT COUNT(*) as c FROM visit_hours_config");
    if ($vhRes && $vhRes->fetch_assoc()['c'] == 0) {
        $conn->query("INSERT INTO visit_hours_config (is_enabled, start_time, end_time, start_msg_ar, end_msg_ar, warn_msg_ar, start_msg_en, end_msg_en, warn_msg_en) VALUES (
            1,
            '16:00',
            '20:00',
            'بدأت ساعات الزيارة. يرجى من الزوار التوجه إلى الأقسام المخصصة.',
            'انتهت ساعات الزيارة. يرجى من الزوار مغادرة المستشفى. شاكرين لكم تفهمكم.',
            'تنتهي ساعات الزيارة خلال 10 دقائق. يرجى من الزوار الاستعداد للمغادرة.',
            'Visiting hours have begun. Visitors may proceed to the designated wards.',
            'Visiting hours have ended. Visitors are kindly requested to leave the hospital. Thank you.',
            'Visiting hours will end in 10 minutes. Visitors are kindly requested to prepare to leave.'
        )");
    }

    // Seed departments
    $dRes = $conn->query("SELECT COUNT(*) as c FROM departments");
    if ($dRes && $dRes->fetch_assoc()['c'] == 0) {
        $conn->query("INSERT INTO departments (code, name, name_ar, category, floor) VALUES
            ('ER', 'Emergency Room', 'قسم الطوارئ', 'medical', 'Ground Floor'),
            ('DLY', 'Dialysis Unit', 'وحدة الغسيل الكلوي', 'medical', '1st Floor'),
            ('CCU', 'Coronary Care Unit', 'وحدة عناية القلب', 'medical', '2nd Floor'),
            ('ADM', 'Administration', 'الإدارة', 'admin', '5th Floor'),
            ('LOB', 'Main Lobby', 'البهو الرئيسي', 'general', 'Ground Floor'),
            ('LAB', 'Laboratory', 'المختبر', 'medical', 'Ground Floor'),
            ('ICU', 'Intensive Care Unit', 'وحدة العناية المركزة', 'medical', '2nd Floor'),
            ('FMW', 'Female Medical Ward', 'الجناح الطبي النسائي', 'medical', '4th Floor'),
            ('OPC', 'Outpatient Clinics', 'العيادات الخارجية', 'medical', '1st Floor'),
            ('OR', 'Operating Room', 'غرفة العمليات', 'medical', '2nd Floor'),
            ('NICU', 'Neonatal ICU', 'وحدة عناية حديثي الولادة', 'medical', '3rd Floor'),
            ('MMW', 'Male Medical Ward', 'الجناح الطبي الرجالي', 'medical', '3rd Floor'),
            ('RAD', 'Radiology Department', 'قسم الأشعة', 'medical', 'Ground Floor')");
    }

    // Create default admin
    $uRes = $conn->query("SELECT id FROM users WHERE email='admin@hospital.sa'");
    if ($uRes && $uRes->num_rows == 0) {
        $conn->query("INSERT INTO users (name, email, password, role) VALUES ('Administrator', 'admin@hospital.sa', '" . password_hash("Admin@1234", PASSWORD_DEFAULT) . "', 'admin')");
    }

    return $conn;
}

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_name('HCS_SESSION');
    session_start();
}

function requireAuth() {
    if (empty($_SESSION['user_id'])) {
        http_response_code(401);
        die(json_encode(['success' => false, 'error' => 'Unauthorized']));
    }
}

function sanitize($str) {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}
