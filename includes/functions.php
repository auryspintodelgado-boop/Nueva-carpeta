<?php
/**
 * Utility Functions
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

/**
 * Get base URL
 * @return string
 */
function base_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . ($script !== '/' ? $script : '');
}

/**
 * Redirect to URL
 * @param string $url
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Redirect to dashboard based on user role
 */
function redirect_to_dashboard() {
    $role = $_SESSION['role'] ?? '';
    switch ($role) {
        case 'admin':
            redirect('admin/index.php');
            break;
        case 'director':
            redirect('directors/index.php');
            break;
        case 'employee':
            redirect('employees/index.php');
            break;
        default:
            redirect('auth/login.php');
    }
}

/**
 * Show JSON response
 * @param mixed $data
 * @param int $statusCode
 */
function json_response($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Show error message
 * @param string $message
 * @param string $type
 */
function show_message($message, $type = 'danger') {
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
            ' . $message . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
}

/**
 * Format date for display
 * @param string $date
 * @return string
 */
function format_date($date) {
    if (empty($date)) return '';
    return date('d/m/Y', strtotime($date));
}

/**
 * Get status badge HTML
 * @param string $status
 * @return string
 */
function get_status_badge($status) {
    $badges = [
        'pending' => '<span class="status-badge status-pending"><i class="bi bi-clock"></i> Pendiente</span>',
        'verified' => '<span class="status-badge status-verified"><i class="bi bi-check-circle"></i> Verificado</span>',
        'rejected' => '<span class="status-badge status-rejected"><i class="bi bi-x-circle"></i> Rechazado</span>'
    ];
    return $badges[$status] ?? $status;
}

/**
 * Get evaluation score label
 * @param int $score
 * @return string
 */
function get_score_label($score) {
    $labels = [
        1 => '<span class="text-danger">Muy Bajo</span>',
        2 => '<span class="text-warning">Bajo</span>',
        3 => '<span class="text-info">Moderado</span>',
        4 => '<span class="text-primary">Alto</span>',
        5 => '<span class="text-success">Muy Alto</span>'
    ];
    return $labels[$score] ?? $score;
}

/**
 * Calculate age from birthdate
 * @param string $birthdate
 * @return int|null
 */
function calculate_age($birthdate) {
    if (empty($birthdate)) return null;
    $birth = new DateTime($birthdate);
    $today = new DateTime('today');
    return $birth->diff($today)->y;
}

/**
 * Get months array
 * @return array
 */
function get_months() {
    return [
        '01' => 'Enero',
        '02' => 'Febrero',
        '03' => 'Marzo',
        '04' => 'Abril',
        '05' => 'Mayo',
        '06' => 'Junio',
        '07' => 'Julio',
        '08' => 'Agosto',
        '09' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre'
    ];
}

/**
 * Get blood types
 * @return array
 */
function get_blood_types() {
    return ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
}

/**
 * Validate required fields
 * @param array $data
 * @param array $required
 * @return array [bool, array]
 */
function validate_required($data, $required) {
    $errors = [];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            $errors[] = "El campo $field es requerido";
        }
    }
    return [empty($errors), $errors];
}

/**
 * Generate random password
 * @param int $length
 * @return string
 */
function generate_password($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    return substr(str_shuffle($chars), 0, $length);
}

/**
 * Log activity
 * @param string $action
 * @param int $userId
 * @param string $details
 */
function log_activity($action, $userId, $details = '') {
    $conn = getDBConnection();
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details, ip_address) VALUES (?, ?, ?, ?)");
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt->bind_param("isss", $userId, $action, $details, $ip);
    $stmt->execute();
    $stmt->close();
}
