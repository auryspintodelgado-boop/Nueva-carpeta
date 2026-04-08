<?php

namespace App\Controllers;

use App\Models\PersonaModel;
use App\Models\EvaluacionModel;
use App\Models\SeguimientoModel;
use App\Models\DepartamentoModel;

class ReporteController extends BaseController
{
    protected $personaModel;
    protected $evaluacionModel;
    protected $seguimientoModel;
    protected $departamentoModel;

    public function __construct()
    {
        $this->personaModel = new PersonaModel();
        $this->evaluacionModel = new EvaluacionModel();
        $this->seguimientoModel = new SeguimientoModel();
        $this->departamentoModel = new DepartamentoModel();
    }

    /**
     * Menú de reportes
     */
    public function index()
    {
        $data = [
            'title' => 'Reportes',
            'departamentos' => $this->departamentoModel->findAll(),
        ];

        return view('reportes/index', $data);
    }

    /**
     * Reporte de personas
     */
    public function personas($formato = 'html')
    {
        $departamentoId = $this->request->getGet('departamento');
        $rol = session()->get('rol');
        $departamentoSession = session()->get('departamento_id');

        // Filtrar por departamento según rol
        if ($rol === 'ADMIN' || $rol === 'EVALUADOR') {
            if ($departamentoId) {
                $personas = $this->personaModel->where('departamento_id', $departamentoId)
                    ->where('estado_registro', 'ACTIVO')
                    ->findAll();
                $departamento = $this->departamentoModel->find($departamentoId);
                $filtros['departamento'] = $departamento['nombre'] ?? 'Todos';
            } else {
                $personas = $this->personaModel->where('estado_registro', 'ACTIVO')->findAll();
                $filtros['departamento'] = 'Todos';
            }
        } else {
            $personas = $this->personaModel->where('departamento_id', $departamentoSession)
                ->where('estado_registro', 'ACTIVO')
                ->findAll();
            $departamento = $this->departamentoModel->find($departamentoSession);
            $filtros['departamento'] = $departamento['nombre'] ?? 'Mi Departamento';
        }

        $filtros['total'] = count($personas);

        if ($formato === 'excel') {
            return $this->exportExcelPersonas($personas);
        } elseif ($formato === 'pdf') {
            return $this->exportPdfPersonas($personas, $filtros);
        }

        $data = [
            'title' => 'Reporte de Personas',
            'personas' => $personas,
            'departamentos' => $this->departamentoModel->findAll(),
            'filtros' => $filtros,
        ];

        return view('reportes/personas', $data);
    }

    /**
     * Reporte de evaluaciones
     */
    public function evaluaciones($formato = 'html')
    {
        $departamentoId = $this->request->getGet('departamento');
        $mes = $this->request->getGet('mes');
        $año = $this->request->getGet('año');
        $rol = session()->get('rol');
        $departamentoSession = session()->get('departamento_id');

        // Obtener evaluaciones
        if ($rol === 'ADMIN' || $rol === 'EVALUADOR') {
            $evaluaciones = $this->evaluacionModel->orderBy('fecha_evaluacion', 'DESC')->findAll();
        } else {
            // Obtener personas del departamento
            $personas = $this->personaModel->where('departamento_id', $departamentoSession)->findAll();
            $personaIds = array_column($personas, 'id');
            $evaluaciones = $this->evaluacionModel->whereIn('persona_id', $personaIds)
                ->orderBy('fecha_evaluacion', 'DESC')
                ->findAll();
        }

        // Filtrar por departamento y fecha
        $filtros = [];
        if ($departamentoId || $mes || $año) {
            $personaIds = [];
            if ($departamentoId) {
                $personasDept = $this->personaModel->where('departamento_id', $departamentoId)->findAll();
                $personaIds = array_column($personasDept, 'id');
                $departamento = $this->departamentoModel->find($departamentoId);
                $filtros['departamento'] = $departamento['nombre'] ?? 'Todos';
            } else {
                $filtros['departamento'] = 'Todos';
            }
            
            if ($mes) {
                $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                $filtros['mes'] = $meses[(int)$mes];
            } else {
                $filtros['mes'] = 'Todos';
            }
            
            if ($año) {
                $filtros['año'] = $año;
            } else {
                $filtros['año'] = 'Todos';
            }
            
            $evaluaciones = array_filter($evaluaciones, function($eval) use ($personaIds, $mes, $año, $departamentoId) {
                if ($departamentoId && !empty($personaIds) && !in_array($eval['persona_id'], $personaIds)) {
                    return false;
                }
                if ($mes && date('m', strtotime($eval['fecha_evaluacion'])) != $mes) {
                    return false;
                }
                if ($año && date('Y', strtotime($eval['fecha_evaluacion'])) != $año) {
                    return false;
                }
                return true;
            });
        } else {
            $filtros['departamento'] = 'Todos';
            $filtros['mes'] = 'Todos';
            $filtros['año'] = 'Todos';
        }

        $filtros['total'] = count($evaluaciones);

        if ($formato === 'excel') {
            return $this->exportExcelEvaluaciones($evaluaciones);
        } elseif ($formato === 'pdf') {
            return $this->exportPdfEvaluaciones($evaluaciones, $filtros);
        }

        $data = [
            'title' => 'Reporte de Evaluaciones',
            'evaluaciones' => $evaluaciones,
            'departamentos' => $this->departamentoModel->findAll(),
            'filtros' => $filtros,
        ];

        return view('reportes/evaluaciones', $data);
    }

    /**
     * Reporte de seguimientos
     */
    public function seguimientos($formato = 'html')
    {
        $departamentoId = $this->request->getGet('departamento');
        $estado = $this->request->getGet('estado');
        $rol = session()->get('rol');
        $departamentoSession = session()->get('departamento_id');

        if ($rol === 'ADMIN' || $rol === 'EVALUADOR') {
            $seguimientos = $this->seguimientoModel->orderBy('fecha_seguimiento', 'DESC')->findAll();
        } else {
            $personas = $this->personaModel->where('departamento_id', $departamentoSession)->findAll();
            $personaIds = array_column($personas, 'id');
            $seguimientos = $this->seguimientoModel->whereIn('persona_id', $personaIds)
                ->orderBy('fecha_seguimiento', 'DESC')
                ->findAll();
        }

        // Filtrar
        $filtros = [];
        if ($departamentoId || $estado) {
            $personasDept = $this->personaModel->where('departamento_id', $departamentoId)->findAll();
            $personaIdsDept = array_column($personasDept, 'id');
            
            if ($departamentoId) {
                $departamento = $this->departamentoModel->find($departamentoId);
                $filtros['departamento'] = $departamento['nombre'] ?? 'Todos';
            } else {
                $filtros['departamento'] = 'Todos';
            }
            
            $estados = ['' => 'Todos', 'PENDIENTE' => 'Pendiente', 'EN_PROCESO' => 'En Proceso', 'COMPLETADO' => 'Completado'];
            $filtros['estado'] = $estados[$estado] ?? 'Todos';
            
            $seguimientos = array_filter($seguimientos, function($seg) use ($personaIdsDept, $estado, $departamentoId) {
                if ($departamentoId && !in_array($seg['persona_id'], $personaIdsDept)) {
                    return false;
                }
                if ($estado && $seg['estado'] != $estado) {
                    return false;
                }
                return true;
            });
        } else {
            $filtros['departamento'] = 'Todos';
            $filtros['estado'] = 'Todos';
        }

        $filtros['total'] = count($seguimientos);

        if ($formato === 'excel') {
            return $this->exportExcelSeguimientos($seguimientos);
        } elseif ($formato === 'pdf') {
            return $this->exportPdfSeguimientos($seguimientos, $filtros);
        }

        $data = [
            'title' => 'Reporte de Seguimientos',
            'seguimientos' => $seguimientos,
            'departamentos' => $this->departamentoModel->findAll(),
            'filtros' => $filtros,
        ];

        return view('reportes/seguimientos', $data);
    }

    /**
     * Exportar a Excel (CSV)
     */
    private function exportExcelPersonas($personas)
    {
        $filename = 'reporte_personas_' . date('YmdHis') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Encabezado de la empresa
        fputcsv($output, ['REPORTE DE CARACTERIZACIÓN - SISTEMA AURYS'], ';');
        fputcsv($output, ['Fecha de generación: ' . date('d/m/Y H:i:s')], ';');
        fputcsv($output, ['Total de personas: ' . count($personas)], ';');
        fputcsv($output, [], ';');
        
        // Encabezados - Todos los campos de caracterización
        fputcsv($output, [
            'N°', 'Número', 'Nacionalidad', 'Cédula', 
            'Primer Nombre', 'Segundo Nombre', 
            'Primer Apellido', 'Segundo Apellido',
            'Sexo', 'Fecha Nacimiento', 'Edad',
            'Teléfono 1', 'Teléfono 2', 'Correo Electrónico',
            'Carrera', 'Año/Semestre', 'Posee Beca', 'Sede',
            'Estado', 'Siglas Universidad', 'Tipo IEU',
            'Nivel Académico', 'Urbanismo', 'Municipio', 'Parroquia',
            'Tiene Hijos', 'Cantidad Hijos', 
            'Posee Discapacidad', 'Descripción Discapacidad',
            'Presenta Enfermedad', 'Condición Médica', 'Medicamentos',
            'Trabaja', 'Tipo Empleo', 'Medio Transporte',
            'Inscrito CNE', 'Centro Electoral', 'Comuna',
            'Estado Civil', 'Talla Camisa', 'Talla Zapatos',
            'Talla Pantalón', 'Altura', 'Peso', 'Tipo Sangre',
            'Carga Familiar', 'Departamento', 'Fecha Registro', 'Estado'
        ], ';');
        
        $num = 1;
        foreach ($personas as $p) {
            $departamento = $this->departamentoModel->find($p['departamento_id']);
            fputcsv($output, [
                $num++,
                $p['numero'] ?? '',
                $p['nacionalidad'] ?? '',
                $p['cedula'] ?? '',
                $p['primer_nombre'] ?? '',
                $p['segundo_nombre'] ?? '',
                $p['primer_apellido'] ?? '',
                $p['segundo_apellido'] ?? '',
                $p['sexo'] ?? '',
                $p['fecha_nacimiento'] ?? '',
                $p['edad'] ?? '',
                $p['telefono1'] ?? '',
                $p['telefono2'] ?? '',
                $p['correo_electronico'] ?? '',
                $p['carrera'] ?? '',
                $p['ano_semestre'] ?? '',
                $p['posee_beca'] ?? '',
                $p['sede'] ?? '',
                $p['estado'] ?? '',
                $p['siglas_universidad'] ?? '',
                $p['tipo_ieu'] ?? '',
                $p['nivel_academico'] ?? '',
                $p['urbanismo'] ?? '',
                $p['municipio'] ?? '',
                $p['parroquia'] ?? '',
                $p['tiene_hijos'] ?? '',
                $p['cantidad_hijos'] ?? '',
                $p['posee_discapacidad'] ?? '',
                $p['descripcion_discapacidad'] ?? '',
                $p['presenta_enfermedad'] ?? '',
                $p['condicion_medica'] ?? '',
                $p['medicamentos'] ?? '',
                $p['trabaja'] ?? '',
                $p['tipo_empleo'] ?? '',
                $p['medio_transporte'] ?? '',
                $p['inscrito_cne'] ?? '',
                $p['centro_electoral'] ?? '',
                $p['comuna'] ?? '',
                $p['estado_civil'] ?? '',
                $p['talla_camisa'] ?? '',
                $p['talla_zapatos'] ?? '',
                $p['talla_pantalon'] ?? '',
                $p['altura'] ?? '',
                $p['peso'] ?? '',
                $p['tipo_sangre'] ?? '',
                $p['carga_familiar'] ?? '',
                $departamento['nombre'] ?? '',
                $p['fecha_registro'] ?? '',
                $p['estado_registro'] ?? ''
            ], ';');
        }
        
        fclose($output);
        exit;
    }

    private function exportExcelEvaluaciones($evaluaciones)
    {
        $filename = 'reporte_evaluaciones_' . date('YmdHis') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, [
            'Fecha', 'Persona', 'Tipo', 'Asistencia', 'Puntualidad', 
            'Trabajo Equipo', 'Iniciativa', 'Total', 'Resultado'
        ], ';');
        
        foreach ($evaluaciones as $e) {
            $persona = $this->personaModel->find($e['persona_id']);
            $nombre = $persona ? $persona['primer_nombre'] . ' ' . $persona['primer_apellido'] : 'N/A';
            
            fputcsv($output, [
                $e['fecha_evaluacion'],
                $nombre,
                $e['tipo_evaluacion'],
                $e['asistencia'],
                $e['puntualidad'],
                $e['trabajo_equipo'],
                $e['iniciativa'],
                $e['puntuacion_total'],
                $e['resultado']
            ], ';');
        }
        
        fclose($output);
        exit;
    }

    private function exportExcelSeguimientos($seguimientos)
    {
        $filename = 'reporte_seguimientos_' . date('YmdHis') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, [
            'Fecha', 'Persona', 'Tipo', 'Estado', 'Prioridad', 'Descripción'
        ], ';');
        
        foreach ($seguimientos as $s) {
            $persona = $this->personaModel->find($s['persona_id']);
            $nombre = $persona ? $persona['primer_nombre'] . ' ' . $persona['primer_apellido'] : 'N/A';
            
            fputcsv($output, [
                $s['fecha_seguimiento'],
                $nombre,
                $s['tipo_seguimiento'],
                $s['estado'],
                $s['prioridad'],
                substr($s['descripcion'] ?? '', 0, 100)
            ], ';');
        }
        
        fclose($output);
        exit;
    }

    /**
     * Exportar a PDF (HTML para imprimir)
     */
    private function exportPdfPersonas($personas, $filtros = [])
    {
        $data = [
            'title' => 'Reporte de Personas',
            'personas' => $personas,
            'fecha' => date('d/m/Y H:i'),
            'filtros' => $filtros,
        ];

        // Cargar vista como string
        $html = view('reportes/pdf/personas', $data);
        
        return $this->response->setContentType('text/html')
            ->setBody($html);
    }

    private function exportPdfEvaluaciones($evaluaciones, $filtros = [])
    {
        $data = [
            'title' => 'Reporte de Evaluaciones',
            'evaluaciones' => $evaluaciones,
            'fecha' => date('d/m/Y H:i'),
            'filtros' => $filtros,
        ];

        $html = view('reportes/pdf/evaluaciones', $data);
        
        return $this->response->setContentType('text/html')
            ->setBody($html);
    }

    private function exportPdfSeguimientos($seguimientos, $filtros = [])
    {
        $data = [
            'title' => 'Reporte de Seguimientos',
            'seguimientos' => $seguimientos,
            'fecha' => date('d/m/Y H:i'),
            'filtros' => $filtros,
        ];

        $html = view('reportes/pdf/seguimientos', $data);
        
        return $this->response->setContentType('text/html')
            ->setBody($html);
    }

    /**
     * Reporte de estadísticas (gráficos)
     */
    public function estadisticas()
    {
        $departamentoId = $this->request->getGet('departamento');
        $rol = session()->get('rol');
        $departamentoSession = session()->get('departamento_id');

        // Obtener personas según rol
        if ($rol === 'ADMIN' || $rol === 'EVALUADOR') {
            if ($departamentoId) {
                $personas = $this->personaModel->where('departamento_id', $departamentoId)
                    ->where('estado_registro', 'ACTIVO')
                    ->findAll();
                $departamento = $this->departamentoModel->find($departamentoId);
                $filtros['departamento'] = $departamento['nombre'] ?? 'Todos';
            } else {
                $personas = $this->personaModel->where('estado_registro', 'ACTIVO')->findAll();
                $filtros['departamento'] = 'Todos los departamentos';
            }
        } else {
            $personas = $this->personaModel->where('departamento_id', $departamentoSession)
                ->where('estado_registro', 'ACTIVO')
                ->findAll();
            $departamento = $this->departamentoModel->find($departamentoSession);
            $filtros['departamento'] = $departamento['nombre'] ?? 'Mi Departamento';
        }

        // Estadísticas de tipo de sangre
        $tiposSangre = [];
        foreach ($personas as $p) {
            $tipo = $p['tipo_sangre'] ?? 'No definido';
            if (!isset($tiposSangre[$tipo])) {
                $tiposSangre[$tipo] = 0;
            }
            $tiposSangre[$tipo]++;
        }

        // Ordenar por cantidad
        arsort($tiposSangre);

        // Colores para el gráfico
        $colores = [
            'A+' => '#e74c3c', 'A-' => '#c0392b',
            'B+' => '#3498db', 'B-' => '#2980b9',
            'AB+' => '#9b59b6', 'AB-' => '#8e44ad',
            'O+' => '#2ecc71', 'O-' => '#27ae60',
            'No definido' => '#95a5a6'
        ];

        $data = [
            'title' => 'Estadísticas del Sistema',
            'personas' => $personas,
            'departamentos' => $this->departamentoModel->findAll(),
            'tiposSangre' => $tiposSangre,
            'colores' => $colores,
            'filtros' => $filtros,
            'totalPersonas' => count($personas),
            'selectedDepartamento' => $departamentoId,
        ];

        return view('reportes/estadisticas', $data);
    }
}
