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

        // Personas con/discapacidad
        $conDiscapacidad = $this->personaModel->where('posee_discapacidad', 'S')->where('estado_registro', 'ACTIVO')->countAllResults();
        $sinDiscapacidad = $this->personaModel->where('posee_discapacidad', 'N')->where('estado_registro', 'ACTIVO')->countAllResults();

        // Estado de seguimientos
        $segPendientes = $this->seguimientoModel->where('estado', 'PENDIENTE')->countAllResults();
        $segEnProceso = $this->seguimientoModel->where('estado', 'EN_PROCESO')->countAllResults();
        $segCompletados = $this->seguimientoModel->where('estado', 'COMPLETADO')->countAllResults();

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
}
