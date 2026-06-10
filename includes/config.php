<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'droga_pharma');

// ── Auto-detect SITE_URL so spaces in the parent folder name never break CSS/JS paths ──
$_proto    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$_host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_script   = $_SERVER['SCRIPT_NAME'] ?? '';
// Find the 'droga-pharma' segment and build the base path from it
$_parts    = explode('/', trim($_script, '/'));
$_rootIdx  = array_search('droga-pharma', $_parts);
$_basePath = ($_rootIdx !== false)
    ? '/' . implode('/', array_slice($_parts, 0, $_rootIdx + 1))
    : '/droga-pharma';

define('SITE_URL',  $_proto . '://' . $_host . $_basePath);
define('BASE_PATH', $_basePath);

// Site Info
define('SITE_NAME',    'Droga Pharma PLC');
define('SITE_EMAIL',   'info@drogapharma.com');
define('SITE_PHONE',   '+251 11 123 4567');
define('SITE_ADDRESS', 'Addis Ababa, Ethiopia');

// Upload paths
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL',  SITE_URL . '/uploads/');

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
function getDB() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die(json_encode(['error' => 'Database connection failed']));
        }
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

// CSRF Token
function generateCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRF($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Admin auth
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?>
