<?php

namespace App\Controllers;

use App\Models\EvaluacionModel;
use App\Models\PersonaModel;
use App\Models\UsuarioModel;
use App\Models\DepartamentoModel;

class EvaluacionController extends BaseController
{
    protected $evaluacionModel;
    protected $personaModel;
    protected $usuarioModel;
    protected $departamentoModel;

    public function __construct()
    {
        $this->evaluacionModel = new EvaluacionModel();
        $this->personaModel = new PersonaModel();
        $this->usuarioModel = new UsuarioModel();
        $this->departamentoModel = new DepartamentoModel();
    }

    /**
     * Lista todas las evaluaciones - Redirige según el rol
     */
    public function index()
    {
        $userId = session()->get('id');
        
        // Si no hay usuario logueado, mostrar vista original
        if (!$userId) {
            return $this->originalIndex();
        }
        
        $usuario = $this->usuarioModel->find($userId);
        
        // Si no se encuentra el usuario, mostrar vista original
        if (!$usuario) {
            return $this->originalIndex();
        }
        
        // Si es ADMIN, mostrar panel general de evaluaciones
        if ($usuario['rol'] === 'ADMIN') {
            return $this->adminIndex();
        }
        
        // Si es DIRECTOR, redirigir al sistema de evaluación por departamento
        if ($usuario['rol'] === 'DIRECTOR') {
            return redirect()->to('/evaluaciones/director');
        }
        
        // Vista original para otros roles
        return $this->originalIndex();
    }

    /**
     * Vista original de evaluaciones
     */
    private function originalIndex()
    {
        $personaId = $this->request->getGet('persona_id');
        
        if ($personaId) {
            $evaluaciones = $this->evaluacionModel->getEvaluacionesPorPersona($personaId);
            $persona = $this->personaModel->find($personaId);
            $titulo = 'Evaluaciones de: ' . ($persona ? $persona['primer_nombre'] . ' ' . $persona['primer_apellido'] : 'Desconocido');
        } else {
            $evaluaciones = $this->evaluacionModel->orderBy('fecha_evaluacion', 'DESC')->findAll();
            $titulo = 'Lista de Evaluaciones';
            $persona = null;
        }

        $data = [
            'title'      => $titulo,
            'evaluaciones' => $evaluaciones,
            'persona_id'  => $personaId,
            'persona'     => $persona,
        ];

        return view('evaluaciones/index', $data);
    }

    /**
     * Panel de ADMIN - Lista de evaluaciones por departamento
     */
    private function adminIndex()
    {
        $departamentos = $this->departamentoModel->getDepartamentosActivos();
        $mesActual = date('Y-m');
        
        $data = [
            'title' => 'Panel de Evaluaciones - Administración',
            'departamentos' => $departamentos,
            'mesActual' => $mesActual,
        ];

        return view('evaluaciones/admin/index', $data);
    }

    /**
     * Muestra el formulario para crear una evaluación - REDIRIGE AL NUEVO SISTEMA
     */
    public function create()
    {
        // Usar 'id' que es como se guarda en AuthController
        $userId = session()->get('id');
        
        // Si no hay usuario, usar el sistema original con datos
        if (!$userId) {
            $personas = $this->personaModel->getPersonasActivas();
            $data = [
                'title' => 'Nueva Evaluación',
                'personas' => $personas,
                'persona_id' => null,
            ];
            return view('evaluaciones/create', $data);
        }
        
        $usuario = $this->usuarioModel->find($userId);
        
        // Si no se encuentra el usuario, usar el sistema original
        if (!$usuario) {
            $personas = $this->personaModel->getPersonasActivas();
            $data = [
                'title' => 'Nueva Evaluación',
                'personas' => $personas,
                'persona_id' => null,
            ];
            return view('evaluaciones/create', $data);
        }
        
        // Debug: mostrar el rol del usuario
        log_message('debug', 'EvaluacionController::create - Usuario rol: ' . ($usuario['rol'] ?? 'NULL'));
        
        // Si es ADMIN o DIRECTOR, usar el nuevo sistema
        if ($usuario['rol'] === 'ADMIN' || $usuario['rol'] === 'DIRECTOR') {
            // Para ADMIN, verificar si tiene departamento seleccionado en sesión
            if ($usuario['rol'] === 'ADMIN') {
                $departamentoId = session()->get('departamento_seleccionado');
                if (!$departamentoId && $usuario['departamento_id']) {
                    $departamentoId = $usuario['departamento_id'];
                }
                // Ir al sistema de director (que ahora muestra todas las personas para ADMIN)
                return redirect()->to('/evaluaciones/director');
            }
            
            // Para DIRECTOR, usar su departamento
            if ($usuario['rol'] === 'DIRECTOR' && $usuario['departamento_id']) {
                return redirect()->to('/evaluaciones/director/create?departamento=' . $usuario['departamento_id']);
            }
            
            // Si DIRECTOR no tiene departamento asignado
            if ($usuario['rol'] === 'DIRECTOR') {
                return redirect()->to('/home')->with('error', 'No tiene un departamento asignado.');
            }
            
            // Por defecto, ir al sistema de director
            return redirect()->to('/evaluaciones/director');
        }
        
        // Otros roles usan el sistema original
        $personas = $this->personaModel->getPersonasActivas();
        $data = [
            'title' => 'Nueva Evaluación',
            'personas' => $personas,
            'persona_id' => null,
        ];
        return view('evaluaciones/create', $data);
    }

    /**
     * Guarda una nueva evaluación
     */
    public function store()
    {
        $userId = session()->get('id');
        $usuario = $this->usuarioModel->find($userId);
        
        // Si es ADMIN o DIRECTOR, usar el nuevo sistema - Redirigir a la vista de creación
        // (No se puede hacer redirect con POST, por eso vamos a la página de create)
        if ($usuario && ($usuario['rol'] === 'ADMIN' || $usuario['rol'] === 'DIRECTOR')) {
            $departamentoId = $usuario['departamento_id'] ?? $this->request->getGet('departamento');
            return redirect()->to('/evaluaciones/director/create' . ($departamentoId ? '?departamento=' . $departamentoId : ''));
        }

        $rules = [
            'persona_id'       => 'required|is_natural_no_zero',
            'tipo_evaluacion' => 'required',
            'fecha_evaluacion' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'persona_id'       => $this->request->getPost('persona_id'),
            'tipo_evaluacion'  => $this->request->getPost('tipo_evaluacion'),
            'titulo'           => $this->request->getPost('titulo'),
            'fecha_evaluacion' => $this->request->getPost('fecha_evaluacion'),
            'resultado'        => $this->request->getPost('resultado'),
            'observaciones'    => $this->request->getPost('observaciones'),
            'calificacion'     => $this->request->getPost('calificacion'),
            'evaluador_id'     => $userId,
        ];

        $this->evaluacionModel->insert($data);

        return redirect()->to('/evaluaciones')
            ->with('success', 'Evaluación guardada exitosamente');
    }

    /**
     * Verifica que el usuario tenga acceso a la evaluación
     * Los directores solo pueden acceder a evaluaciones de su departamento
     */
    private function verificarAccesoEvaluacion($evaluacionId)
    {
        $userId = session()->get('id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $usuario = $this->usuarioModel->find($userId);
        if (!$usuario) {
            return redirect()->to('/login');
        }

        // ADMIN tiene acceso a todo
        if ($usuario['rol'] === 'ADMIN') {
            return true;
        }

        // DIRECTOR solo puede acceder a evaluaciones de su departamento
        if ($usuario['rol'] === 'DIRECTOR') {
            $departamentoId = $usuario['departamento_id'] ?? null;
            if (!$departamentoId) {
                return redirect()->to('/home')->with('error', 'No tiene un departamento asignado.');
            }

            // Obtener la evaluación
            $evaluacion = $this->evaluacionModel->find($evaluacionId);
            if (!$evaluacion) {
                return redirect()->to('/evaluaciones')->with('error', 'Evaluación no encontrada');
            }

            // Verificar que la persona evaluada pertenece al departamento del director
            $persona = $this->personaModel->find($evaluacion['persona_id']);
            if (!$persona || $persona['departamento_id'] != $departamentoId) {
                return redirect()->to('/evaluaciones/director')->with('error', 'No tiene permiso para ver esta evaluación.');
            }

            return true;
        }

        // Otros roles no tienen acceso
        return redirect()->to('/home')->with('error', 'Acceso denegado.');
    }

    /**
     * Muestra una evaluación específica
     */
    public function show($id)
    {
        // Verificar permisos de acceso
        $accesoPermitido = $this->verificarAccesoEvaluacion($id);
        if ($accesoPermitido !== true) {
            return $accesoPermitido; // Retorna redirect si no tiene acceso
        }

        $evaluacion = $this->evaluacionModel->find($id);

        if (!$evaluacion) {
            return redirect()->to('/evaluaciones')->with('error', 'Evaluación no encontrada');
        }

        $persona = $this->personaModel->find($evaluacion['persona_id']);

        $data = [
            'title'      => 'Detalle de Evaluación',
            'evaluacion' => $evaluacion,
            'persona'   => $persona,
        ];

        return view('evaluaciones/show', $data);
    }

    /**
     * Muestra el formulario para editar una evaluación
     */
    public function edit($id)
    {
        // Verificar permisos de acceso
        $accesoPermitido = $this->verificarAccesoEvaluacion($id);
        if ($accesoPermitido !== true) {
            return $accesoPermitido; // Retorna redirect si no tiene acceso
        }

        $evaluacion = $this->evaluacionModel->find($id);

        if (!$evaluacion) {
            return redirect()->to('/evaluaciones')->with('error', 'Evaluación no encontrada');
        }

        $personas = $this->personaModel->getPersonasActivas();

        $data = [
            'title'     => 'Editar Evaluación',
            'evaluacion' => $evaluacion,
            'personas'  => $personas,
        ];

        return view('evaluaciones/edit', $data);
    }

    /**
     * Actualiza una evaluación
     */
    public function update($id)
    {
        // Verificar permisos de acceso
        $accesoPermitido = $this->verificarAccesoEvaluacion($id);
        if ($accesoPermitido !== true) {
            return $accesoPermitido; // Retorna redirect si no tiene acceso
        }

        $rules = [
            'persona_id'       => 'required|is_natural_no_zero',
            'tipo_evaluacion' => 'required',
            'fecha_evaluacion' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'persona_id'       => $this->request->getPost('persona_id'),
            'tipo_evaluacion'  => $this->request->getPost('tipo_evaluacion'),
            'titulo'           => $this->request->getPost('titulo'),
            'fecha_evaluacion' => $this->request->getPost('fecha_evaluacion'),
            'resultado'        => $this->request->getPost('resultado'),
            'observaciones'    => $this->request->getPost('observaciones'),
            'calificacion'     => $this->request->getPost('calificacion'),
        ];

        $this->evaluacionModel->update($id, $data);

        return redirect()->to('/evaluaciones/show/' . $id)
            ->with('success', 'Evaluación actualizada exitosamente');
    }

    /**
     * Elimina una evaluación
     */
    public function delete($id)
    {
        // Verificar permisos de acceso
        $accesoPermitido = $this->verificarAccesoEvaluacion($id);
        if ($accesoPermitido !== true) {
            return $accesoPermitido; // Retorna redirect si no tiene acceso
        }

        $evaluacion = $this->evaluacionModel->find($id);

        if (!$evaluacion) {
            return redirect()->to('/evaluaciones')->with('error', 'Evaluación no encontrada');
        }

        $this->evaluacionModel->delete($id);

        return redirect()->to('/evaluaciones')
            ->with('success', 'Evaluación eliminada exitosamente');
    }

/**
     * Muestra la página de empleado del mes
     */
    public function empleadoDelMes()
    {
        $userId = session()->get('id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $usuario = $this->usuarioModel->find($userId);
        if (!$usuario || $usuario['rol'] !== 'ADMIN') {
            return redirect()->to('/home')->with('error', 'Solo administradores pueden seleccionar empleado del mes.');
        }

        $mes_seleccionado = $this->request->getGet('mes') ?? date('Y-m');

        $empleado_mes = $this->evaluacionModel->getEmpleadoDelMes($mes_seleccionado);
        $candidatos = $this->evaluacionModel->getCandidatosEmpleadoDelMes($mes_seleccionado);

        $data = [
            'title' => 'Empleado del Mes',
            'mes_seleccionado' => $mes_seleccionado,
            'empleado_mes' => $empleado_mes,
            'candidatos' => $candidatos,
        ];

        return view('evaluaciones/empleado_del_mes', $data);
    }

    /**
     * Selecciona empleado del mes
     */
    public function seleccionarEmpleadoDelMes($id)
    {
        // Solo ADMIN puede seleccionar empleado del mes
        $userId = session()->get('id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $usuario = $this->usuarioModel->find($userId);
        if (!$usuario || $usuario['rol'] !== 'ADMIN') {
            return redirect()->to('/home')->with('error', 'Solo administradores pueden seleccionar empleado del mes.');
        }

        // Desmarcar anteriores
        $this->evaluacionModel->where('es_empleado_mes', 'S')->update(null, ['es_empleado_mes' => 'N']);

        // Marcar nuevo
        $this->evaluacionModel->update($id, ['es_empleado_mes' => 'S']);

        return redirect()->to('/evaluaciones/empleado-del-mes')
            ->with('success', 'Empleado del mes seleccionado exitosamente');
    }
}
