<?php
/**
 * Performance Evaluation Form
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('director');

$pageTitle = 'Evaluar Empleado';
$conn = getDBConnection();
$userId = getUserId();

$error = '';
$success = '';

// Get director's department
$deptQuery = $conn->query("SELECT id, name FROM departments WHERE director_id = $userId");
$department = $deptQuery->fetch_assoc();
$deptId = $department['id'] ?? 0;

// Get selected employee
$employee_id = intval($_GET['employee_id'] ?? 0);

// Get employees for dropdown (only verified ones)
$employees = $conn->query("
    SELECT e.id, e.primer_nombre, e.segundo_nombre, e.primer_apellido, e.segundo_apellido, e.cedula
    FROM employees e
    WHERE e.department_id = $deptId AND e.characterization_status = 'verified'
    ORDER BY e.primer_apellido, e.primer_nombre
");

// If no employee selected, try to get from form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = intval($_POST['employee_id'] ?? 0);
}

$selectedEmployee = null;
if ($employee_id) {
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ? AND department_id = ?");
    $stmt->bind_param("ii", $employee_id, $deptId);
    $stmt->execute();
    $result = $stmt->get_result();
    $selectedEmployee = $result->fetch_assoc();
    $stmt->close();
}

// Handle evaluation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_evaluation') {
    $employee_id = intval($_POST['employee_id']);
    $mes = sanitize($_POST['mes']);
    $ano = intval($_POST['ano']);
    $evaluation_date = date('Y-m-d');
    
    // Get scores
    $orientation_cumple = intval($_POST['orientation_cumple']);
    $orientation_volumen = intval($_POST['orientation_volumen']);
    $orientation_comments = sanitize($_POST['orientation_comments'] ?? '');
    
    $quality_no_errores = intval($_POST['quality_no_errores']);
    $quality_recursos = intval($_POST['quality_recursos']);
    $quality_supervision = intval($_POST['quality_supervision']);
    $quality_profesional = intval($_POST['quality_profesional']);
    $quality_respetuoso = intval($_POST['quality_respetuoso']);
    $quality_planifica = intval($_POST['quality_planifica']);
    $quality_indicadores = intval($_POST['quality_indicadores']);
    $quality_metas = intval($_POST['quality_metas']);
    $quality_comments = sanitize($_POST['quality_comments'] ?? '');
    
    $teamwork_cortes = intval($_POST['teamwork_cortes']);
    $teamwork_orientacion = intval($_POST['teamwork_orientacion']);
    $teamwork_conflictos = intval($_POST['teamwork_conflictos']);
    $teamwork_integracion = intval($_POST['teamwork_integracion']);
    $teamwork_objetivos = intval($_POST['teamwork_objetivos']);
    $teamwork_comments = sanitize($_POST['teamwork_comments'] ?? '');
    
    $initiative_ideas = intval($_POST['initiative_ideas']);
    $initiative_cambio = intval($_POST['initiative_cambio']);
    $initiative_dificultades = intval($_POST['initiative_dificultades']);
    $initiative_resolver = intval($_POST['initiative_resolver']);
    $initiative_comments = sanitize($_POST['initiative_comments'] ?? '');
    
    $general_comments = sanitize($_POST['general_comments'] ?? '');
    
    // Calculate total score (3 criteria per area for the main score)
    $total_score = $orientation_cumple + $orientation_volumen + 
                   $quality_profesional + $quality_respetuoso + $quality_planifica + $quality_metas +
                   $teamwork_cortes + $teamwork_integracion + $teamwork_objetivos +
                   $initiative_ideas + $initiative_cambio + $initiative_resolver;
    
    // Insert evaluation
    $stmt = $conn->prepare("INSERT INTO evaluations 
        (employee_id, evaluator_id, evaluation_date, mes, ano,
         orientation_cumple_tareas, orientation_volumen_adecuado, orientation_comentarios,
         quality_profesional, quality_respetuoso, quality_planifica, quality_metas, quality_no_errores, quality_recursos, quality_supervision, quality_indicadores, quality_comentarios,
         teamwork_cortes, teamwork_integracion, teamwork_objetivos, teamwork_orientacion, teamwork_conflictos, teamwork_comentarios,
         initiative_ideas, initiative_cambio, initiative_resolver, initiative_dificultades, initiative_comentarios,
         total_score, general_comments, evaluator_signature)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
    
    $stmt->bind_param("iissiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiis", 
        $employee_id, $userId, $evaluation_date, $mes, $ano,
        $orientation_cumple, $orientation_volumen, $orientation_comments,
        $quality_profesional, $quality_respetuoso, $quality_planifica, $quality_metas, $quality_no_errores, $quality_recursos, $quality_supervision, $quality_indicadores, $quality_comments,
        $teamwork_cortes, $teamwork_integracion, $teamwork_objetivos, $teamwork_orientacion, $teamwork_conflictos, $teamwork_comments,
        $initiative_ideas, $initiative_cambio, $initiative_resolver, $initiative_dificultades, $initiative_comments,
        $total_score, $general_comments);
    
    if ($stmt->execute()) {
        $success = 'Evaluación guardada exitosamente';
    } else {
        $error = 'Error al guardar la evaluación: ' . $stmt->error;
    }
    $stmt->close();
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-clipboard-check"></i> Evaluación de Desempeño</h1>
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

<form method="POST" action="">
    <input type="hidden" name="action" value="submit_evaluation">
    
    <!-- Employee Selection -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-person"></i> Datos de la Evaluación
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="employee_id" class="form-label">Empleado a Evaluar *</label>
                    <select class="form-select" id="employee_id" name="employee_id" required onchange="this.form.submit()">
                        <option value="">Seleccione un empleado</option>
                        <?php while ($emp = $employees->fetch_assoc()): ?>
                        <option value="<?= $emp['id'] ?>" <?= $employee_id === $emp['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($emp['primer_nombre'] . ' ' . $emp['primer_apellido']) ?> - <?= $emp['cedula'] ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="mes" class="form-label">Mes *</label>
                    <select class="form-select" id="mes" name="mes" required>
                        <?php foreach (get_months() as $key => $month): ?>
                        <option value="<?= $key ?>" <?= date('m') === $key ? 'selected' : '' ?>><?= $month ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="ano" class="form-label">Año *</label>
                    <select class="form-select" id="ano" name="ano" required>
                        <option value="<?= date('Y') ?>" selected><?= date('Y') ?></option>
                        <option value="<?= date('Y') - 1 ?>"><?= date('Y') - 1 ?></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($selectedEmployee): ?>
    
    <!-- Employee Info -->
    <div class="alert alert-info mb-4">
        <strong>Evaluado:</strong> <?= htmlspecialchars($selectedEmployee['primer_nombre'] . ' ' . $selectedEmployee['primer_apellido']) ?>
        &nbsp;|&nbsp; <strong>Cédula:</strong> <?= htmlspecialchars($selectedEmployee['cedula']) ?>
    </div>
    
    <!-- Area 1: Orientación de Resultados -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-bullseye"></i> 1. Orientación de Resultados</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">¿En qué grado el trabajador tiene desarrollada la orientación a resultados?</p>
            
            <div class="evaluation-criteria">
                <label class="form-label">Termina su trabajo oportunamente *</label>
                <div class="score-options">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input score-input" type="radio" name="orientation_cumple" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                    <small class="text-muted ms-2">
                        (1=Muy Bajo, 2=Bajo, 3=Moderado, 4=Alto, 5=Muy Alto)
                    </small>
                </div>
            </div>
            
            <div class="evaluation-criteria">
                <label class="form-label">Cumple con las tareas que se le encomienda *</label>
                <div class="score-options">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input score-input" type="radio" name="orientation_volumen" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="orientation_comments" class="form-label">Comentarios</label>
                <textarea class="form-control" id="orientation_comments" name="orientation_comments" rows="2"></textarea>
            </div>
        </div>
    </div>
    
    <!-- Area 2: Calidad y Organización -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-check2-square"></i> 2. Calidad y Organización</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">¿En qué grado el trabajador tiene desarrollada la calidad y organización?</p>
            
            <div class="row">
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">No comete errores en el trabajo</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="quality_no_errores" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Hace uso racional de los recursos</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="quality_recursos" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">No requiere de supervisión frecuente</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="quality_supervision" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Se muestra profesional en el trabajo *</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="quality_profesional" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Se muestra respetuoso y amable en el trato *</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="quality_respetuoso" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Planifica sus actividades *</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="quality_planifica" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Hace uso de indicadores</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="quality_indicadores" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Se preocupa por alcanzar las metas *</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="quality_metas" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="quality_comments" class="form-label">Comentarios</label>
                <textarea class="form-control" id="quality_comments" name="quality_comments" rows="2"></textarea>
            </div>
        </div>
    </div>
    
    <!-- Area 3: Relaciones Interpersonales y Trabajo en Equipo -->
    <div class="card mb-4">
        <div class="card-header bg-info text-dark">
            <h5 class="mb-0"><i class="bi bi-people"></i> 3. Relaciones Interpersonales y Trabajo en Equipo</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">¿En qué grado el trabajador tiene desarrolladas las relaciones interpersonales?</p>
            
            <div class="row">
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Se muestra cortés con el personal y compañeros *</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="teamwork_cortes" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Brinda adecuada orientación a sus compañeros</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="teamwork_orientacion" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Evita los conflictos dentro del trabajo</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="teamwork_conflictos" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Muestra aptitud para integrarse al equipo *</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="teamwork_integracion" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="evaluation-criteria">
                <label class="form-label">Se identifica fácilmente con los objetivos del equipo *</label>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="teamwork_objetivos" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                    <label class="form-check-label"><?= $i ?></label>
                </div>
                <?php endfor; ?>
            </div>
            
            <div class="mb-3">
                <label for="teamwork_comments" class="form-label">Comentarios</label>
                <textarea class="form-control" id="teamwork_comments" name="teamwork_comments" rows="2"></textarea>
            </div>
        </div>
    </div>
    
    <!-- Area 4: Iniciativa -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-lightbulb"></i> 4. Iniciativa</h5>
        </div>
        <div class="card-body">
            <p class="text-muted">¿En qué grado el trabajador tiene desarrollada la iniciativa?</p>
            
            <div class="row">
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Muestra nuevas ideas para mejorar los procesos *</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="initiative_ideas" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Se muestra asequible al cambio *</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="initiative_cambio" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Se anticipa a las dificultades</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="initiative_dificultades" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="col-md-6 evaluation-criteria">
                    <label class="form-label">Tiene gran capacidad para resolver problemas *</label>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="initiative_resolver" value="<?= $i ?>" <?= $i === 3 ? 'checked' : '' ?> required>
                        <label class="form-check-label"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="initiative_comments" class="form-label">Comentarios</label>
                <textarea class="form-control" id="initiative_comments" name="initiative_comments" rows="2"></textarea>
            </div>
        </div>
    </div>
    
    <!-- General Comments -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-chat-square-text"></i> Comentarios Generales
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="general_comments" class="form-label">Comentarios adicionales</label>
                <textarea class="form-control" id="general_comments" name="general_comments" rows="4" placeholder="Escriba cualquier comentario adicional sobre la evaluación..."></textarea>
            </div>
        </div>
    </div>
    
    <!-- Submit -->
    <div class="d-grid gap-2 mb-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-circle"></i> Guardar Evaluación
        </button>
    </div>
    
    <?php endif; ?>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
