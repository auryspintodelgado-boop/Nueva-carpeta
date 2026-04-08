<?php

namespace App\Controllers;

use App\Models\EvaluacionModel;
use App\Models\PersonaModel;
use App\Models\UsuarioModel;
use App\Models\DepartamentoModel;

class EvaluacionDirectorController extends BaseController
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
     * Verifica que el usuario sea director o admin y tenga acceso al departamento
     */
    private function verificarAcceso()
    {
        $userId = session()->get('id');
        
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión');
        }

        $usuario = $this->usuarioModel->find($userId);
        
        if (!$usuario) {
            return redirect()->to('/login')->with('error', 'Usuario no encontrado');
        }
        
        // ADMIN tiene acceso a todos los departamentos
        if (isset($usuario['rol']) && $usuario['rol'] === 'ADMIN') {
            return null; // Acceso permitido
        }
        
        // DIRECTOR necesita tener departamento asignado
        if (!isset($usuario['rol']) || $usuario['rol'] !== 'DIRECTOR') {
            return redirect()->to('/home')->with('error', 'Acceso denegado. Solo administradores y directores pueden evaluar.');
        }

        if (!$usuario['departamento_id'] ?? null ?? null) {
            return redirect()->to('/home')->with('error', 'No tiene un departamento asignado.');
        }

        return null; // Acceso permitido
    }

    /**
     * Dashboard del director - muestra su departamento y personal
     */
    public function index()
    {
        $redirect = $this->verificarAcceso();
        if ($redirect) return $redirect;

        $userId = session()->get('id');
        $usuario = $this->usuarioModel->find($userId);
        
        // ADMIN puede seleccionar departamento
        if (isset($usuario['rol']) && $usuario['rol'] === 'ADMIN') {
            return $this->adminIndex();
        }
        
        $departamentoId = $usuario['departamento_id'] ?? null;
        
        // Guardar departamento en sesión para ADMIN
        if (!$departamentoId) {
            $departamentoId = session()->get('departamento_seleccionado');
        }
        
        if (!$departamentoId) {
            return redirect()->to('/home')->with('error', 'No tiene un departamento asignado.');
        }
        
        $departamento = $this->departamentoModel->find($departamentoId);
        $personal = $this->personaModel->getPersonasPorDepartamento($departamentoId);
        $mesActual = date('Y-m');

        // Obtener evaluaciones del mes actual
        $evaluacionesMes = $this->evaluacionModel->getEvaluacionesDepartamento($departamentoId, $mesActual);
        
        // Obtener estadísticas
        $estadisticas = $this->evaluacionModel->getEstadisticasDepartamento($departamentoId, $mesActual);

        $data = [
            'title' => 'Evaluación de Personal - ' . ($departamento ? $departamento['nombre'] : 'Departamento'),
            'departamento' => $departamento,
            'personal' => $personal,
            'evaluacionesMes' => $evaluacionesMes,
            'estadisticas' => $estadisticas,
            'mesActual' => $mesActual,
            'totalPersonal' => count($personal),
            'evaluados' => count($evaluacionesMes),
        ];

        return view('evaluaciones/director/index', $data);
    }

    /**
     * Panel de ADMIN para seleccionar departamento
     */
    private function adminIndex()
    {
        $departamentoId = $this->request->getGet('departamento') ?? session()->get('departamento_seleccionado');
        $departamentos = $this->departamentoModel->getDepartamentosActivos();
        $mesActual = date('Y-m');
        
        // Guardar departamento seleccionado en sesión
        if ($departamentoId) {
            session()->set('departamento_seleccionado', $departamentoId);
        }
        
        if ($departamentoId) {
            $departamento = $this->departamentoModel->find($departamentoId);
            $personal = $this->personaModel->getPersonasPorDepartamento($departamentoId);
            $evaluacionesMes = $this->evaluacionModel->getEvaluacionesDepartamento($departamentoId, $mesActual);
            $estadisticas = $this->evaluacionModel->getEstadisticasDepartamento($departamentoId, $mesActual);
            
            $data = [
                'title' => 'Evaluación de Personal - ' . ($departamento ? $departamento['nombre'] : 'Departamento'),
                'departamento' => $departamento,
                'personal' => $personal,
                'evaluacionesMes' => $evaluacionesMes,
                'estadisticas' => $estadisticas,
                'mesActual' => $mesActual,
                'totalPersonal' => count($personal),
                'evaluados' => count($evaluacionesMes),
                'departamentos' => $departamentos,
            ];
            
            return view('evaluaciones/director/index', $data);
        }
        
        $data = [
            'title' => 'Evaluación de Personal - Seleccionar Departamento',
            'departamentos' => $departamentos,
            'mesActual' => $mesActual,
        ];

        return view('evaluaciones/director/select_departamento', $data);
    }

    /**
     * Muestra el formulario para crear una evaluación mensual
     */
    public function create()
    {
        $redirect = $this->verificarAcceso();
        if ($redirect) return $redirect;

        $userId = session()->get('id');
        $usuario = $this->usuarioModel->find($userId);
        
        // ADMIN debe seleccionar departamento primero
        if (isset($usuario['rol']) && $usuario['rol'] === 'ADMIN') {
            $departamentoId = $this->request->getGet('departamento') ?? session()->get('departamento_seleccionado');
            
            // Si no hay departamento seleccionado, ir a la vista de selección
            if (!$departamentoId) {
                // Primero ir al índice para seleccionar departamento
                return redirect()->to('/evaluaciones/director');
            }
            
            // Guardar en sesión
            session()->set('departamento_seleccionado', $departamentoId);
            $departamento = $this->departamentoModel->find($departamentoId);
            // ADMIN puede ver todas las personas activas
            $personal = $this->personaModel->getPersonasActivasTodas();
            $departamentos = $this->departamentoModel->getDepartamentosActivos();
        } else {
            $departamentoId = $usuario['departamento_id'] ?? null;
            
            // Si DIRECTOR no tiene departamento, redirigir a home
            if (!$departamentoId) {
                return redirect()->to('/home')->with('error', 'No tiene un departamento asignado.');
            }
            
            $departamento = $this->departamentoModel->find($departamentoId);
            
            // Si es ADMIN, mostrar todas las personas activas
            $esAdmin = isset($usuario['rol']) && $usuario['rol'] === 'ADMIN';
            if ($esAdmin) {
                $personal = $this->personaModel->getPersonasActivasTodas();
            } else {
                $personal = $this->personaModel->getPersonasPorDepartamento($departamentoId);
            }
            
            $departamentos = null;
        }
        
        // Obtener mes a evaluar (por defecto el actual)
        $mesEvaluacion = $this->request->getGet('mes') ?? date('Y-m');

        $data = [
            'title' => 'Nueva Evaluación Mensual',
            'departamento' => $departamento,
            'departamento_id' => $departamentoId,
            'personal' => $personal,
            'mesEvaluacion' => $mesEvaluacion,
            'evaluacion' => null,
            'departamentos' => $departamentos,
        ];

        return view('evaluaciones/director/create', $data);
    }

    /**
     * Guarda una nueva evaluación mensual
     */
    public function store()
    {
        $redirect = $this->verificarAcceso();
        if ($redirect) return $redirect;

        $userId = session()->get('id');
        $usuario = $this->usuarioModel->find($userId);
        
        // Obtener departamento_id - para DIRECTOR viene del usuario, para ADMIN de la sesión o POST
        $departamentoId = $usuario['departamento_id'] ?? null ?? null;
        
        // Si es ADMIN, obtener de la sesión o del POST
        if (!$departamentoId) {
            $departamentoId = session()->get('departamento_seleccionado');
        }
        if (!$departamentoId) {
            $departamentoId = $this->request->getPost('departamento_id');
        }
        
        if (!$departamentoId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No se ha seleccionado un departamento.');
        }

        // Validar que la persona pertenece al departamento del director
        $personaId = $this->request->getPost('persona_id');
        $persona = $this->personaModel->find($personaId);
        
        // Verificar que la persona existe
        if (!$persona) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La persona seleccionada no existe.');
        }
        
        // Usar ?? 0 para evitar error si no tiene departamento
        $personaDepartamentoId = $persona['departamento_id'] ?? 0;
        if ($personaDepartamentoId != $departamentoId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La persona seleccionada no pertenece al departamento seleccionado.');
        }

        // Validar campos requeridos
        $rules = [
            'persona_id' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $mesEvaluacion = $this->request->getPost('mes_evaluado') ?? date('Y-m');

        // Verificar si ya existe evaluación para este mes
        if ($this->evaluacionModel->existeEvaluacionMensual($personaId, $mesEvaluacion)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe una evaluación para esta persona en el mes seleccionado.');
        }

        // Recopilar datos de los 20 sub-campos
        $data = [
            // Orientación de Resultados
            'ori_termino_oportuno' => intval($this->request->getPost('ori_termino_oportuno')),
            'ori_cumple_tareas' => intval($this->request->getPost('ori_cumple_tareas')),
            'ori_volumen_adecuado' => intval($this->request->getPost('ori_volumen_adecuado')),
            // Calidad y Organización
            'cal_no_errores' => intval($this->request->getPost('cal_no_errores')),
            'cal_recursos_racionales' => intval($this->request->getPost('cal_recursos_racionales')),
            'cal_supervision' => intval($this->request->getPost('cal_supervision')),
            'cal_profesional' => intval($this->request->getPost('cal_profesional')),
            'cal_respetuoso' => intval($this->request->getPost('cal_respetuoso')),
            'cal_planifica' => intval($this->request->getPost('cal_planifica')),
            'cal_indicadores' => intval($this->request->getPost('cal_indicadores')),
            'cal_metas' => intval($this->request->getPost('cal_metas')),
            // Relaciones Interpersonales
            'rel_cortes' => intval($this->request->getPost('rel_cortes')),
            'rel_orientacion' => intval($this->request->getPost('rel_orientacion')),
            'rel_conflictos' => intval($this->request->getPost('rel_conflictos')),
            'rel_integracion' => intval($this->request->getPost('rel_integracion')),
            'rel_objetivos' => intval($this->request->getPost('rel_objetivos')),
            // Iniciativa
            'ini_ideas' => intval($this->request->getPost('ini_ideas')),
            'ini_cambio' => intval($this->request->getPost('ini_cambio')),
            'ini_anticipacion' => intval($this->request->getPost('ini_anticipacion')),
            'ini_resolucion' => intval($this->request->getPost('ini_resolucion')),
        ];
        
        // Calcular puntuación detallada
        $puntuacionDetallada = $this->evaluacionModel->calcularPuntuacionDetallada($data);
        
        // Preparar datos para guardar
        $evaluacionData = [
            'persona_id' => $personaId,
            'departamento_id' => $departamentoId,
            'evaluador_id' => $userId,
            'tipo_evaluacion' => 'MENSUAL',
            'mes_evaluado' => $mesEvaluacion,
            'fecha_evaluacion' => date('Y-m-d'),
            'estado_evaluacion' => 'COMPLETADA',
            // Puntuaciones calculadas
            'orientacion_resultados' => $puntuacionDetallada['orientacion'],
            'calidad_organizacion' => $puntuacionDetallada['calidad'],
            'relaciones_interpersonales' => $puntuacionDetallada['relaciones'],
            'iniciativa' => $puntuacionDetallada['iniciativa'],
            'puntuacion' => $puntuacionDetallada['total'],
            // Campos detallados - Orientación de Resultados
            'ori_termino_oportuno' => $data['ori_termino_oportuno'],
            'ori_cumple_tareas' => $data['ori_cumple_tareas'],
            'ori_volumen_adecuado' => $data['ori_volumen_adecuado'],
            // Campos detallados - Calidad y Organización
            'cal_no_errores' => $data['cal_no_errores'],
            'cal_recursos_racionales' => $data['cal_recursos_racionales'],
            'cal_supervision' => $data['cal_supervision'],
            'cal_profesional' => $data['cal_profesional'],
            'cal_respetuoso' => $data['cal_respetuoso'],
            'cal_planifica' => $data['cal_planifica'],
            'cal_indicadores' => $data['cal_indicadores'],
            'cal_metas' => $data['cal_metas'],
            // Campos detallados - Relaciones Interpersonales
            'rel_cortes' => $data['rel_cortes'],
            'rel_orientacion' => $data['rel_orientacion'],
            'rel_conflictos' => $data['rel_conflictos'],
            'rel_integracion' => $data['rel_integracion'],
            'rel_objetivos' => $data['rel_objetivos'],
            // Campos detallados - Iniciativa
            'ini_ideas' => $data['ini_ideas'],
            'ini_cambio' => $data['ini_cambio'],
            'ini_anticipacion' => $data['ini_anticipacion'],
            'ini_resolucion' => $data['ini_resolucion'],
            // Observaciones de cada sección
            'obs_orientacion' => $this->request->getPost('obs_orientacion'),
            'obs_calidad' => $this->request->getPost('obs_calidad'),
            'obs_relaciones' => $this->request->getPost('obs_relaciones'),
            'obs_iniciativa' => $this->request->getPost('obs_iniciativa'),
            // Observación general
            'observaciones' => $this->request->getPost('observaciones'),
            // Campos adicionales
            'fecha_ingreso' => $this->request->getPost('fecha_ingreso'),
            'nombre_evaluador' => $usuario['nombre'] ?? '',
            'comentarios_adicionales' => $this->request->getPost('comentarios_adicionales'),
            'resultado' => $this->evaluacionModel->getTextoPuntuacion($puntuacionDetallada['total']),
        ];

        $this->evaluacionModel->insert($evaluacionData);

        return redirect()->to('/evaluaciones/director')
            ->with('success', 'Evaluación guardada exitosamente. Puntuación: ' . $puntuacionDetallada['total'] . '/20');
    }

    /**
     * Muestra el historial de evaluaciones
     */
    public function historial()
    {
        $redirect = $this->verificarAcceso();
        if ($redirect) return $redirect;

        $userId = session()->get('id');
        $usuario = $this->usuarioModel->find($userId);
        
        // Obtener departamento_id - para DIRECTOR viene del usuario, para ADMIN de la sesión
        $departamentoId = $usuario['departamento_id'] ?? null ?? null;
        
        if (!$departamentoId) {
            $departamentoId = session()->get('departamento_seleccionado');
        }
        
        if (!$departamentoId) {
            return redirect()->to('/home')->with('error', 'No tiene un departamento asignado.');
        }
        
        $departamento = $this->departamentoModel->find($departamentoId);
        
        // Obtener mes a consultar
        $mes = $this->request->getGet('mes') ?? date('Y-m');

        $evaluaciones = $this->evaluacionModel->getEvaluacionesDepartamento($departamentoId, $mes);
        $estadisticas = $this->evaluacionModel->getEstadisticasDepartamento($departamentoId, $mes);

        $data = [
            'title' => 'Historial de Evaluaciones',
            'departamento' => $departamento,
            'evaluaciones' => $evaluaciones,
            'estadisticas' => $estadisticas,
            'mes' => $mes,
        ];

        return view('evaluaciones/director/historial', $data);
    }

    /**
     * Ver detalle de una evaluación
     */
    public function show($id)
    {
        $redirect = $this->verificarAcceso();
        if ($redirect) return $redirect;

        $userId = session()->get('id');
        $usuario = $this->usuarioModel->find($userId);
        
        // Obtener departamento_id - para DIRECTOR viene del usuario, para ADMIN de la sesión
        $departamentoId = $usuario['departamento_id'] ?? null ?? null;
        
        if (!$departamentoId) {
            $departamentoId = session()->get('departamento_seleccionado');
        }

        $evaluacion = $this->evaluacionModel->find($id);
        
        if (!$evaluacion) {
            return redirect()->to('/evaluaciones/director')->with('error', 'Evaluación no encontrada');
        }

        // Verificar que la evaluación pertenece al departamento del director/admin
        // ADMIN puede ver todas las evaluaciones
        $esAdmin = isset($usuario['rol']) && $usuario['rol'] === 'ADMIN';
        $evaluacionDepartamentoId = $evaluacion['departamento_id'] ?? 0;
        if (!$esAdmin && $evaluacionDepartamentoId != $departamentoId) {
            return redirect()->to('/evaluaciones/director')->with('error', 'No tiene acceso a esta evaluación');
        }

        $persona = $this->personaModel->find($evaluacion['persona_id']);
        $evaluador = $this->usuarioModel->find($evaluacion['evaluador_id']);

        $data = [
            'title' => 'Detalle de Evaluación',
            'evaluacion' => $evaluacion,
            'persona' => $persona,
            'evaluador' => $evaluador,
        ];

        return view('evaluaciones/director/show', $data);
    }

    /**
     * Genera texto de resultado según puntuación
     */
    private function generarResultado($puntuacion)
    {
        if ($puntuacion >= 18) return 'Excelente';
        if ($puntuacion >= 15) return 'Muy Bueno';
        if ($puntuacion >= 12) return 'Bueno';
        if ($puntuacion >= 10) return 'Regular';
        return 'Necesita Mejorar';
    }
}
