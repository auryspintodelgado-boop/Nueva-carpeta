<?php
/**
 * Controlador de Persona
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Persona.php';

class PersonaController extends Controller {
    private $personaModel;
    
    /**
     * Mapeo de nombres de campos del formulario a la base de datos
     */
    private $fieldMapping = [
        'nacionalidad' => 'nacionalidad_id',
        'sexo' => 'sexo_id',
        'carrera' => 'carrera_id',
        'sede' => 'sede_id',
        'nombre_universidad' => 'universidad_id',
        'estado_residencia' => 'estado_id',
        'municipio' => 'municipio_id',
        'parroquia' => 'parroquia_id',
        'estado_civil' => 'estado_civil_id',
        'tipo_discapacidad' => 'tipo_discapacidad_id',
        'condicion_medica' => 'condicion_medica_id',
        'tipo_sangre' => 'tipo_sangre_id',
        'tipo_empleo' => 'tipo_empleo_id',
        'medio_transporte' => 'medio_transporte_id'
    ];
    
    public function __construct() {
        parent::__construct();
        $this->personaModel = new Persona();
    }
    
    /**
     * Mapear nombres de campos del formulario a la base de datos
     */
    private function mapFields($data) {
        foreach ($this->fieldMapping as $oldName => $newName) {
            if (isset($data[$oldName]) && $data[$oldName] !== '') {
                $data[$newName] = $data[$oldName];
                unset($data[$oldName]);
            }
        }
        return $data;
    }
    
    /**
     * Index - Listar todas las personas
     */
    public function index() {
        $page = $this->getInputValue('page', 1);
        $search = $this->getInputValue('search', '');
        
        if (!empty($search)) {
            $personas = $this->personaModel->search($search);
        } else {
            $personas = $this->personaModel->paginate($page, Config::ITEMS_PER_PAGE);
        }
        
        $total = $this->personaModel->count();
        $totalPages = ceil($total / Config::ITEMS_PER_PAGE);
        
        $this->view('personas/index', [
            'personas' => $personas,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create() {
        $this->view('personas/create', [
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Guardar nueva persona
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/personas/create');
        }
        
        $data = $this->getInput();
        
        // Mapear nombres de campos del formulario a la base de datos
        $data = $this->mapFields($data);
        
        // Validar datos requeridos
        $rules = [
            'cedula' => 'required|min:5',
            'primer_nombre' => 'required|min:2',
            'primer_apellido' => 'required|min:2',
            'correo_electronico' => 'email'
        ];
        
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            $this->view('personas/create', [
                'errors' => $errors,
                'data' => $data
            ]);
            return;
        }
        
        // Calcular edad si hay fecha de nacimiento
        if (!empty($data['fecha_nacimiento'])) {
            $data['edad'] = Persona::calcularEdad($data['fecha_nacimiento']);
        }
        
        // Valores por defecto
        $data['estado_registro_id'] = $data['estado_registro_id'] ?? 1;
        
        try {
            $id = $this->personaModel->create($data);
            $this->redirectWith('/personas', 'Persona registrada exitosamente', 'success');
        } catch (Exception $e) {
            $this->view('personas/create', [
                'errors' => ['general' => 'Error al guardar: ' . $e->getMessage()],
                'data' => $data
            ]);
        }
    }
    
    /**
     * Mostrar detalles de una persona
     */
    public function show($id) {
        $persona = $this->personaModel->find($id);
        
        if (!$persona) {
            $this->error('Persona no encontrada');
        }
        
        $this->view('personas/show', [
            'persona' => $persona
        ]);
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit($id) {
        $persona = $this->personaModel->find($id);
        
        if (!$persona) {
            $this->error('Persona no encontrada');
        }
        
        $this->view('personas/edit', [
            'persona' => $persona,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Actualizar persona
     */
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect("/personas/edit/{$id}");
        }
        
        $data = $this->getInput();
        
        // Mapear nombres de campos del formulario a la base de datos
        $data = $this->mapFields($data);
        
        // Validar datos requeridos
        $rules = [
            'cedula' => 'required|min:5',
            'primer_nombre' => 'required|min:2',
            'primer_apellido' => 'required|min:2',
            'correo_electronico' => 'email'
        ];
        
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            $this->view('personas/edit', [
                'errors' => $errors,
                'persona' => array_merge($this->personaModel->find($id), $data)
            ]);
            return;
        }
        
        // Calcular edad si hay fecha de nacimiento
        if (!empty($data['fecha_nacimiento'])) {
            $data['edad'] = Persona::calcularEdad($data['fecha_nacimiento']);
        }
        
        try {
            $this->personaModel->update($id, $data);
            $this->redirectWith("/personas/edit/{$id}", 'Persona actualizada exitosamente', 'success');
        } catch (Exception $e) {
            $this->view('personas/edit', [
                'errors' => ['general' => 'Error al actualizar: ' . $e->getMessage()],
                'persona' => array_merge($this->personaModel->find($id), $data)
            ]);
        }
    }
    
    /**
     * Eliminar persona
     */
    public function delete($id) {
        try {
            $this->personaModel->delete($id);
            $this->redirectWith('/personas', 'Persona eliminada exitosamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/personas', 'Error al eliminar: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Estadísticas y dashboard
     */
    public function stats() {
        $stats = $this->personaModel->getEstadisticas();
        $promedioEdad = $this->personaModel->getPromedioEdad();
        
        $this->view('personas/stats', [
            'stats' => $stats,
            'promedioEdad' => $promedioEdad
        ]);
    }
    
    /**
     * Exportar datos
     */
    public function export() {
        $personas = $this->personaModel->all();
        
        // Generar CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="personas_export.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Encabezados
        fputcsv($output, [
            'ID', 'Cédula', 'Nombres', 'Apellidos', 'Sexo', 
            'Edad', 'Correo', 'Teléfono', 'Carrera', 'Universidad'
        ]);
        
        // Datos
        foreach ($personas as $p) {
            fputcsv($output, [
                $p['id'],
                $p['cedula'],
                $p['primer_nombre'] . ' ' . $p['segundo_nombre'],
                $p['primer_apellido'] . ' ' . $p['segundo_apellido'],
                $p['sexo'],
                $p['edad'],
                $p['correo_electronico'],
                $p['telefono_1'],
                $p['carrera'],
                $p['nombre_universidad']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
