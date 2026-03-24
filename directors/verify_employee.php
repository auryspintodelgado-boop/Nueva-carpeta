<?php
/**
 * Verify Employee Characterization
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('director');

$pageTitle = 'Verificar Empleado';
$conn = getDBConnection();
$userId = getUserId();

$error = '';
$success = '';

// Get employee ID from URL
$employee_id = intval($_GET['id'] ?? 0);

if (!$employee_id) {
    header('Location: employees.php');
    exit;
}

// Verify employee belongs to director's department
$deptQuery = $conn->query("SELECT id, name FROM departments WHERE director_id = $userId");
$department = $deptQuery->fetch_assoc();
$deptId = $department['id'] ?? 0;

$stmt = $conn->prepare("SELECT e.*, u.username FROM employees e JOIN users u ON e.user_id = u.id WHERE e.id = ? AND e.department_id = ?");
$stmt->bind_param("ii", $employee_id, $deptId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: employees.php');
    exit;
}

$employee = $result->fetch_assoc();
$stmt->close();

// Handle verification action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'verify') {
        $stmt = $conn->prepare("UPDATE employees SET characterization_status = 'verified', verified_by = ?, verified_at = NOW() WHERE id = ?");
        $stmt->bind_param("ii", $userId, $employee_id);
        
        if ($stmt->execute()) {
            $success = 'Empleado verificado exitosamente';
            $employee['characterization_status'] = 'verified';
        } else {
            $error = 'Error al verificar el empleado';
        }
        $stmt->close();
    } elseif ($action === 'reject') {
        $reason = sanitize($_POST['reason'] ?? '');
        
        $stmt = $conn->prepare("UPDATE employees SET characterization_status = 'rejected', verified_by = ?, verified_at = NOW(), rejection_reason = ? WHERE id = ?");
        $stmt->bind_param("isi", $userId, $reason, $employee_id);
        
        if ($stmt->execute()) {
            $success = 'Caracterización rechazada. El empleado recibirá una notificación para corregir.';
            $employee['characterization_status'] = 'rejected';
        } else {
            $error = 'Error al rechazar la caracterización';
        }
        $stmt->close();
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-check-circle"></i> Verificar Caracterización</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="employees.php" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<?php if ($error): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle"></i> <?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <!-- Employee Profile -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person"></i> Datos del Empleado
            </div>
            <div class="card-body text-center">
                <div class="profile-photo-placeholder mb-3 mx-auto">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h5><?= htmlspecialchars($employee['primer_nombre'] . ' ' . $employee['primer_apellido']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($employee['cedula']) ?></p>
                <hr>
                <p><strong>Usuario:</strong> <?= htmlspecialchars($employee['username']) ?></p>
                <p><strong>Estado:</strong> <?= get_status_badge($employee['characterization_status']) ?></p>
            </div>
        </div>
    </div>
    
    <!-- Characterization Details -->
    <div class="col-md-8 mb-4">
        <?php if ($employee['characterization_status'] === 'pending'): ?>
        <!-- Verification Actions -->
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <i class="bi bi-exclamation-triangle"></i> Acciones de Verificación
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" name="action" value="verify" class="btn btn-success w-100 btn-lg">
                                <i class="bi bi-check-circle"></i> Verificar
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle"></i> Rechazar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-<?= $employee['characterization_status'] === 'verified' ? 'success' : 'danger' ?>">
            <i class="bi bi-<?= $employee['characterization_status'] === 'verified' ? 'check-circle' : 'x-circle' ?>"></i>
            Esta caracterización ha sido <?= $employee['characterization_status'] ?>.
            <?php if ($employee['characterization_status'] === 'rejected' && !empty($employee['rejection_reason'])): ?>
            <br><strong>Razón:</strong> <?= htmlspecialchars($employee['rejection_reason']) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Characterization Form Display -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clipboard-data"></i> Datos de Caracterización
            </div>
            <div class="card-body">
                <!-- Identificación -->
                <h6 class="text-primary mb-3"><i class="bi bi-person-badge"></i> Identificación</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Nacionalidad</small>
                        <p><?= htmlspecialchars($employee['nacionalidad'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Cédula</small>
                        <p><?= htmlspecialchars($employee['cedula'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <small class="text-muted">Primer Nombre</small>
                        <p><?= htmlspecialchars($employee['primer_nombre'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Segundo Nombre</small>
                        <p><?= htmlspecialchars($employee['segundo_nombre'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Primer Apellido</small>
                        <p><?= htmlspecialchars($employee['primer_apellido'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Segundo Apellido</small>
                        <p><?= htmlspecialchars($employee['segundo_apellido'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <hr>
                
                <!-- Datos Personales -->
                <h6 class="text-primary mb-3"><i class="bi bi-heart"></i> Datos Personales</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted">Sexo</small>
                        <p><?= htmlspecialchars($employee['sexo'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Fecha de Nacimiento</small>
                        <p><?= format_date($employee['fecha_nacimiento'] ?? '') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Edad</small>
                        <p><?= $employee['edad'] ?? 'N/A' ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Teléfono</small>
                        <p><?= htmlspecialchars($employee['telefono1'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Correo Electrónico</small>
                        <p><?= htmlspecialchars($employee['correo_electronico'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <hr>
                
                <!-- Educación -->
                <h6 class="text-primary mb-3"><i class="bi bi-book"></i> Educación</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Carrera</small>
                        <p><?= htmlspecialchars($employee['carrera'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Año/Semestre</small>
                        <p><?= htmlspecialchars($employee['ano_semestre'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted">Posee Beca</small>
                        <p><?= htmlspecialchars($employee['posee_beca'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Universidad</small>
                        <p><?= htmlspecialchars($employee['siglas_universidad'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Tipo</small>
                        <p><?= htmlspecialchars($employee['tipo_ieu'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <hr>
                
                <!-- Dirección -->
                <h6 class="text-primary mb-3"><i class="bi bi-house"></i> Dirección</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted">Urbanismo</small>
                        <p><?= htmlspecialchars($employee['urbanismo'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Municipio</small>
                        <p><?= htmlspecialchars($employee['municipio'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Parroquia</small>
                        <p><?= htmlspecialchars($employee['parroquia'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <hr>
                
                <!-- Familia -->
                <h6 class="text-primary mb-3"><i class="bi bi-people"></i> Familia</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted">Estado Civil</small>
                        <p><?= htmlspecialchars($employee['estado_civil'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Tiene Hijos</small>
                        <p><?= htmlspecialchars($employee['tiene_hijos'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Carga Familiar</small>
                        <p><?= $employee['carga_familiar'] ?? 'N/A' ?></p>
                    </div>
                </div>
                <hr>
                
                <!-- Salud -->
                <h6 class="text-primary mb-3"><i class="bi bi-heart-pulse"></i> Salud</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted">Discapacidad</small>
                        <p><?= htmlspecialchars($employee['posee_discapacidad'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Enfermedad</small>
                        <p><?= htmlspecialchars($employee['presenta_enfermedad'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Tipo de Sangre</small>
                        <p><?= htmlspecialchars($employee['tipo_sangre'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <hr>
                
                <!-- Empleo -->
                <h6 class="text-primary mb-3"><i class="bi bi-briefcase"></i> Empleo</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted">Trabaja</small>
                        <p><?= htmlspecialchars($employee['trabaja'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Tipo de Empleo</small>
                        <p><?= htmlspecialchars($employee['tipo_empleo'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Medio de Transporte</small>
                        <p><?= htmlspecialchars($employee['medio_transporte'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <hr>
                
                <!-- CNE -->
                <h6 class="text-primary mb-3"><i class="bi bi-card-checklist"></i> CNE</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted">Inscrito en CNE</small>
                        <p><?= htmlspecialchars($employee['inscrito_cne'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Centro Electoral</small>
                        <p><?= htmlspecialchars($employee['centro_electoral'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Comuna</small>
                        <p><?= htmlspecialchars($employee['comuna'] ?? 'N/A') ?></p>
                    </div>
                </div>
                <hr>
                
                <!-- Medidas -->
                <h6 class="text-primary mb-3"><i class="bi bi-rulers"></i> Medidas</h6>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <small class="text-muted">Talla Camisa</small>
                        <p><?= htmlspecialchars($employee['talla_camisa'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Talla Zapatos</small>
                        <p><?= htmlspecialchars($employee['talla_zapatos'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Talla Pantalón</small>
                        <p><?= htmlspecialchars($employee['talla_pantalon'] ?? 'N/A') ?></p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Peso</small>
                        <p><?= $employee['peso'] ? $employee['peso'] . ' kg' : 'N/A' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><i class="bi bi-x-circle"></i> Rechazar Caracterización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="reject">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Razón del Rechazo *</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" required placeholder="Explique por qué se rechaza la caracterización..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Rechazar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
