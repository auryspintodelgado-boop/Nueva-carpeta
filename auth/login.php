<?php
/**
 * Login Page
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

startSession();

$error = '';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    redirect_to_dashboard();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Por favor ingrese usuario y contraseña';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, username, password, role, status FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            if ($row['status'] === 'inactive') {
                $error = 'Su cuenta está desactivada. Contacte al administrador.';
            } elseif (password_verify($password, $row['password'])) {
                // Login successful
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['login_time'] = time();
                
                redirect_to_dashboard();
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'Usuario no encontrado';
        }
        $stmt->close();
    }
}

function redirect_to_dashboard() {
    $role = $_SESSION['role'];
    switch ($role) {
        case 'admin':
            header('Location: ../admin/index.php');
            break;
        case 'director':
            header('Location: ../directors/index.php');
            break;
        case 'employee':
            header('Location: ../employees/index.php');
            break;
        default:
            header('Location: index.php');
    }
    exit;
}

$pageTitle = 'Iniciar Sesión';
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            max-width: 420px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .login-logo {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-login {
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <div class="login-logo">
                            <i class="bi bi-building"></i>
                        </div>
                        <h3 class="mb-1">AURYS</h3>
                        <p class="mb-0 opacity-75">Sistema de Gestión de RRHH</p>
                    </div>
                    
                    <div class="login-body">
                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person"></i> Usuario
                                </label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Ingrese su usuario" required autofocus>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key"></i> Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Ingrese su contraseña" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                                </button>
                            </div>
                        </form>
                        
                        <div class="register-link">
                            <a href="register.php" class="text-decoration-none">
                                <i class="bi bi-person-plus"></i> ¿Nuevo empleado? Regístrate aquí
                            </a>
                        </div>
                    </div>
                    
                    <div class="login-footer">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Credenciales proporcionadas por el administrador
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>
