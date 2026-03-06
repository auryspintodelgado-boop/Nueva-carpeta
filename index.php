<?php
/**
 * Entry point del Sistema de Caracterización AURYS
 */

// Iniciar sesión
session_start();

// Cargar configuración
require_once __DIR__ . '/config/database.php';

// Obtener la URL solicitada
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

// Limpiar la URI
$path = str_replace($scriptName, '', $requestUri);
$path = trim($path, '/');
$path = preg_replace('/\?.*/', '', $path);

// Si está vacío o index.php, usar HomeController
if (empty($path) || $path === 'index.php' || $path === '') {
    $controllerName = 'Home';
    $method = 'index';
    $params = [];
} else {
    // Parsear la ruta
    $parts = explode('/', $path);
    $controllerName = isset($parts[0]) && !empty($parts[0]) ? ucfirst($parts[0]) : 'Home';
    $method = isset($parts[1]) && !empty($parts[1]) ? $parts[1] : 'index';
    $params = array_slice($parts, 2);
}

// Mapear controladores
$controllerMap = [
    'home' => 'HomeController',
    'login' => 'LoginController',
    'persona' => 'PersonaController',
    'personas' => 'PersonaController',
    'evaluacion' => 'EvaluacionController',
    'evaluaciones' => 'EvaluacionController',
    'seguimiento' => 'SeguimientoController',
    'seguimientos' => 'SeguimientoController',
    'usuario' => 'UsuarioController',
    'usuarios' => 'UsuarioController'
];

// Determinar el controlador
$controllerFileName = strtolower($controllerName);
if (isset($controllerMap[$controllerFileName])) {
    $controllerClass = $controllerMap[$controllerFileName];
} else {
    $controllerClass = $controllerName . 'Controller';
}

// Ruta al archivo del controlador
$controllerFile = __DIR__ . '/app/Controllers/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Verificar que la clase existe
    if (class_exists($controllerClass)) {
        // Instanciar controlador
        $controller = new $controllerClass();
        
        // Verificar que el método existe
        if (method_exists($controller, $method)) {
            // Llamar al método con parámetros
            call_user_func_array([$controller, $method], $params);
        } else {
            // Método no encontrado - intentar con 'index'
            if (method_exists($controller, 'index')) {
                call_user_func_array([$controller, 'index'], $params);
            } else {
                http_response_code(404);
                echo "<h1>404 - Método no encontrado</h1>";
                echo "<p>El método '{$method}' no existe en el controlador '{$controllerClass}'</p>";
            }
        }
    } else {
        http_response_code(500);
        echo "<h1>500 - Error de Servidor</h1>";
        echo "<p>La clase '{$controllerClass}' no existe</p>";
    }
} else {
    // Controlador no encontrado
    http_response_code(404);
    echo "<h1>404 - Página No Encontrada</h1>";
    echo "<p>El controlador '{$controllerClass}' no existe</p>";
}
