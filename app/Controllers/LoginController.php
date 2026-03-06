<?php
/**
 * Controlador de Autenticación
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Usuario.php';

class LoginController extends Controller {
    protected $authRequired = false;
    
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Mostrar formulario de login
     */
    public function index() {
        // Si ya está logueado, redirigir al dashboard
        if (isset($_SESSION['usuario_id'])) {
            $this->redirect('/dashboard');
        }
        
        $this->view('login/index', [
            'message' => $this->getMessage(),
            'error' => $_GET['error'] ?? null
        ]);
    }
    
    /**
     * Procesar login
     */
    public function login() {
        if (!$this->isPost()) {
            $this->redirect('/login');
        }
        
        $username = $this->getInputValue('username', '');
        $password = $this->getInputValue('password', '');
        
        if (empty($username) || empty($password)) {
            $this->redirectWith('/login', 'Por favor ingrese usuario y contraseña', 'error');
        }
        
        $usuario = $this->usuarioModel->authenticate($username, $password);
        
        if ($usuario) {
            // Iniciar sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
            $_SESSION['rol_id'] = $usuario['rol_id'];
            $_SESSION['correo'] = $usuario['correo'];
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();
            
            // Redirigir al dashboard
            $this->redirect('/dashboard');
        } else {
            $this->redirectWith('/login', 'Usuario o contraseña incorrectos', 'error');
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        // Destruir sesión
        session_unset();
        session_destroy();
        
        $this->redirectWith('/login', 'Sesión cerrada correctamente', 'success');
    }
}
