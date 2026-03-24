<?php
/**
 * Employee Dashboard
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('employee');

$pageTitle = 'Dashboard';
$conn = getDBConnection();
$userId = getUserId();

// Get employee profile
$stmt = $conn->prepare("SELECT e.*, d.name as dept_name 
    FROM employees e 
    JOIN departments d ON e.department_id = d.id 
    WHERE e.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

// Get evaluations
$evaluations = $conn->query("
    SELECT * FROM evaluations 
    WHERE employee_id = {$employee['id']}
    ORDER BY evaluation_date DESC
    LIMIT 5
");

// Get average score
$avgScore = $conn->query("
    SELECT AVG(total_score) as avg FROM evaluations WHERE employee_id = {$employee['id']}
")->fetch_assoc()['avg'] ?? 0;
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard</h1>
</div>

<!-- Welcome Message -->
<div class="alert alert-info mb-4">
    <h4>Bienvenido, <?= htmlspecialchars($employee['primer_nombre'] . ' ' . $employee['primer_apellido']) ?>!</h4>
    <p class="mb-0">Departamento: <strong><?= htmlspecialchars($employee['dept_name']) ?></strong></p>
</div>

<!-- Characterization Status -->
<?php if ($employee['characterization_status'] === 'pending'): ?>
<div class="alert alert-warning" role="alert">
    <i class="bi bi-exclamation-triangle"></i>
    Su caracterización está <strong>pendiente de verificación</strong>. Por favor complete todos los datos requeridos.
    <a href="characterization.php" class="btn btn-sm btn-primary ms-2">Completar Caracterización</a>
</div>
<?php elseif ($employee['characterization_status'] === 'rejected'): ?>
<div class="alert alert-danger" role="alert">
    <i class="bi bi-x-circle"></i>
    Su caracterización ha sido <strong>rechazada</strong>. Por favor corrija los datos solicitados.
    <br><strong>Razón:</strong> <?= htmlspecialchars($employee['rejection_reason']) ?>
    <a href="characterization.php" class="btn btn-sm btn-primary ms-2">Corregir Caracterización</a>
</div>
<?php else: ?>
<div class="alert alert-success" role="alert">
    <i class="bi bi-check-circle"></i> Su caracterización ha sido verificada correctamente.
</div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Estado de Caracterización</h6>
                        <h3 class="mb-0"><?= get_status_badge($employee['characterization_status']) ?></h3>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Promedio de Evaluaciones</h6>
                        <h2 class="mb-0"><?= number_format($avgScore, 1) ?>/20</h2>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Profile Summary -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person"></i> Mi Perfil
            </div>
            <div class="card-body text-center">
                <div class="profile-photo-placeholder mb-3 mx-auto">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h5><?= htmlspecialchars($employee['primer_nombre'] . ' ' . $employee['primer_apellido']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($employee['cedula']) ?></p>
                <hr>
                <p><i class="bi bi-envelope"></i> <?= htmlspecialchars($employee['correo_electronico'] ?? 'No registrado') ?></p>
                <p><i class="bi bi-telephone"></i> <?= htmlspecialchars($employee['telefono1'] ?? 'No registrado') ?></p>
                <div class="d-grid gap-2 mt-3">
                    <a href="characterization.php" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> <?= $employee['characterization_status'] === 'verified' ? 'Ver' : 'Completar' ?> Caracterización
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Evaluations -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clipboard-check"></i> Mis Evaluaciones Recientes
            </div>
            <div class="card-body p-0">
                <?php if ($evaluations->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Mes/Año</th>
                                <th>Puntaje</th>
                                <th>Nivel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($eval = $evaluations->fetch_assoc()): ?>
                            <tr>
                                <td><?= format_date($eval['evaluation_date']) ?></td>
                                <td><?= get_months()[$eval['mes']] ?? $eval['mes'] ?>/<?= $eval['ano'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $eval['total_score'] >= 14 ? 'success' : ($eval['total_score'] >= 8 ? 'warning' : 'danger') ?>">
                                        <?= $eval['total_score'] ?>/20
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $level = '';
                                    if ($eval['total_score'] >= 16) $level = 'Excelente';
                                    elseif ($eval['total_score'] >= 12) $level = 'Bueno';
                                    elseif ($eval['total_score'] >= 8) $level = 'Regular';
                                    else $level = 'Bajo';
                                    echo $level;
                                    ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center p-4 text-muted">
                    <i class="bi bi-clipboard fs-1"></i>
                    <p class="mt-2">No hay evaluaciones registradas</p>
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
                        <a href="characterization.php" class="text-decoration-none">
                            <div class="p-3 border rounded hover-effect">
                                <i class="bi bi-person-lines-fill fs-1 text-primary"></i>
                                <h5 class="mt-2">Mi Caracterización</h5>
                                <small class="text-muted">Ver o completar datos personales</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="my_profile.php" class="text-decoration-none">
                            <div class="p-3 border rounded hover-effect">
                                <i class="bi bi-person fs-1 text-success"></i>
                                <h5 class="mt-2">Mi Perfil</h5>
                                <small class="text-muted">Información de cuenta</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="my_evaluations.php" class="text-decoration-none">
                            <div class="p-3 border rounded hover-effect">
                                <i class="bi bi-star fs-1 text-warning"></i>
                                <h5 class="mt-2">Mis Evaluaciones</h5>
                                <small class="text-muted">Historial completo</small>
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
