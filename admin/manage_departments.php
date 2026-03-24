<?php
/**
 * Manage Departments
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireRole('admin');

$pageTitle = 'Departamentos';
$conn = getDBConnection();

$error = '';
$success = '';

// Handle form submission for adding/editing department
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $action = $_POST['action'] ?? 'add';
    
    if (empty($name)) {
        $error = 'El nombre del departamento es requerido';
    } else {
        if ($action === 'edit') {
            $dept_id = intval($_POST['dept_id']);
            $stmt = $conn->prepare("UPDATE departments SET name = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $description, $dept_id);
            if ($stmt->execute()) {
                $success = 'Departamento actualizado exitosamente';
            } else {
                $error = 'Error al actualizar el departamento';
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO departments (name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $description);
            if ($stmt->execute()) {
                $success = 'Departamento creado exitosamente';
            } else {
                $error = 'Error al crear el departamento';
            }
        }
        $stmt->close();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $dept_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM departments WHERE id = ?");
    $stmt->bind_param("i", $dept_id);
    if ($stmt->execute()) {
        $success = 'Departamento eliminado exitosamente';
    } else {
        $error = 'No se puede eliminar el departamento porque tiene empleados asociados';
    }
    $stmt->close();
}

// Get all departments with director info
$departments = $conn->query("
    SELECT d.*, u.username as director_name, 
           (SELECT COUNT(*) FROM employees WHERE department_id = d.id) as employee_count
    FROM departments d
    LEFT JOIN users u ON d.director_id = u.id
    ORDER BY d.name
");

// Get available directors (users with director role)
$directors = $conn->query("SELECT id, username FROM users WHERE role = 'director' ORDER BY username");
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-diagram-3"></i> Departamentos</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#deptModal">
            <i class="bi bi-plus-circle"></i> Nuevo Departamento
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
        <i class="bi bi-list"></i> Lista de Departamentos
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Director</th>
                        <th>Empleados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($dept = $departments->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($dept['name']) ?></strong></td>
                        <td><?= htmlspecialchars($dept['description'] ?? 'Sin descripción') ?></td>
                        <td>
                            <?php if ($dept['director_name']): ?>
                            <span class="badge bg-primary"><?= htmlspecialchars($dept['director_name']) ?></span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Sin asignar</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-info"><?= $dept['employee_count'] ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal" 
                                    data-dept-id="<?= $dept['id'] ?>" data-dept-name="<?= htmlspecialchars($dept['name']) ?>">
                                <i class="bi bi-person-check"></i> Asignar
                            </button>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#deptModal"
                                    data-dept-id="<?= $dept['id'] ?>" data-dept-name="<?= htmlspecialchars($dept['name']) ?>" 
                                    data-dept-desc="<?= htmlspecialchars($dept['description'] ?? '') ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <a href="?delete=<?= $dept['id'] ?>" class="btn btn-sm btn-danger btn-delete">
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

<!-- Department Modal -->
<div class="modal fade" id="deptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-diagram-3"></i> Departamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="action" id="modalAction" value="add">
                    <input type="hidden" name="dept_id" id="deptId" value="">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Departamento *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Director Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-badge"></i> Asignar Director</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="manage_directors.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="assign">
                    <input type="hidden" name="dept_id" id="assignDeptId" value="">
                    
                    <p>Asignar director al departamento: <strong id="assignDeptName"></strong></p>
                    
                    <div class="mb-3">
                        <label for="director_id" class="form-label">Seleccionar Director</label>
                        <select class="form-select" name="director_id" id="directorSelect">
                            <option value="">Sin asignar</option>
                            <?php while ($director = $directors->fetch_assoc()): ?>
                            <option value="<?= $director['id'] ?>"><?= htmlspecialchars($director['username']) ?></option>
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
    // Handle edit modal
    var deptModal = document.getElementById('deptModal');
    deptModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var deptId = button.getAttribute('data-dept-id');
        var deptName = button.getAttribute('data-dept-name');
        var deptDesc = button.getAttribute('data-dept-desc');
        
        if (deptId) {
            document.getElementById('modalAction').value = 'edit';
            document.getElementById('deptId').value = deptId;
            document.getElementById('name').value = deptName;
            document.getElementById('description').value = deptDesc || '';
        } else {
            document.getElementById('modalAction').value = 'add';
            document.getElementById('deptId').value = '';
            document.getElementById('name').value = '';
            document.getElementById('description').value = '';
        }
    });
    
    // Handle assign modal
    var assignModal = document.getElementById('assignModal');
    assignModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        document.getElementById('assignDeptId').value = button.getAttribute('data-dept-id');
        document.getElementById('assignDeptName').textContent = button.getAttribute('data-dept-name');
    });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
