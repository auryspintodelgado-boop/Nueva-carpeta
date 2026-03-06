<?php
/**
 * Controlador de Evaluaciones
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Evaluacion.php';
require_once __DIR__ . '/../Models/Persona.php';

class EvaluacionController extends Controller {
    private $evaluacionModel;
    private $personaModel;
    
    public function __construct() {
        parent::__construct();
        $this->evaluacionModel = new Evaluacion();
        $this->personaModel = new Persona();
    }
    
    /**
     * Obtener tipos de evaluación desde la base de datos
     */
    private function getTiposEvaluacion() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM tipos_evaluacion WHERE activo = 1 ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }
    
    /**
     * Index - Listar todas las evaluaciones
     */
    public function index() {
        $page = $this->getInputValue('page', 1);
        $search = $this->getInputValue('search', '');
        $tipo = $this->getInputValue('tipo', '');
        
        $evaluaciones = $this->evaluacionModel->paginate($page, Config::ITEMS_PER_PAGE);
        $total = $this->evaluacionModel->count();
        $totalPages = ceil($total / Config::ITEMS_PER_PAGE);
        
        $tiposEvaluacion = $this->getTiposEvaluacion();
        
        $this->view('evaluaciones/index', [
            'evaluaciones' => $evaluaciones,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'tipo' => $tipo,
            'tiposEvaluacion' => $tiposEvaluacion,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create() {
        $personas = $this->personaModel->all();
        $tiposEvaluacion = $this->getTiposEvaluacion();
        
        $this->view('evaluaciones/create', [
            'personas' => $personas,
            'tiposEvaluacion' => $tiposEvaluacion,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Guardar nueva evaluación
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/evaluaciones/create');
        }
        
        $data = $this->getInput();
        
        // Validar datos requeridos
        $rules = [
            'persona_id' => 'required|numeric',
            'tipo_evaluacion_id' => 'required|numeric',
            'fecha_evaluacion' => 'required'
        ];
        
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            $this->redirect('/evaluaciones/create');
        }
        
        try {
            $this->evaluacionModel->create($data);
            $this->redirectWith('/evaluaciones', 'Evaluación creada correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/evaluaciones/create', 'Error al crear evaluación: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit($id = null) {
        if (!$id) {
            $this->redirect('/evaluaciones');
        }
        
        $evaluacion = $this->evaluacionModel->find($id);
        
        if (!$evaluacion) {
            $this->redirectWith('/evaluaciones', 'Evaluación no encontrada', 'error');
        }
        
        $personas = $this->personaModel->all();
        $tiposEvaluacion = $this->getTiposEvaluacion();
        
        $this->view('evaluaciones/edit', [
            'evaluacion' => $evaluacion,
            'personas' => $personas,
            'tiposEvaluacion' => $tiposEvaluacion,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Actualizar evaluación
     */
    public function update($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/evaluaciones');
        }
        
        $data = $this->getInput();
        
        // Validar datos requeridos
        $rules = [
            'persona_id' => 'required|numeric',
            'tipo_evaluacion_id' => 'required|numeric',
            'fecha_evaluacion' => 'required'
        ];
        
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            $this->redirect('/evaluaciones/edit/' . $id);
        }
        
        try {
            $this->evaluacionModel->update($id, $data);
            $this->redirectWith('/evaluaciones', 'Evaluación actualizada correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/evaluaciones/edit/' . $id, 'Error al actualizar evaluación: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Eliminar evaluación
     */
    public function delete($id = null) {
        if (!$id) {
            $this->redirect('/evaluaciones');
        }
        
        try {
            $this->evaluacionModel->delete($id);
            $this->redirectWith('/evaluaciones', 'Evaluación eliminada correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/evaluaciones', 'Error al eliminar evaluación: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Ver detalles de una evaluación
     */
    public function show($id = null) {
        if (!$id) {
            $this->redirect('/evaluaciones');
        }
        
        $evaluacion = $this->evaluacionModel->find($id);
        
        if (!$evaluacion) {
            $this->redirectWith('/evaluaciones', 'Evaluación no encontrada', 'error');
        }
        
        $this->view('evaluaciones/view', [
            'evaluacion' => $evaluacion,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Obtener evaluaciones de una persona
     */
    public function porPersona($personaId = null) {
        if (!$personaId) {
            $this->json(['error' => 'ID de persona requerido'], 400);
        }
        
        $evaluaciones = $this->evaluacionModel->getHistorialCompleto($personaId);
        $this->json($evaluaciones);
    }
    
    /**
     * Obtener evaluaciones pendientes
     */
    public function pendientes() {
        $evaluaciones = $this->evaluacionModel->getPendientes();
        
        $this->view('evaluaciones/pendientes', [
            'evaluaciones' => $evaluaciones,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Estadísticas de evaluaciones
     */
    public function estadisticas() {
        $promedios = $this->evaluacionModel->getPromedioPorTipo();
        
        $this->view('evaluaciones/estadisticas', [
            'promedios' => $promedios,
            'message' => $this->getMessage()
        ]);
    }
}
