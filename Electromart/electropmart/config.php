<?php
// Configuration file for Electropmart
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'electropmart');

define('SITE_URL', 'http://localhost/electropmart/');
define('SITE_NAME', 'Electropmart');
define('SITE_EMAIL', 'admin@electropmart.com');

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session configuration
session_start();
ini_set('session.gc_maxlifetime', 86400);

// Connect to database
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}

// Helper functions
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars(strip_tags($data)));
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function password_hash_custom($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function password_verify_custom($password, $hash) {
    return password_verify($password, $hash);
}

function is_user_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function get_user_role() {
    return $_SESSION['user_role'] ?? 'customer';
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function format_price($price) {
    return '$' . number_format((float)$price, 2, '.', ',');
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function get_cart_count() {
    if (!is_user_logged_in()) {
        return isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
    }
    
    global $conn;
    $user_id = get_user_id();
    $result = $conn->query("SELECT COUNT(*) as count FROM cart WHERE user_id = $user_id");
    $row = $result->fetch_assoc();
    return $row['count'];
}
?>
