<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluacionModel extends Model
{
    protected $table            = 'evaluaciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'persona_id',
        'tipo_evaluacion',
        'titulo',
        'fecha_evaluacion',
        'resultado',
        'observaciones',
        'calificacion',
        'es_empleado_mes',
        'mes_evaluado',
        'puntuacion',
        'asistencia',
        'puntualidad',
        'trabajo_equipo',
        'iniciativa',
        'archivo',
        'evaluador_id',
        // Nuevos campos para esquema de evaluación (20 puntos, 4 secciones)
        'departamento_id',
        'estado_evaluacion',
        'orientacion_resultados',
        'calidad_organizacion',
        'relaciones_interpersonales',
        'iniciativa',
        'obs_orientacion',
        'obs_calidad',
        'obs_relaciones',
        'obs_iniciativa',
        // Campos detallados - Orientación de Resultados
        'ori_termino_oportuno',
        'ori_cumple_tareas',
        'ori_volumen_adecuado',
        // Campos detallados - Calidad y Organización
        'cal_no_errores',
        'cal_recursos_racionales',
        'cal_supervision',
        'cal_profesional',
        'cal_respetuoso',
        'cal_planifica',
        'cal_indicadores',
        'cal_metas',
        // Campos detallados - Relaciones Interpersonales
        'rel_cortes',
        'rel_orientacion',
        'rel_conflictos',
        'rel_integracion',
        'rel_objetivos',
        // Campos detallados - Iniciativa
        'ini_ideas',
        'ini_cambio',
        'ini_anticipacion',
        'ini_resolucion',
        // Campos adicionales
        'fecha_ingreso',
        'firma_evaluador',
        'nombre_evaluador',
        'fecha_ratificacion',
        'comentarios_adicionales',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'persona_id'       => 'required|is_natural_no_zero',
        'tipo_evaluacion'  => 'required',
        'fecha_evaluacion' => 'required|valid_date',
    ];

    /**
     * Obtiene las evaluaciones de una persona
     */
    public function getEvaluacionesPorPersona($personaId)
    {
        return $this->where('persona_id', $personaId)
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene evaluaciones por tipo
     */
    public function getEvaluacionesPorTipo($tipo)
    {
        return $this->where('tipo_evaluacion', $tipo)
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene la última evaluación de una persona
     */
    public function getUltimaEvaluacion($personaId)
    {
        return $this->where('persona_id', $personaId)
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->first();
    }

    /**
     * Obtiene evaluaciones por evaluador
     */
    public function getEvaluacionesPorEvaluador($evaluadorId)
    {
        return $this->where('evaluador_id', $evaluadorId)
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->findAll();
    }

    /**
     * Cuenta evaluaciones por persona
     */
    public function countEvaluacionesPorPersona($personaId)
    {
        return $this->where('persona_id', $personaId)->countAllResults();
    }

    /**
     * Obtiene el empleado del mes
     */
    public function getEmpleadoDelMes($mes = null)
    {
        if (!$mes) {
            $mes = date('Y-m');
        }

        return $this->where('mes_evaluado', $mes)
                    ->where('es_empleado_mes', 'S')
                    ->first();
    }

    /**
     * Obtiene los candidatos a empleado del mes (top 5 por puntuación)
     */
    public function getCandidatosEmpleadoDelMes($mes = null, $limite = 5)
    {
        if (!$mes) {
            $mes = date('Y-m');
        }

        return $this->select('evaluaciones.*, personas.primer_nombre, personas.primer_apellido, personas.cedula')
                    ->join('personas', 'personas.id = evaluaciones.persona_id')
                    ->where('evaluaciones.mes_evaluado', $mes)
                    ->where('evaluaciones.tipo_evaluacion', 'MENSUAL')
                    ->orderBy('evaluaciones.puntuacion', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Obtiene evaluaciones mensuales por persona
     */
    public function getEvaluacionesMensuales($personaId, $anio = null)
    {
        if (!$anio) {
            $anio = date('Y');
        }

        return $this->where('persona_id', $personaId)
                    ->where('tipo_evaluacion', 'MENSUAL')
                    ->like('fecha_evaluacion', $anio, 'after')
                    ->orderBy('fecha_evaluacion', 'DESC')
                    ->findAll();
    }

    /**
     * Calcula la puntuación total de una evaluación
     */
    public function calcularPuntuacion($data)
    {
        $ponderacion = [
            'asistencia'      => 0.25,
            'puntualidad'     => 0.20,
            'trabajo_equipo'  => 0.25,
            'iniciativa'      => 0.30,
        ];

        $puntuacion = 0;
        foreach ($ponderacion as $campo => $peso) {
            if (isset($data[$campo]) && $data[$campo] > 0) {
                $puntuacion += $data[$campo] * $peso;
            }
        }

        return round($puntuacion, 2);
    }

    /**
     * Calcula la puntuación total del esquema de 20 puntos
     * Suma de las 4 secciones (0-5 puntos cada una)
     */
    public function calcularPuntuacionEsquema($data)
    {
        $puntuacion = 0;
        $secciones = [
            'orientacion_resultados',
            'calidad_organizacion',
            'relaciones_interpersonales',
            'iniciativa'
        ];

        foreach ($secciones as $seccion) {
            if (isset($data[$seccion]) && $data[$seccion] > 0) {
                $puntuacion += floatval($data[$seccion]);
            }
        }

        // Validar máximo de 20 puntos
        return min(round($puntuacion, 2), 20.00);
    }

    /**
     * Obtiene la evaluación mensual de una persona para un mes específico
     */
    public function getEvaluacionMensual($personaId, $mes = null)
    {
        if (!$mes) {
            $mes = date('Y-m');
        }

        return $this->where('persona_id', $personaId)
                    ->where('mes_evaluado', $mes)
                    ->where('tipo_evaluacion', 'MENSUAL')
                    ->first();
    }

    /**
     * Obtiene las evaluaciones de un departamento para un mes específico
     */
    public function getEvaluacionesDepartamento($departamentoId, $mes = null)
    {
        if (!$mes) {
            $mes = date('Y-m');
        }

        return $this->select('evaluaciones.*, personas.primer_nombre, personas.primer_apellido, personas.cedula')
                    ->join('personas', 'personas.id = evaluaciones.persona_id')
                    ->where('evaluaciones.departamento_id', $departamentoId)
                    ->where('evaluaciones.mes_evaluado', $mes)
                    ->where('evaluaciones.tipo_evaluacion', 'MENSUAL')
                    ->orderBy('evaluaciones.puntuacion', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene estadísticas de evaluaciones por departamento
     */
    public function getEstadisticasDepartamento($departamentoId, $mes = null)
    {
        if (!$mes) {
            $mes = date('Y-m');
        }

        $result = $this->select('
            AVG(orientacion_resultados) as avg_orientacion,
            AVG(calidad_organizacion) as avg_calidad,
            AVG(relaciones_interpersonales) as avg_relaciones,
            AVG(iniciativa) as avg_iniciativa,
            AVG(puntuacion) as avg_total,
            COUNT(*) as total_evaluaciones
        ')
        ->where('departamento_id', $departamentoId)
        ->where('mes_evaluado', $mes)
        ->where('tipo_evaluacion', 'MENSUAL')
        ->first();

        return $result;
    }

    /**
     * Obtiene el historial de evaluaciones mensuales de una persona
     */
    public function getHistorialMensual($personaId, $limite = 12)
    {
        return $this->where('persona_id', $personaId)
                    ->where('tipo_evaluacion', 'MENSUAL')
                    ->orderBy('mes_evaluado', 'DESC')
                    ->limit($limite)
                    ->findAll();
    }

    /**
     * Verifica si ya existe una evaluación para el mes actual
     */
    public function existeEvaluacionMensual($personaId, $mes = null)
    {
        if (!$mes) {
            $mes = date('Y-m');
        }

        $evaluacion = $this->where('persona_id', $personaId)
                          ->where('mes_evaluado', $mes)
                          ->where('tipo_evaluacion', 'MENSUAL')
                          ->first();

        return $evaluacion ? true : false;
    }

    /**
     * Calcula la puntuación detallada basada en los 20 sub-campos
     * Retorna un array con las puntuaciones por sección y el total
     */
    public function calcularPuntuacionDetallada($data)
    {
        $resultado = [
            'orientacion' => 0,
            'calidad' => 0,
            'relaciones' => 0,
            'iniciativa' => 0,
            'total' => 0,
        ];

        // Orientación de Resultados (3 sub-campos)
        $oriCampos = ['ori_termino_oportuno', 'ori_cumple_tareas', 'ori_volumen_adecuado'];
        $oriSum = 0;
        foreach ($oriCampos as $campo) {
            if (isset($data[$campo]) && $data[$campo] > 0) {
                $oriSum += intval($data[$campo]);
            }
        }
        // Promedio * 5 = puntuación sobre 5
        $resultado['orientacion'] = count($oriCampos) > 0 ? round(($oriSum / count($oriCampos)) * 5, 2) : 0;

        // Calidad y Organización (8 sub-campos)
        $calCampos = ['cal_no_errores', 'cal_recursos_racionales', 'cal_supervision', 
                       'cal_profesional', 'cal_respetuoso', 'cal_planifica', 
                       'cal_indicadores', 'cal_metas'];
        $calSum = 0;
        foreach ($calCampos as $campo) {
            if (isset($data[$campo]) && $data[$campo] > 0) {
                $calSum += intval($data[$campo]);
            }
        }
        $resultado['calidad'] = count($calCampos) > 0 ? round(($calSum / count($calCampos)) * 5, 2) : 0;

        // Relaciones Interpersonales (5 sub-campos)
        $relCampos = ['rel_cortes', 'rel_orientacion', 'rel_conflictos', 
                      'rel_integracion', 'rel_objetivos'];
        $relSum = 0;
        foreach ($relCampos as $campo) {
            if (isset($data[$campo]) && $data[$campo] > 0) {
                $relSum += intval($data[$campo]);
            }
        }
        $resultado['relaciones'] = count($relCampos) > 0 ? round(($relSum / count($relCampos)) * 5, 2) : 0;

        // Iniciativa (4 sub-campos)
        $iniCampos = ['ini_ideas', 'ini_cambio', 'ini_anticipacion', 'ini_resolucion'];
        $iniSum = 0;
        foreach ($iniCampos as $campo) {
            if (isset($data[$campo]) && $data[$campo] > 0) {
                $iniSum += intval($data[$campo]);
            }
        }
        $resultado['iniciativa'] = count($iniCampos) > 0 ? round(($iniSum / count($iniCampos)) * 5, 2) : 0;

        // Total (suma de las 4 secciones, máximo 20)
        $resultado['total'] = min(round($resultado['orientacion'] + $resultado['calidad'] + 
                                       $resultado['relaciones'] + $resultado['iniciativa'], 2), 20.00);

        return $resultado;
    }

    /**
     * Obtiene el texto descriptivo de la puntuación
     */
    public function getTextoPuntuacion($puntuacion)
    {
        if ($puntuacion >= 18) return 'Excelente';
        if ($puntuacion >= 15) return 'Muy Bueno';
        if ($puntuacion >= 12) return 'Bueno';
        if ($puntuacion >= 10) return 'Regular';
        return 'Necesita Mejorar';
    }
}
