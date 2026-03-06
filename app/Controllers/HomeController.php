<?php
/**
 * Controlador Principal / Home
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Persona.php';
require_once __DIR__ . '/../Models/Evaluacion.php';
require_once __DIR__ . '/../Models/Seguimiento.php';
require_once __DIR__ . '/../Models/Usuario.php';

class HomeController extends Controller {
    private $personaModel;
    private $evaluacionModel;
    private $seguimientoModel;
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->personaModel = new Persona();
        $this->evaluacionModel = new Evaluacion();
        $this->seguimientoModel = new Seguimiento();
        $this->usuarioModel = new Usuario();
    }
    
    /**
     * Página de inicio - Redirecciona según autenticación
     */
    public function index() {
        // Si está logueado, redirigir al dashboard
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            $this->redirect('/dashboard');
        } else {
            // Si no está logueado, redirigir al login
            $this->redirect('/login');
        }
    }
    
    /**
     * Dashboard - Panel principal
     */
    public function dashboard() {
        // Verificar autenticación
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            $this->redirect('/login');
        }
        
        // Obtener estadísticas
        $stats = [
            'total_personas' => $this->personaModel->count(),
            'total_evaluaciones' => $this->evaluacionModel->count(),
            'total_seguimientos' => $this->seguimientoModel->count(),
            'total_usuarios' => $this->usuarioModel->count()
        ];
        
        // Obtener seguimientos activos
        $seguimientosActivos = $this->seguimientoModel->getActivos();
        
        // Obtener evaluaciones pendientes
        $evaluacionesPendientes = $this->evaluacionModel->getPendientes();
        
        $this->view('home/dashboard', [
            'stats' => $stats,
            'seguimientosActivos' => $seguimientosActivos,
            'evaluacionesPendientes' => $evaluacionesPendientes,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Acerca de
     */
    public function about() {
        $this->view('home/about', [
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Página no encontrada
     */
    public function notFound() {
        http_response_code(404);
        $this->view('home/notFound', [
            'message' => $this->getMessage()
        ]);
    }
}
