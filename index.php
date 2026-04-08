<?php
/**
 * Index - Redirect to appropriate dashboard
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

startSession();

if (isLoggedIn()) {
    $role = getUserRole();
    switch ($role) {
        case 'admin':
            header('Location: admin/index.php');
            break;
        case 'director':
            header('Location: directors/index.php');
            break;
        case 'employee':
            header('Location: employees/index.php');
            break;
        default:
            header('Location: auth/login.php');
    }
} else {
    header('Location: auth/login.php');
}
exit;
