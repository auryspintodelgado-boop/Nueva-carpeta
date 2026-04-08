<?php
/**
 * Employee Characterization Form
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('employee');

$pageTitle = 'Caracterización Personal';
$conn = getDBConnection();
$userId = getUserId();

$error = '';
$success = '';

// Get employee profile
$stmt = $conn->prepare("SELECT e.*, d.name as dept_name FROM employees e JOIN departments d ON e.department_id = d.id WHERE e.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

// Check if already verified
$readonly = $employee['characterization_status'] === 'verified';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {
    
    // Get all form data
    $data = [
        'nacionalidad' => sanitize($_POST['nacionalidad'] ?? ''),
        'cedula' => sanitize($_POST['cedula'] ?? ''),
        'primer_nombre' => sanitize($_POST['primer_nombre'] ?? ''),
        'segundo_nombre' => sanitize($_POST['segundo_nombre'] ?? ''),
        'primer_apellido' => sanitize($_POST['primer_apellido'] ?? ''),
        'segundo_apellido' => sanitize($_POST['segundo_apellido'] ?? ''),
        'sexo' => sanitize($_POST['sexo'] ?? ''),
        'fecha_nacimiento' => sanitize($_POST['fecha_nacimiento'] ?? ''),
        'telefono1' => sanitize($_POST['telefono1'] ?? ''),
        'correo_electronico' => sanitize($_POST['correo_electronico'] ?? ''),
        'carrera' => sanitize($_POST['carrera'] ?? ''),
        'ano_semestre' => sanitize($_POST['ano_semestre'] ?? ''),
        'posee_beca' => sanitize($_POST['posee_beca'] ?? ''),
        'sede' => sanitize($_POST['sede'] ?? ''),
        'estado' => sanitize($_POST['estado'] ?? ''),
        'siglas_universidad' => sanitize($_POST['siglas_universidad'] ?? ''),
        'tipo_ieu' => sanitize($_POST['tipo_ieu'] ?? ''),
        'pregrado_postgrado' => sanitize($_POST['pregrado_postgrado'] ?? ''),
        'urbanismo' => sanitize($_POST['urbanismo'] ?? ''),
        'municipio' => sanitize($_POST['municipio'] ?? ''),
        'parroquia' => sanitize($_POST['parroquia'] ?? ''),
        'tiene_hijos' => sanitize($_POST['tiene_hijos'] ?? ''),
        'cantidad_hijos' => intval($_POST['cantidad_hijos'] ?? 0),
        'carga_familiar' => intval($_POST['carga_familiar'] ?? 0),
        'estado_civil' => sanitize($_POST['estado_civil'] ?? ''),
        'posee_discapacidad' => sanitize($_POST['posee_discapacidad'] ?? ''),
        'describe_discapacidad' => sanitize($_POST['describe_discapacidad'] ?? ''),
        'presenta_enfermedad' => sanitize($_POST['presenta_enfermedad'] ?? ''),
        'condicion_medica' => sanitize($_POST['condicion_medica'] ?? ''),
        'medicamentos' => sanitize($_POST['medicamentos'] ?? ''),
        'trabaja' => sanitize($_POST['trabaja'] ?? ''),
        'tipo_empleo' => sanitize($_POST['tipo_empleo'] ?? ''),
        'medio_transporte' => sanitize($_POST['medio_transporte'] ?? ''),
        'inscrito_cne' => sanitize($_POST['inscrito_cne'] ?? ''),
        'centro_electoral' => sanitize($_POST['centro_electoral'] ?? ''),
        'comuna' => sanitize($_POST['comuna'] ?? ''),
        'talla_camisa' => sanitize($_POST['talla_camisa'] ?? ''),
        'talla_zapatos' => sanitize($_POST['talla_zapatos'] ?? ''),
        'talla_pantalon' => sanitize($_POST['talla_pantalon'] ?? ''),
        'altura' => sanitize($_POST['altura'] ?? ''),
        'peso' => sanitize($_POST['peso'] ?? ''),
        'tipo_sangre' => sanitize($_POST['tipo_sangre'] ?? ''),
    ];
    
    // Calculate age if birthdate provided
    if (!empty($data['fecha_nacimiento'])) {
        $data['edad'] = calculate_age($data['fecha_nacimiento']);
    }
    
    // Build update query
    $fields = [];
    $values = [];
    foreach ($data as $key => $value) {
        $fields[] = "$key = ?";
        $values[] = $value;
    }
    $values[] = $employee['id'];
    
    $sql = "UPDATE employees SET " . implode(', ', $fields) . ", characterization_status = 'pending' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    // Build types string
    $types = str_repeat('s', count($values) - 1) . 'i';
    $stmt->bind_param($types, ...$values);
    
    if ($stmt->execute()) {
        $success = 'Datos guardados exitosamente. Su caracterización será verificada por su director.';
    } else {
        $error = 'Error al guardar los datos: ' . $stmt->error;
    }
    $stmt->close();
}

// Refresh employee data after update
$stmt = $conn->prepare("SELECT e.*, d.name as dept_name FROM employees e JOIN departments d ON e.department_id = d.id WHERE e.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person-lines-fill"></i> Caracterización Personal</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php if ($employee['characterization_status'] !== 'verified'): ?>
        <button type="submit" form="charForm" class="btn btn-sm btn-primary">
            <i class="bi bi-check-circle"></i> Guardar
        </button>
        <?php endif; ?>
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

<?php if ($employee['characterization_status'] === 'verified'): ?>
<div class="alert alert-success mb-4">
    <i class="bi bi-check-circle"></i> Su caracterización ha sido verificada. Si necesita hacer cambios, contacte a su director.
</div>
<?php elseif ($employee['characterization_status'] === 'rejected'): ?>
<div class="alert alert-danger mb-4">
    <i class="bi bi-x-circle"></i> Su caracterización fue rechazada. Por favor corrija los datos y envíe nuevamente.
    <br><strong>Razón:</strong> <?= htmlspecialchars($employee['rejection_reason']) ?>
</div>
<?php endif; ?>

<form method="POST" action="" id="charForm" class="char-form">
    <!-- Section 1: Identificación -->
    <div class="char-section">
        <h5 class="char-section-title"><i class="bi bi-person-badge"></i> 1. Identificación</h5>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" value="<?= htmlspecialchars($employee['nacionalidad'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
                <label for="cedula" class="form-label">Cédula de Identidad</label>
                <input type="text" class="form-control" id="cedula" name="cedula" value="<?= htmlspecialchars($employee['cedula'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?> required>
            </div>
            <div class="col-md-4">
                <label for="sexo" class="form-label">Sexo</label>
                <select class="form-select" id="sexo" name="sexo" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="M" <?= ($employee['sexo'] ?? '') === 'M' ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= ($employee['sexo'] ?? '') === 'F' ? 'selected' : '' ?>>Femenino</option>
                </select>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="primer_nombre" class="form-label">Primer Nombre *</label>
                <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" value="<?= htmlspecialchars($employee['primer_nombre'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?> required>
            </div>
            <div class="col-md-3">
                <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre" value="<?= htmlspecialchars($employee['segundo_nombre'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-3">
                <label for="primer_apellido" class="form-label">Primer Apellido *</label>
                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" value="<?= htmlspecialchars($employee['primer_apellido'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?> required>
            </div>
            <div class="col-md-3">
                <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" value="<?= htmlspecialchars($employee['segundo_apellido'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $employee['fecha_nacimiento'] ?? '' ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" class="form-control" id="edad" name="edad" value="<?= $employee['edad'] ?? '' ?>" readonly>
            </div>
            <div class="col-md-4">
                <label for="telefono1" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono1" name="telefono1" value="<?= htmlspecialchars($employee['telefono1'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="correo_electronico" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?= htmlspecialchars($employee['correo_electronico'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
        </div>
    </div>
    
    <!-- Section 2: Educación -->
    <div class="char-section">
        <h5 class="char-section-title"><i class="bi bi-book"></i> 2. Educación</h5>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="carrera" class="form-label">Carrera / Título</label>
                <input type="text" class="form-control" id="carrera" name="carrera" value="<?= htmlspecialchars($employee['carrera'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-3">
                <label for="ano_semestre" class="form-label">Año / Semestre</label>
                <input type="text" class="form-control" id="ano_semestre" name="ano_semestre" value="<?= htmlspecialchars($employee['ano_semestre'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-3">
                <label for="posee_beca" class="form-label">¿Posee Beca?</label>
                <select class="form-select" id="posee_beca" name="posee_beca" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="SI" <?= ($employee['posee_beca'] ?? '') === 'SI' ? 'selected' : '' ?>>Sí</option>
                    <option value="NO" <?= ($employee['posee_beca'] ?? '') === 'NO' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="sede" class="form-label">Sede</label>
                <input type="text" class="form-control" id="sede" name="sede" value="<?= htmlspecialchars($employee['sede'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
                <label for="estado" class="form-label">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" value="<?= htmlspecialchars($employee['estado'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
                <label for="siglas_universidad" class="form-label">Universidad (Siglas)</label>
                <input type="text" class="form-control" id="siglas_universidad" name="siglas_universidad" value="<?= htmlspecialchars($employee['siglas_universidad'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="tipo_ieu" class="form-label">Tipo de IEU</label>
                <select class="form-select" id="tipo_ieu" name="tipo_ieu" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="PUBLICA" <?= ($employee['tipo_ieu'] ?? '') === 'PUBLICA' ? 'selected' : '' ?>>Pública</option>
                    <option value="PRIVADA" <?= ($employee['tipo_ieu'] ?? '') === 'PRIVADA' ? 'selected' : '' ?>>Privada</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="pregrado_postgrado" class="form-label">Nivel de Estudio</label>
                <select class="form-select" id="pregrado_postgrado" name="pregrado_postgrado" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="PREGRADO" <?= ($employee['pregrado_postgrado'] ?? '') === 'PREGRADO' ? 'selected' : '' ?>>Pregrado</option>
                    <option value="POSTGRADO" <?= ($employee['pregrado_postgrado'] ?? '') === 'POSTGRADO' ? 'selected' : '' ?>>Postgrado</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Section 3: Dirección -->
    <div class="char-section">
        <h5 class="char-section-title"><i class="bi bi-house"></i> 3. Dirección</h5>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="urbanismo" class="form-label">Urbanismo</label>
                <input type="text" class="form-control" id="urbanismo" name="urbanismo" value="<?= htmlspecialchars($employee['urbanismo'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
                <label for="municipio" class="form-label">Municipio</label>
                <input type="text" class="form-control" id="municipio" name="municipio" value="<?= htmlspecialchars($employee['municipio'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
                <label for="parroquia" class="form-label">Parroquia</label>
                <input type="text" class="form-control" id="parroquia" name="parroquia" value="<?= htmlspecialchars($employee['parroquia'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
        </div>
    </div>
    
    <!-- Section 4: Familia -->
    <div class="char-section">
        <h5 class="char-section-title"><i class="bi bi-people"></i> 4. Familia</h5>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="estado_civil" class="form-label">Estado Civil</label>
                <select class="form-select" id="estado_civil" name="estado_civil" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="SOLTERO" <?= ($employee['estado_civil'] ?? '') === 'SOLTERO' ? 'selected' : '' ?>>Soltero</option>
                    <option value="CASADO" <?= ($employee['estado_civil'] ?? '') === 'CASADO' ? 'selected' : '' ?>>Casado</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="tiene_hijos" class="form-label">¿Tiene Hijos?</label>
                <select class="form-select" id="tiene_hijos" name="tiene_hijos" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="SI" <?= ($employee['tiene_hijos'] ?? '') === 'SI' ? 'selected' : '' ?>>Sí</option>
                    <option value="NO" <?= ($employee['tiene_hijos'] ?? '') === 'NO' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="cantidad_hijos" class="form-label">Cantidad de Hijos</label>
                <input type="number" class="form-control" id="cantidad_hijos" name="cantidad_hijos" value="<?= $employee['cantidad_hijos'] ?? 0 ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="carga_familiar" class="form-label">Carga Familiar</label>
            <input type="number" class="form-control" id="carga_familiar" name="carga_familiar" value="<?= $employee['carga_familiar'] ?? 0 ?>" <?= $readonly ? 'readonly' : '' ?>>
        </div>
    </div>
    
    <!-- Section 5: Discapacidad -->
    <div class="char-section">
        <h5 class="char-section-title"><i class="bi bi-person-wheelchair"></i> 5. Discapacidad</h5>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="posee_discapacidad" class="form-label">¿Posee Alguna Discapacidad?</label>
                <select class="form-select" id="posee_discapacidad" name="posee_discapacidad" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="SI" <?= ($employee['posee_discapacidad'] ?? '') === 'SI' ? 'selected' : '' ?>>Sí</option>
                    <option value="NO" <?= ($employee['posee_discapacidad'] ?? '') === 'NO' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="describe_discapacidad" class="form-label">Describa la Discapacidad</label>
                <textarea class="form-control" id="describe_discapacidad" name="describe_discapacidad" rows="2" <?= $readonly ? 'readonly' : '' ?>><?= htmlspecialchars($employee['describe_discapacidad'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
    
    <!-- Section 6: Salud -->
    <div class="char-section">
        <h5 class="char-section-title"><i class="bi bi-heart-pulse"></i> 6. Salud</h5>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="presenta_enfermedad" class="form-label">¿Presenta Alguna Enfermedad?</label>
                <select class="form-select" id="presenta_enfermedad" name="presenta_enfermedad" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="SI" <?= ($employee['presenta_enfermedad'] ?? '') === 'SI' ? 'selected' : '' ?>>Sí</option>
                    <option value="NO" <?= ($employee['presenta_enfermedad'] ?? '') === 'NO' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="condicion_medica" class="form-label">Indique su Condición Médica</label>
                <textarea class="form-control" id="condicion_medica" name="condicion_medica" rows="2" <?= $readonly ? 'readonly' : '' ?>><?= htmlspecialchars($employee['condicion_medica'] ?? '') ?></textarea>
            </div>
            <div class="col-md-4">
                <label for="medicamentos" class="form-label">¿Qué Medicamentos Consume?</label>
                <textarea class="form-control" id="medicamentos" name="medicamentos" rows="2" <?= $readonly ? 'readonly' : '' ?>><?= htmlspecialchars($employee['medicamentos'] ?? '') ?></textarea>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
            <select class="form-select" id="tipo_sangre" name="tipo_sangre" style="width: 200px;" <?= $readonly ? 'readonly' : '' ?>>
                <option value="">Seleccione</option>
                <?php foreach (get_blood_types() as $type): ?>
                <option value="<?= $type ?>" <?= ($employee['tipo_sangre'] ?? '') === $type ? 'selected' : '' ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    
    <!-- Section 7: Empleo -->
    <div class="char-section">
        <h5 class="char-section-title"><i class="bi bi-briefcase"></i> 7. Empleo</h5>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="trabaja" class="form-label">¿Trabaja?</label>
                <select class="form-select" id="trabaja" name="trabaja" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="SI" <?= ($employee['trabaja'] ?? '') === 'SI' ? 'selected' : '' ?>>Sí</option>
                    <option value="NO" <?= ($employee['trabaja'] ?? '') === 'NO' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="tipo_empleo" class="form-label">Indique Tipo de Empleo</label>
                <input type="text" class="form-control" id="tipo_empleo" name="tipo_empleo" value="<?= htmlspecialchars($employee['tipo_empleo'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
                <label for="medio_transporte" class="form-label">Medio de Transporte</label>
                <input type="text" class="form-control" id="medio_transporte" name="medio_transporte" value="<?= htmlspecialchars($employee['medio_transporte'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
        </div>
    </div>
    
    <!-- Section 8: CNE -->
    <div class="char-section">
        <h5 class="char-section-title"><i class="bi bi-card-checklist"></i> 8. CNE</h5>
        
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="inscrito_cne" class="form-label">¿Está Inscrito en el CNE?</label>
                <select class="form-select" id="inscrito_cne" name="inscrito_cne" <?= $readonly ? 'readonly' : '' ?>>
                    <option value="">Seleccione</option>
                    <option value="SI" <?= ($employee['inscrito_cne'] ?? '') === 'SI' ? 'selected' : '' ?>>Sí</option>
                    <option value="NO" <?= ($employee['inscrito_cne'] ?? '') === 'NO' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="centro_electoral" class="form-label">Centro Electoral (CNE)</label>
                <input type="text" class="form-control" id="centro_electoral" name="centro_electoral" value="<?= htmlspecialchars($employee['centro_electoral'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-4">
                <label for="comuna" class="form-label">Comuna</label>
                <input type="text" class="form-control" id="comuna" name="comuna" value="<?= htmlspecialchars($employee['comuna'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
        </div>
    </div>
    
    <!-- Section 9: Medidas -->
    <div class="char-section">
        <h5 class="char-section-title"><i class="bi bi-rulers"></i> 9. Medidas</h5>
        
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="talla_camisa" class="form-label">Talla Camisa</label>
                <input type="text" class="form-control" id="talla_camisa" name="talla_camisa" value="<?= htmlspecialchars($employee['talla_camisa'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-3">
                <label for="talla_zapatos" class="form-label">Talla Zapatos</label>
                <input type="text" class="form-control" id="talla_zapatos" name="talla_zapatos" value="<?= htmlspecialchars($employee['talla_zapatos'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-3">
                <label for="talla_pantalon" class="form-label">Talla Pantalón</label>
                <input type="text" class="form-control" id="talla_pantalon" name="talla_pantalon" value="<?= htmlspecialchars($employee['talla_pantalon'] ?? '') ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
            <div class="col-md-3">
                <label for="altura" class="form-label">Altura (m)</label>
                <input type="number" step="0.01" class="form-control" id="altura" name="altura" value="<?= $employee['altura'] ?? '' ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="peso" class="form-label">Peso (kg)</label>
                <input type="number" step="0.01" class="form-control" id="peso" name="peso" value="<?= $employee['peso'] ?? '' ?>" <?= $readonly ? 'readonly' : '' ?>>
            </div>
        </div>
    </div>
    
    <?php if (!$readonly): ?>
    <div class="d-grid gap-2 mb-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-check-circle"></i> Guardar Caracterización
        </button>
    </div>
    <?php endif; ?>
</form>

<?php include __DIR__ . '/../includes/footer.php'; ?>
