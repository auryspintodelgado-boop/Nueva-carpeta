<?php

namespace App\Controllers;

use App\Models\PersonaModel;
use App\Models\EvaluacionModel;
use App\Models\SeguimientoModel;
use App\Models\DepartamentoModel;

class HomeController extends BaseController
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
     * Página principal - redirige a login o dashboard
     */
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return redirect()->to('/dashboard');
    }

    /**
     * Dashboard principal
     */
    public function dashboard()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Estadísticas
        $statsPersonas = $this->personaModel->getEstadisticas();
        
        $totalEvaluaciones = $this->evaluacionModel->countAll();
        $totalSeguimientos = $this->seguimientoModel->countAll();
        
        $seguimientosPendientes = $this->seguimientoModel->where('estado', 'PENDIENTE')->countAllResults();
        $seguimientosProximos = count($this->seguimientoModel->getSeguimientosProximos());

        // Datos para gráficos
        $departamentos = $this->departamentoModel->findAll();
        $labelsDept = [];
        $dataDept = [];
        foreach ($departamentos as $dept) {
            $count = $this->personaModel->where('departamento_id', $dept['id'])
                ->where('estado_registro', 'ACTIVO')
                ->countAllResults();
            $labelsDept[] = $dept['nombre'];
            $dataDept[] = $count;
        }

        // Evaluaciones por mes (últimos 6 meses)
        $labelsMeses = [];
        $dataEvaluaciones = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = date('m', strtotime("-{$i} months"));
            $año = date('Y', strtotime("-{$i} months"));
            $count = $this->evaluacionModel
                ->where('MONTH(fecha_evaluacion)', $mes)
                ->where('YEAR(fecha_evaluacion)', $año)
                ->countAllResults();
            $labelsMeses[] = date('M Y', strtotime("-{$i} months"));
            $dataEvaluaciones[] = $count;
        }

        // Distribución por género
        $hombres = $this->personaModel->where('sexo', 'M')->where('estado_registro', 'ACTIVO')->countAllResults();
        $mujeres = $this->personaModel->where('sexo', 'F')->where('estado_registro', 'ACTIVO')->countAllResults();

        // Personas con/sin discapacidad
        $conDiscapacidad = $this->personaModel->where('posee_discapacidad', 'S')->where('estado_registro', 'ACTIVO')->countAllResults();
        $sinDiscapacidad = $this->personaModel->where('posee_discapacidad', 'N')->where('estado_registro', 'ACTIVO')->countAllResults();

        // Estado de seguimientos
        $segPendientes = $this->seguimientoModel->where('estado', 'PENDIENTE')->countAllResults();
        $segEnProceso = $this->seguimientoModel->where('estado', 'EN_PROCESO')->countAllResults();
        $segCompletados = $this->seguimientoModel->where('estado', 'COMPLETADO')->countAllResults();

        // Nuevas estadísticas de personas
        $estadoCivil = $this->personaModel->getEstadisticasEstadoCivil();
        $edad = $this->personaModel->getEstadisticasEdad();
        $nacionalidad = $this->personaModel->getEstadisticasNacionalidad();
        $estadoGeo = $this->personaModel->getEstadisticasEstado(); // Estado geográfico
        $municipio = $this->personaModel->getEstadisticasMunicipio();
        $comuna = $this->personaModel->getEstadisticasComuna();
        $parroquia = $this->personaModel->getEstadisticasParroquia();
        $nivelAcademico = $this->personaModel->getEstadisticasNivelAcademico();
        $beca = $this->personaModel->getEstadisticasBeca();
        $tipoUniv = $this->personaModel->getEstadisticasTipoUniversidad();
        $carrera = $this->personaModel->getEstadisticasCarrera();
        $universidad = $this->personaModel->getEstadisticasUniversidad();

        $data = [
            'title'        => 'Dashboard',
            'stats'        => [
                'personas_total'      => $statsPersonas['total'],
                'personas_activas'    => $statsPersonas['activos'],
                'evaluaciones'       => $totalEvaluaciones,
                'seguimientos'       => $totalSeguimientos,
                'pendientes'         => $seguimientosPendientes,
                'proximos'           => $seguimientosProximos,
            ],
            'chart_data' => [
                'labels_dept' => $labelsDept,
                'data_dept' => $dataDept,
                'labels_meses' => $labelsMeses,
                'data_evaluaciones' => $dataEvaluaciones,
                'hombres' => $hombres,
                'mujeres' => $mujeres,
                'con_discapacidad' => $conDiscapacidad,
                'sin_discapacidad' => $sinDiscapacidad,
                'seg_pendientes' => $segPendientes,
                'seg_en_proceso' => $segEnProceso,
                'seg_completados' => $segCompletados,
                // Nuevos datos para gráficos
                'estado_civil' => $estadoCivil,
                'edad' => $edad,
                'nacionalidad' => $nacionalidad,
                'estado_geo' => $estadoGeo,
                'municipio' => $municipio,
                'comuna' => $comuna,
                'parroquia' => $parroquia,
                'nivel_academico' => $nivelAcademico,
                'beca' => $beca,
                'tipo_universidad' => $tipoUniv,
                'carrera' => $carrera,
                'universidad' => $universidad,
            ],
            'seguimientos_recientes' => $this->seguimientoModel
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->findAll(),
        ];

        return view('home/dashboard', $data);
    }

    /**
     * Página de inicio sin login
     */
    public function welcome()
    {
        $data = [
            'title' => 'Sistema de Caracterización',
        ];

        return view('home/welcome', $data);
    }

    /**
     * Obtiene lista de personas filtrada por un criterio (para gráficos)
     */
    public function personasByFilter()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'No autorizado']);
        }

        $filterType = $this->request->getGet('filter_type');
        $filterValue = $this->request->getGet('filter_value');
        $departamentoId = $this->request->getGet('departamento_id');

        if (!$filterType || $filterValue === null) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Parámetros incompletos']);
        }

        $personas = $this->personaModel->getPersonasByFilter($filterType, $filterValue, $departamentoId);

        // Mapeo de nombres de filtros para mostrar
        $filterNames = [
            'estado_civil' => 'Estado Civil',
            'edad' => 'Edad',
            'nacionalidad' => 'Nacionalidad',
            'estado' => 'Estado',
            'municipio' => 'Municipio',
            'comuna' => 'Comuna',
            'parroquia' => 'Parroquia',
            'nivel_academico' => 'Nivel Académico',
            'beca' => 'Beca',
            'tipo_universidad' => 'Tipo de Universidad',
            'carrera' => 'Carrera',
            'universidad' => 'Universidad',
        ];

        $data = [
            'personas' => $personas,
            'filter_type' => $filterType,
            'filter_value' => $filterValue,
            'filter_name' => $filterNames[$filterType] ?? $filterType,
        ];

        return view('home/_personas_list', $data);
    }
}
