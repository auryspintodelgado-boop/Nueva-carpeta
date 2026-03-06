<?php
/**
 * Controlador de Seguimientos
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Seguimiento.php';
require_once __DIR__ . '/../Models/Persona.php';

class SeguimientoController extends Controller {
    private $seguimientoModel;
    private $personaModel;
    
    public function __construct() {
        parent::__construct();
        $this->seguimientoModel = new Seguimiento();
        $this->personaModel = new Persona();
    }
    
    /**
     * Obtener tipos de seguimiento desde la base de datos
     */
    private function getTiposSeguimiento() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM tipos_seguimiento WHERE activo = 1 ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }
    
    /**
     * Index - Listar todos los seguimientos
     */
    public function index() {
        $page = $this->getInputValue('page', 1);
        $search = $this->getInputValue('search', '');
        $estado = $this->getInputValue('estado', '');
        
        $seguimientos = $this->seguimientoModel->paginate($page, Config::ITEMS_PER_PAGE);
        $total = $this->seguimientoModel->count();
        $totalPages = ceil($total / Config::ITEMS_PER_PAGE);
        
        $tiposSeguimiento = $this->getTiposSeguimiento();
        
        $this->view('seguimientos/index', [
            'seguimientos' => $seguimientos,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'estado' => $estado,
            'tiposSeguimiento' => $tiposSeguimiento,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create() {
        $personas = $this->personaModel->all();
        $tiposSeguimiento = $this->getTiposSeguimiento();
        
        $this->view('seguimientos/create', [
            'personas' => $personas,
            'tiposSeguimiento' => $tiposSeguimiento,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Guardar nuevo seguimiento
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/seguimientos/create');
        }
        
        $data = $this->getInput();
        
        // Validar datos requeridos
        $rules = [
            'persona_id' => 'required|numeric',
            'tipo_seguimiento_id' => 'required|numeric',
            'fecha_seguimiento' => 'required',
            'descripcion' => 'required'
        ];
        
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            $this->redirect('/seguimientos/create');
        }
        
        try {
            $this->seguimientoModel->create($data);
            $this->redirectWith('/seguimientos', 'Seguimiento creado correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/seguimientos/create', 'Error al crear seguimiento: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit($id = null) {
        if (!$id) {
            $this->redirect('/seguimientos');
        }
        
        $seguimiento = $this->seguimientoModel->find($id);
        
        if (!$seguimiento) {
            $this->redirectWith('/seguimientos', 'Seguimiento no encontrado', 'error');
        }
        
        $personas = $this->personaModel->all();
        $tiposSeguimiento = $this->getTiposSeguimiento();
        
        $this->view('seguimientos/edit', [
            'seguimiento' => $seguimiento,
            'personas' => $personas,
            'tiposSeguimiento' => $tiposSeguimiento,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Actualizar seguimiento
     */
    public function update($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/seguimientos');
        }
        
        $data = $this->getInput();
        
        // Validar datos requeridos
        $rules = [
            'persona_id' => 'required|numeric',
            'tipo_seguimiento_id' => 'required|numeric',
            'fecha_seguimiento' => 'required',
            'descripcion' => 'required'
        ];
        
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            $this->redirect('/seguimientos/edit/' . $id);
        }
        
        try {
            $this->seguimientoModel->update($id, $data);
            $this->redirectWith('/seguimientos', 'Seguimiento actualizado correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/seguimientos/edit/' . $id, 'Error al actualizar seguimiento: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Eliminar seguimiento
     */
    public function delete($id = null) {
        if (!$id) {
            $this->redirect('/seguimientos');
        }
        
        try {
            $this->seguimientoModel->delete($id);
            $this->redirectWith('/seguimientos', 'Seguimiento eliminado correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/seguimientos', 'Error al eliminar seguimiento: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Ver detalles de un seguimiento
     */
    public function show($id = null) {
        if (!$id) {
            $this->redirect('/seguimientos');
        }
        
        $seguimiento = $this->seguimientoModel->find($id);
        
        if (!$seguimiento) {
            $this->redirectWith('/seguimientos', 'Seguimiento no encontrado', 'error');
        }
        
        $this->view('seguimientos/view', [
            'seguimiento' => $seguimiento,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Obtener seguimientos de una persona
     */
    public function porPersona($personaId = null) {
        if (!$personaId) {
            $this->json(['error' => 'ID de persona requerido'], 400);
        }
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            SELECT s.*, ts.nombre as tipo_nombre
            FROM seguimientos s
            JOIN tipos_seguimiento ts ON s.tipo_seguimiento_id = ts.id
            WHERE s.persona_id = ?
            ORDER BY s.fecha_seguimiento DESC
        ");
        $stmt->execute([$personaId]);
        $seguimientos = $stmt->fetchAll();
        
        $this->json($seguimientos);
    }
    
    /**
     * Obtener seguimientos activos/pendientes
     */
    public function activos() {
        $seguimientos = $this->seguimientoModel->getActivos();
        
        $this->view('seguimientos/activos', [
            'seguimientos' => $seguimientos,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Cambiar estado de un seguimiento
     */
    public function cambiarEstado($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/seguimientos');
        }
        
        $estado = $this->getInputValue('estado', '');
        
        try {
            $this->seguimientoModel->update($id, ['estado_seguimiento' => $estado]);
            $this->redirectWith('/seguimientos', 'Estado actualizado correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/seguimientos', 'Error al actualizar estado: ' . $e->getMessage(), 'error');
        }
    }
}
