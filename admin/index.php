<?php
/**
 * Admin Dashboard
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
requireRole('admin');

$pageTitle = 'Dashboard';
$conn = getDBConnection();

// Get statistics
$totalEmployees = $conn->query("SELECT COUNT(*) as total FROM employees")->fetch_assoc()['total'];
$totalDepartments = $conn->query("SELECT COUNT(*) as total FROM departments")->fetch_assoc()['total'];
$totalDirectors = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'director'")->fetch_assoc()['total'];
$pendingChar = $conn->query("SELECT COUNT(*) as total FROM employees WHERE characterization_status = 'pending'")->fetch_assoc()['total'];
$verifiedChar = $conn->query("SELECT COUNT(*) as total FROM employees WHERE characterization_status = 'verified'")->fetch_assoc()['total'];
$rejectedChar = $conn->query("SELECT COUNT(*) as total FROM employees WHERE characterization_status = 'rejected'")->fetch_assoc()['total'];

// Recent employees
$recentEmployees = $conn->query("
    SELECT e.*, d.name as dept_name, u.username 
    FROM employees e 
    JOIN departments d ON e.department_id = d.id 
    JOIN users u ON e.user_id = u.id 
    ORDER BY e.created_at DESC 
    LIMIT 5
");

// Recent evaluations
$recentEvaluations = $conn->query("
    SELECT ev.*, CONCAT(e.primer_nombre, ' ', e.primer_apellido) as employee_name, u.username as evaluator
    FROM evaluations ev
    JOIN employees e ON ev.employee_id = e.id
    JOIN users u ON ev.evaluator_id = u.id
    ORDER BY ev.created_at DESC
    LIMIT 5
");
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer"></i> Imprimir
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Empleados</h6>
                        <h2 class="mb-0"><?= $totalEmployees ?></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Departamentos</h6>
                        <h2 class="mb-0"><?= $totalDepartments ?></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-diagram-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Directores</h6>
                        <h2 class="mb-0"><?= $totalDirectors ?></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Caracterizaciones Pendientes</h6>
                        <h2 class="mb-0"><?= $pendingChar ?></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Characterization Status -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pie-chart"></i> Estado de Caracterizaciones
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="p-3 bg-success text-white rounded">
                            <h3><?= $verifiedChar ?></h3>
                            <small>Verificadas</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-warning text-dark rounded">
                            <h3><?= $pendingChar ?></h3>
                            <small>Pendientes</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-danger text-white rounded">
                            <h3><?= $rejectedChar ?></h3>
                            <small>Rechazadas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Employees -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-plus"></i> Últimos Empleados Registrados
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Departamento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($emp = $recentEmployees->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($emp['primer_nombre'] . ' ' . $emp['primer_apellido']) ?></td>
                                <td><?= htmlspecialchars($emp['dept_name']) ?></td>
                                <td><?= get_status_badge($emp['characterization_status']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Evaluations -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clipboard-check"></i> Últimas Evaluaciones
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Empleado</th>
                                <th>Evaluador</th>
                                <th>Puntaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($eval = $recentEvaluations->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($eval['employee_name']) ?></td>
                                <td><?= htmlspecialchars($eval['evaluator']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $eval['total_score'] >= 14 ? 'success' : ($eval['total_score'] >= 8 ? 'warning' : 'danger') ?>">
                                        <?= $eval['total_score'] ?>/20
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
