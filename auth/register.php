<?php
/**
 * Employee Self-Registration
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

startSession();

$error = '';
$success = '';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    $role = getUserRole();
    switch ($role) {
        case 'admin': header('Location: ../admin/index.php'); break;
        case 'director': header('Location: ../directors/index.php'); break;
        case 'employee': header('Location: ../employees/index.php'); break;
    }
    exit;
}

// Get departments for dropdown
$departments = [];
$conn = getDBConnection();
$result = $conn->query("SELECT id, name FROM departments ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $cedula = sanitize($_POST['cedula'] ?? '');
    $primer_nombre = sanitize($_POST['primer_nombre'] ?? '');
    $segundo_nombre = sanitize($_POST['segundo_nombre'] ?? '');
    $primer_apellido = sanitize($_POST['primer_apellido'] ?? '');
    $segundo_apellido = sanitize($_POST['segundo_apellido'] ?? '');
    $departamento_id = intval($_POST['departamento_id'] ?? 0);
    
    // Validation
    if (empty($username) || empty($password) || empty($cedula) || empty($primer_nombre) || 
        empty($primer_apellido) || empty($departamento_id)) {
        $error = 'Todos los campos marcados con * son requeridos';
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
            // Check if cedula exists
            $stmt = $conn->prepare("SELECT id FROM employees WHERE cedula = ?");
            $stmt->bind_param("s", $cedula);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'Ya existe un empleado con esta cédula';
            } else {
                // Create user account
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'employee')");
                $stmt->bind_param("ss", $username, $hashed_password);
                
                if ($stmt->execute()) {
                    $user_id = $conn->insert_id;
                    
                    // Create employee record
                    $stmt = $conn->prepare("INSERT INTO employees (user_id, department_id, cedula, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("issssss", $user_id, $departamento_id, $cedula, $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido);
                    
                    if ($stmt->execute()) {
                        $success = 'Registro exitoso. Por favor inicie sesión con sus credenciales.';
                    } else {
                        $error = 'Error al crear el registro de empleado';
                    }
                } else {
                    $error = 'Error al crear la cuenta de usuario';
                }
            }
        }
        $stmt->close();
    }
}

$pageTitle = 'Registro de Empleado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= getPageTitle($pageTitle) ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        
        .register-card {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        
        .register-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 25px;
            text-align: center;
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        .form-label {
            font-weight: 500;
        }
        
        .required::after {
            content: ' *';
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="register-card">
                    <div class="register-header">
                        <h3 class="mb-1"><i class="bi bi-person-plus"></i> Registro de Empleado</h3>
                        <p class="mb-0 opacity-75">Complete sus datos para crear su cuenta</p>
                    </div>
                    
                    <div class="card-body p-4">
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
                        <div class="text-center">
                            <a href="login.php" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Ir a Iniciar Sesión
                            </a>
                        </div>
                        <?php else: ?>
                        
                        <form method="POST" action="">
                            <h5 class="mb-3 text-primary">
                                <i class="bi bi-person-badge"></i> Datos de Cuenta
                            </h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="username" class="form-label required">Usuario</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="cedula" class="form-label required">Cédula de Identidad</label>
                                    <input type="text" class="form-control" id="cedula" name="cedula" placeholder="V-12345678" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label required">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" minlength="6" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label required">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>
                            
                            <h5 class="mb-3 text-primary mt-4">
                                <i class="bi bi-person"></i> Datos Personales
                            </h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="primer_nombre" class="form-label required">Primer Nombre</label>
                                    <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                                    <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="primer_apellido" class="form-label required">Primer Apellido</label>
                                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                                    <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="departamento_id" class="form-label required">Departamento</label>
                                <select class="form-select" id="departamento_id" name="departamento_id" required>
                                    <option value="">Seleccione un departamento</option>
                                    <?php foreach ($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Registrarse
                                </button>
                                <a href="login.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Volver al Login
                                </a>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
