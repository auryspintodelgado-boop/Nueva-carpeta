<?php
/**
 * Logout
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';

startSession();

// Destroy session
session_unset();
session_destroy();

// Redirect to login
header('Location: login.php');
exit;
