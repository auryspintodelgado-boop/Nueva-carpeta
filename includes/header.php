<?php
/**
 * Common Header
 * AURYS - Sistema de Gestión de Recursos Humanos
 */
require_once __DIR__ . '/../config/database.php';
startSession();

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$userRole = getUserRole();
$userId = getUserId();

// Get user info
$userName = '';
if ($userId) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $userName = $row['username'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= getPageTitle($pageTitle ?? '') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= base_url() ?>/css/styles.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }
        
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a252f 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 5px;
            margin: 2px 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.15);
            color: #fff;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1a252f 100%);
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-verified {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .table tbody tr {
            transition: background-color 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <?php if (isLoggedIn()): ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse show" id="sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white mb-0">
                            <i class="bi bi-building"></i> AURYS
                        </h4>
                        <small class="text-white-50">Gestión de RRHH</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <?php if ($userRole === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'admin-index' ? 'active' : '' ?>" href="index.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'manage-departments' ? 'active' : '' ?>" href="manage_departments.php">
                                <i class="bi bi-diagram-3"></i> Departamentos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'manage-directors' ? 'active' : '' ?>" href="manage_directors.php">
                                <i class="bi bi-person-badge"></i> Directores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'reports' ? 'active' : '' ?>" href="reports.php">
                                <i class="bi bi-file-earmark-bar-graph"></i> Reportes
                            </a>
                        </li>
                        
                        <?php elseif ($userRole === 'director'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'director-index' ? 'active' : '' ?>" href="index.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'employees' ? 'active' : '' ?>" href="employees.php">
                                <i class="bi bi-people"></i> Empleados
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'evaluate' ? 'active' : '' ?>" href="evaluate.php">
                                <i class="bi bi-clipboard-check"></i> Evaluar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'evaluations-history' ? 'active' : '' ?>" href="evaluations_history.php">
                                <i class="bi bi-history"></i> Historial
                            </a>
                        </li>
                        
                        <?php elseif ($userRole === 'employee'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'employee-index' ? 'active' : '' ?>" href="index.php">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'characterization' ? 'active' : '' ?>" href="characterization.php">
                                <i class="bi bi-person-lines-fill"></i> Caracterización
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'my-profile' ? 'active' : '' ?>" href="my_profile.php">
                                <i class="bi bi-person"></i> Mi Perfil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage === 'my-evaluations' ? 'active' : '' ?>" href="my_evaluations.php">
                                <i class="bi bi-star"></i> Mis Evaluaciones
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <div class="mt-4 px-3">
                        <div class="card bg-transparent border-light">
                            <div class="card-body text-center">
                                <div class="user-avatar mx-auto mb-2">
                                    <?= strtoupper(substr($userName, 0, 1)) ?>
                                </div>
                                <small class="text-white d-block"><?= htmlspecialchars($userName) ?></small>
                                <span class="badge bg-<?= $userRole === 'admin' ? 'danger' : ($userRole === 'director' ? 'warning' : 'info') ?>">
                                    <?= ucfirst($userRole) ?>
                                </span>
                            </div>
                        </div>
                        <a href="<?= base_url() ?>/auth/logout.php" class="btn btn-outline-light btn-sm w-100 mt-3">
                            <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </nav>
            
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
    <?php endif; ?>
