<?php
/**
 * Manage Directors
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('admin');

$pageTitle = 'Directores';
$conn = getDBConnection();

$error = '';
$success = '';

// Handle form submission for creating director
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create_director') {
        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $nombres = sanitize($_POST['nombres'] ?? '');
        $apellidos = sanitize($_POST['apellidos'] ?? '');
        
        if (empty($username) || empty($password) || empty($nombres) || empty($apellidos)) {
            $error = 'Todos los campos son requeridos';
        } elseif ($password !== $confirm_password) {
            $error = 'Las contraseñas no coinciden';
        } elseif (strlen($password) < 6) {
            $error = 'La contraseña debe tener al menos 6 caracteres';
        } else {
            // Check if username exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'El nombre de usuario ya existe';
            } else {
                // Create director user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'director')");
                $stmt->bind_param("ss", $username, $hashed_password);
                
                if ($stmt->execute()) {
                    $success = 'Director creado exitosamente. Ahora puede asignarlo a un departamento.';
                } else {
                    $error = 'Error al crear el director';
                }
            }
            $stmt->close();
        }
    } elseif ($action === 'assign') {
        $dept_id = intval($_POST['dept_id']);
        $director_id = intval($_POST['director_id']);
        
        if ($director_id > 0) {
            $stmt = $conn->prepare("UPDATE departments SET director_id = ? WHERE id = ?");
            $stmt->bind_param("ii", $director_id, $dept_id);
        } else {
            $stmt = $conn->prepare("UPDATE departments SET director_id = NULL WHERE id = ?");
            $stmt->bind_param("i", $dept_id);
        }
        
        if ($stmt->execute()) {
            $success = 'Director asignado exitosamente';
        } else {
            $error = 'Error al asignar el director';
        }
        $stmt->close();
    }
}

// Handle delete director
if (isset($_GET['delete'])) {
    $director_id = intval($_GET['delete']);
    
    // Check if director has department
    $result = $conn->query("SELECT id FROM departments WHERE director_id = $director_id");
    if ($result->num_rows > 0) {
        $error = 'Primero debe desasignar el director del departamento';
    } else {
        $stmt = $conn->prepare("UPDATE users SET status = 'inactive' WHERE id = ? AND role = 'director'");
        $stmt->bind_param("i", $director_id);
        if ($stmt->execute()) {
            $success = 'Director desactivado exitosamente';
        }
        $stmt->close();
    }
}

// Get all directors
$directors = $conn->query("
    SELECT u.id, u.username, u.status, u.created_at, d.name as dept_name, d.id as dept_id
    FROM users u
    LEFT JOIN departments d ON u.id = d.director_id
    WHERE u.role = 'director'
    ORDER BY u.username
");

// Get departments without directors
$availableDepts = $conn->query("
    SELECT id, name FROM departments WHERE director_id IS NULL ORDER BY name
");
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person-badge"></i> Directores</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#directorModal">
            <i class="bi bi-plus-circle"></i> Nuevo Director
        </button>
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

<div class="card mb-4">
    <div class="card-header">
        <i class="bi bi-list"></i> Lista de Directores
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Departamento Asignado</th>
                        <th>Estado</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($dir = $directors->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($dir['username']) ?></strong></td>
                        <td>
                            <?php if ($dir['dept_name']): ?>
                            <span class="badge bg-primary"><?= htmlspecialchars($dir['dept_name']) ?></span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Sin asignar</span>
                            <button class="btn btn-xs btn-link" data-bs-toggle="modal" data-bs-target="#assignDeptModal"
                                    data-director-id="<?= $dir['id'] ?>" data-director-name="<?= htmlspecialchars($dir['username']) ?>">
                                Asignar
                            </button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?= $dir['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= $dir['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td><?= format_date($dir['created_at']) ?></td>
                        <td>
                            <?php if ($dir['dept_name']): ?>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#assignDeptModal"
                                    data-director-id="<?= $dir['id'] ?>" data-director-name="<?= htmlspecialchars($dir['username']) ?>"
                                    data-dept-id="<?= $dir['dept_id'] ?>">
                                <i class="bi bi-pencil"></i> Cambiar
                            </button>
                            <?php endif; ?>
                            <a href="?delete=<?= $dir['id'] ?>" class="btn btn-sm btn-danger btn-delete">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Director Modal -->
<div class="modal fade" id="directorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> Nuevo Director</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="create_director">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario *</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres *</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos *</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Crear Director
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Department Modal -->
<div class="modal fade" id="assignDeptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-diagram-3"></i> Asignar Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" value="assign">
                    <input type="hidden" name="dept_id" id="assignDeptId" value="">
                    
                    <p>Asignar departamento al director: <strong id="assignDirectorName"></strong></p>
                    
                    <div class="mb-3">
                        <label for="departmentSelect" class="form-label">Seleccionar Departamento</label>
                        <select class="form-select" name="director_id" id="directorIdSelect">
                            <option value="">Sin asignar</option>
                            <?php while ($dept = $availableDepts->fetch_assoc()): ?>
                            <option value=""><?= htmlspecialchars($dept['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Asignar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var assignModal = document.getElementById('assignDeptModal');
    assignModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        document.getElementById('assignDirectorName').textContent = button.getAttribute('data-director-name');
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
