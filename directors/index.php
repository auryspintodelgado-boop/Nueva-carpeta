<?php
/**
 * Director Dashboard
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('director');

$pageTitle = 'Dashboard';
$conn = getDBConnection();
$userId = getUserId();

// Get director's department
$deptQuery = $conn->query("SELECT id, name FROM departments WHERE director_id = $userId");
$department = $deptQuery->fetch_assoc();
$deptId = $department['id'] ?? 0;

// Get statistics for the department
$totalEmployees = $conn->query("SELECT COUNT(*) as total FROM employees WHERE department_id = $deptId")->fetch_assoc()['total'];
$pendingChar = $conn->query("SELECT COUNT(*) as total FROM employees WHERE department_id = $deptId AND characterization_status = 'pending'")->fetch_assoc()['total'];
$verifiedChar = $conn->query("SELECT COUNT(*) as total FROM employees WHERE department_id = $deptId AND characterization_status = 'verified'")->fetch_assoc()['total'];

// Get employees pending verification
$pendingEmployees = $conn->query("
    SELECT e.*, u.username, u.status as user_status
    FROM employees e
    JOIN users u ON e.user_id = u.id
    WHERE e.department_id = $deptId AND e.characterization_status = 'pending'
    ORDER BY e.created_at DESC
");

// Get recent evaluations
$recentEvaluations = $conn->query("
    SELECT ev.*, CONCAT(e.primer_nombre, ' ', e.primer_apellido) as employee_name
    FROM evaluations ev
    JOIN employees e ON ev.employee_id = e.id
    WHERE e.department_id = $deptId
    ORDER BY ev.created_at DESC
    LIMIT 5
);

// Get all department employees
$allEmployees = $conn->query("
    SELECT e.*, u.username
    FROM employees e
    JOIN users u ON e.user_id = u.id
    WHERE e.department_id = $deptId
    ORDER BY e.primer_apellido, e.primer_nombre
");
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard - <?= htmlspecialchars($department['name'] ?? 'Departamento') ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer"></i> Imprimir
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
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
    
    <div class="col-md-4">
        <div class="card stat-card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Pendientes de Verificación</h6>
                        <h2 class="mb-0"><?= $pendingChar ?></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Verificados</h6>
                        <h2 class="mb-0"><?= $verifiedChar ?></h2>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($pendingChar > 0): ?>
<div class="alert alert-info" role="alert">
    <i class="bi bi-info-circle"></i> 
    Hay <strong><?= $pendingChar ?></strong> empleado(s) esperando verificación de caracterización.
    <a href="employees.php" class="btn btn-sm btn-primary ms-2">Verificar Ahora</a>
</div>
<?php endif; ?>

<div class="row">
    <!-- Employees Pending Verification -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-hourglass-split"></i> Empleados Pendientes de Verificación
            </div>
            <div class="card-body p-0">
                <?php if ($pendingEmployees->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($emp = $pendingEmployees->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($emp['primer_nombre'] . ' ' . $emp['primer_apellido']) ?></td>
                                <td><?= htmlspecialchars($emp['cedula']) ?></td>
                                <td>
                                    <a href="verify_employee.php?id=<?= $emp['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-check-circle"></i> Verificar
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center p-4 text-muted">
                    <i class="bi bi-check-circle fs-1"></i>
                    <p class="mt-2">No hay empleados pendientes de verificación</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Recent Evaluations -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clipboard-check"></i> Evaluaciones Recientes
            </div>
            <div class="card-body p-0">
                <?php if ($recentEvaluations->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Empleado</th>
                                <th>Fecha</th>
                                <th>Puntaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($eval = $recentEvaluations->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($eval['employee_name']) ?></td>
                                <td><?= format_date($eval['evaluation_date']) ?></td>
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
                <?php else: ?>
                <div class="text-center p-4 text-muted">
                    <i class="bi bi-clipboard fs-1"></i>
                    <p class="mt-2">No hay evaluaciones realizadas</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-lightning"></i> Acciones Rápidas
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <a href="employees.php" class="text-decoration-none">
                            <div class="p-3 border rounded hover-effect">
                                <i class="bi bi-people fs-1 text-primary"></i>
                                <h5 class="mt-2">Ver Empleados</h5>
                                <small class="text-muted">Lista completa del personal</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="evaluate.php" class="text-decoration-none">
                            <div class="p-3 border rounded hover-effect">
                                <i class="bi bi-clipboard-check fs-1 text-success"></i>
                                <h5 class="mt-2">Nueva Evaluación</h5>
                                <small class="text-muted">Evaluar rendimiento mensual</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="evaluations_history.php" class="text-decoration-none">
                            <div class="p-3 border rounded hover-effect">
                                <i class="bi bi-history fs-1 text-warning"></i>
                                <h5 class="mt-2">Historial</h5>
                                <small class="text-muted">Ver evaluaciones anteriores</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-effect:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
