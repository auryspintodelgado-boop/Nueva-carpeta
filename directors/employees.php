<?php
/**
 * Employees List - Director View
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('director');

$pageTitle = 'Empleados';
$conn = getDBConnection();
$userId = getUserId();

// Get director's department
$deptQuery = $conn->query("SELECT id, name FROM departments WHERE director_id = $userId");
$department = $deptQuery->fetch_assoc();
$deptId = $department['id'] ?? 0;

// Get filter status
$statusFilter = $_GET['status'] ?? 'all';

// Build query based on filter
$whereClause = "e.department_id = $deptId";
if ($statusFilter !== 'all') {
    $whereClause .= " AND e.characterization_status = '$statusFilter'";
}

$employees = $conn->query("
    SELECT e.*, u.username, u.status as user_status
    FROM employees e
    JOIN users u ON e.user_id = u.id
    WHERE $whereClause
    ORDER BY e.primer_apellido, e.primer_nombre
");
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-people"></i> Empleados - <?= htmlspecialchars($department['name'] ?? 'Departamento') ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="?status=all" class="btn btn-sm btn-outline-secondary <?= $statusFilter === 'all' ? 'active' : '' ?>">Todos</a>
            <a href="?status=pending" class="btn btn-sm btn-outline-warning <?= $statusFilter === 'pending' ? 'active' : '' ?>">Pendientes</a>
            <a href="?status=verified" class="btn btn-sm btn-outline-success <?= $statusFilter === 'verified' ? 'active' : '' ?>">Verificados</a>
            <a href="?status=rejected" class="btn btn-sm btn-outline-danger <?= $statusFilter === 'rejected' ? 'active' : '' ?>">Rechazados</a>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="employeesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre Completo</th>
                        <th>Cédula</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 1; while ($emp = $employees->fetch_assoc()): ?>
                    <tr class="employee-row" data-dept="<?= $emp['department_id'] ?>">
                        <td><?= $counter++ ?></td>
                        <td>
                            <strong><?= htmlspecialchars($emp['primer_nombre'] . ' ' . $emp['segundo_nombre'] . ' ' . $emp['primer_apellido'] . ' ' . $emp['segundo_apellido']) ?></strong>
                        </td>
                        <td><?= htmlspecialchars($emp['cedula']) ?></td>
                        <td><?= htmlspecialchars($emp['telefono1'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($emp['correo_electronico'] ?? 'N/A') ?></td>
                        <td><?= get_status_badge($emp['characterization_status']) ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="verify_employee.php?id=<?= $emp['id'] ?>" class="btn btn-sm btn-primary" 
                                   title="Verificar">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                                <a href="evaluate.php?employee_id=<?= $emp['id'] ?>" class="btn btn-sm btn-success"
                                   title="Evaluar">
                                    <i class="bi bi-clipboard-check"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($employees->num_rows === 0): ?>
<div class="alert alert-info text-center">
    <i class="bi bi-info-circle"></i> No hay empleados en este departamento con el filtro seleccionado.
</div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
