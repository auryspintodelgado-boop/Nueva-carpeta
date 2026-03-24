<?php
/**
 * Database Connection Configuration
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'aurys_hr');

/**
 * Get database connection
 * @return mysqli|null
 */
function getDBConnection() {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

/**
 * Start session if not started
 */
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Check if user is logged in
 * @return bool
 */
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

/**
 * Get current user role
 * @return string|null
 */
function getUserRole() {
    startSession();
    return $_SESSION['role'] ?? null;
}

/**
 * Get current user ID
 * @return int|null
 */
function getUserId() {
    startSession();
    return $_SESSION['user_id'] ?? null;
}

/**
 * Require login - redirect to login if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: auth/login.php');
        exit;
    }
}

/**
 * Require specific role
 * @param string|array $roles Allowed roles
 */
function requireRole($roles) {
    requireLogin();
    
    $userRole = getUserRole();
    
    if (is_array($roles)) {
        if (!in_array($userRole, $roles)) {
            header('Location: index.php?error=Unauthorized');
            exit;
        }
    } else {
        if ($userRole !== $roles) {
            header('Location: index.php?error=Unauthorized');
            exit;
        }
    }
}

/**
 * Sanitize input
 * @param string $input
 * @return string
 */
function sanitize($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Get current page title
 * @param string $title
 * @return string
 */
function getPageTitle($title = '') {
    return $title ? $title . ' - AURYS HR' : 'AURYS - Sistema de Gestión de Recursos Humanos';
}
