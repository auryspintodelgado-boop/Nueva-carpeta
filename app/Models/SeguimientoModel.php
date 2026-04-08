<?php

namespace App\Models;

use CodeIgniter\Model;

class SeguimientoModel extends Model
{
    protected $table            = 'seguimientos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'persona_id',
        'tipo_seguimiento',
        'fecha_seguimiento',
        'descripcion',
        'acciones',
        'resultado',
        'proxima_fecha',
        'prioridad',
        'estado',
        'usuario_seguimiento',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'persona_id'        => 'required|is_natural_no_zero',
        'tipo_seguimiento'  => 'required',
        'fecha_seguimiento' => 'required|valid_date',
    ];

    /**
     * Obtiene los seguimientos de una persona
     */
    public function getSeguimientosPorPersona($personaId)
    {
        return $this->where('persona_id', $personaId)
                    ->orderBy('fecha_seguimiento', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene seguimientos por tipo
     */
    public function getSeguimientosPorTipo($tipo)
    {
        return $this->where('tipo_seguimiento', $tipo)
                    ->orderBy('fecha_seguimiento', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene seguimientos por estado
     */
    public function getSeguimientosPorEstado($estado)
    {
        return $this->where('estado', $estado)
                    ->orderBy('fecha_seguimiento', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene seguimientos pendientes
     */
    public function getSeguimientosPendientes()
    {
        return $this->where('estado', 'PENDIENTE')
                    ->orWhere('estado', 'EN_PROCESO')
                    ->orderBy('proxima_fecha', 'ASC')
                    ->findAll();
    }

    /**
     * Obtiene seguimientos por prioridad
     */
    public function getSeguimientosPorPrioridad($prioridad)
    {
        return $this->where('prioridad', $prioridad)
                    ->orderBy('fecha_seguimiento', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene seguimientos de un usuario
     */
    public function getSeguimientosPorUsuario($usuarioId)
    {
        return $this->where('usuario_seguimiento', $usuarioId)
                    ->orderBy('fecha_seguimiento', 'DESC')
                    ->findAll();
    }

    /**
     * Obtiene seguimientos próximos (próximos 7 días)
     */
    public function getSeguimientosProximos()
    {
        $fecha = date('Y-m-d', strtotime('+7 days'));
        return $this->where('proxima_fecha <=', $fecha)
                    ->where('estado', 'PENDIENTE')
                    ->orderBy('proxima_fecha', 'ASC')
                    ->findAll();
    }

    /**
     * Cuenta seguimientos por persona
     */
    public function countSeguimientosPorPersona($personaId)
    {
        return $this->where('persona_id', $personaId)->countAllResults();
    }
}
