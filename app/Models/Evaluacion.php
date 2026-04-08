<?php
/**
 * Modelo Evaluación
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

require_once __DIR__ . '/Model.php';

class Evaluacion extends Model {
    protected $table = 'evaluaciones';
    protected $primaryKey = 'id';
    protected $fillable = [
        'persona_id',
        'tipo_evaluacion_id',
        'fecha_evaluacion',
        'puntaje',
        'observaciones',
        'resultado',
        'evaluador'
    ];
    
    /**
     * Relación con Persona
     */
    public function persona() {
        return $this->belongsTo('Persona', 'persona_id');
    }
    
    /**
     * Relación con Tipo de Evaluación
     */
    public function tipoEvaluacion() {
        return $this->belongsTo('TipoEvaluacion', 'tipo_evaluacion_id');
    }
    
    /**
     * Obtener evaluaciones de una persona
     */
    public function getByPersona($personaId) {
        return $this->where('persona_id', $personaId);
    }
    
    /**
     * Obtener evaluaciones por tipo
     */
    public function getByTipo($tipoEvaluacionId) {
        return $this->where('tipo_evaluacion_id', $tipoEvaluacionId);
    }
    
    /**
     * Obtener evaluaciones por fecha
     */
    public function getByFecha($fecha) {
        return $this->where('fecha_evaluacion', $fecha);
    }
    
    /**
     * Obtener última evaluación de una persona
     */
    public function getUltimaEvaluacion($personaId) {
        $stmt = $this->db->prepare("
            SELECT e.*, te.nombre as tipo_nombre
            FROM evaluaciones e
            JOIN tipos_evaluacion te ON e.tipo_evaluacion_id = te.id
            WHERE e.persona_id = ?
            ORDER BY e.fecha_evaluacion DESC
            LIMIT 1
        ");
        $stmt->execute([$personaId]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener historial de evaluaciones de una persona
     */
    public function getHistorialCompleto($personaId) {
        $stmt = $this->db->prepare("
            SELECT e.*, te.nombre as tipo_nombre
            FROM evaluaciones e
            JOIN tipos_evaluacion te ON e.tipo_evaluacion_id = te.id
            WHERE e.persona_id = ?
            ORDER BY e.fecha_evaluacion DESC
        ");
        $stmt->execute([$personaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener promedio de puntajes por tipo de evaluación
     */
    public function getPromedioPorTipo() {
        $stmt = $this->db->query("
            SELECT te.nombre as tipo, AVG(e.puntaje) as promedio, COUNT(*) as total
            FROM evaluaciones e
            JOIN tipos_evaluacion te ON e.tipo_evaluacion_id = te.id
            GROUP BY e.tipo_evaluacion_id
            ORDER BY promedio DESC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener evaluaciones en un rango de fechas
     */
    public function getByDateRange($fechaInicio, $fechaFin) {
        $stmt = $this->db->prepare("
            SELECT e.*, te.nombre as tipo_nombre, 
                   CONCAT(p.primer_nombre, ' ', p.primer_apellido) as persona_nombre,
                   p.cedula
            FROM evaluaciones e
            JOIN tipos_evaluacion te ON e.tipo_evaluacion_id = te.id
            JOIN personas p ON e.persona_id = p.id
            WHERE e.fecha_evaluacion BETWEEN ? AND ?
            ORDER BY e.fecha_evaluacion DESC
        ");
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener evaluaciones pendientes
     */
    public function getPendientes() {
        $stmt = $this->db->query("
            SELECT e.*, te.nombre as tipo_nombre,
                   CONCAT(p.primer_nombre, ' ', p.primer_apellido) as persona_nombre,
                   p.cedula
            FROM evaluaciones e
            JOIN tipos_evaluacion te ON e.tipo_evaluacion_id = te.id
            JOIN personas p ON e.persona_id = p.id
            WHERE e.resultado IS NULL OR e.resultado = ''
            ORDER BY e.fecha_evaluacion ASC
        ");
        return $stmt->fetchAll();
    }
}
