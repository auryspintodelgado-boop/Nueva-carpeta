<?php
/**
 * Vista de Índice
 * Sistema de Evaluación, Seguimiento y Caracterización
 * Redirecciona al login
 */

// Redireccionar al dashboard si está logueado, si no al login
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header('Location: /dashboard');
    exit;
} else {
    header('Location: /login');
    exit;
}
