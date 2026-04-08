<?php
/**
 * Modelo Seguimiento
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

require_once __DIR__ . '/Model.php';

class Seguimiento extends Model {
    protected $table = 'seguimientos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'persona_id',
        'tipo_seguimiento_id',
        'fecha_seguimiento',
        'descripcion',
        'resultado',
        'proxima_fecha',
        'responsable',
        'estado_seguimiento'
    ];
    
    /**
     * Relación con Persona
     */
    public function persona() {
        return $this->belongsTo('Persona', 'persona_id');
    }
    
    /**
     * Relación con Tipo de Seguimiento
     */
    public function tipoSeguimiento() {
        return $this->belongsTo('TipoSeguimiento', 'tipo_seguimiento_id');
    }
    
    /**
     * Obtener seguimientos de una persona
     */
    public function getByPersona($personaId) {
        return $this->where('persona_id', $personaId);
    }
    
    /**
     * Obtener seguimientos por tipo
     */
    public function getByTipo($tipoSeguimientoId) {
        return $this->where('tipo_seguimiento_id', $tipoSeguimientoId);
    }
    
    /**
     * Obtener seguimientos activos
     */
    public function getActivos() {
        $stmt = $this->db->query("
            SELECT * FROM {$this->table} 
            WHERE estado_seguimiento IN ('Pendiente', 'En_Proceso')
            ORDER BY fecha_seguimiento ASC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener seguimientos completados
     */
    public function getCompletados() {
        return $this->where('estado_seguimiento', 'Completado');
    }
    
    /**
     * Obtener seguimientos pendientes
     */
    public function getPendientes() {
        return $this->where('estado_seguimiento', 'Pendiente');
    }
    
    /**
     * Obtener último seguimiento de una persona
     */
    public function getUltimoSeguimiento($personaId) {
        $stmt = $this->db->prepare("
            SELECT s.*, ts.nombre as tipo_nombre
            FROM seguimientos s
            JOIN tipos_seguimiento ts ON s.tipo_seguimiento_id = ts.id
            WHERE s.persona_id = ?
            ORDER BY s.fecha_seguimiento DESC
            LIMIT 1
        ");
        $stmt->execute([$personaId]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener historial de seguimientos de una persona
     */
    public function getHistorialCompleto($personaId) {
        $stmt = $this->db->prepare("
            SELECT s.*, ts.nombre as tipo_nombre
            FROM seguimientos s
            JOIN tipos_seguimiento ts ON s.tipo_seguimiento_id = ts.id
            WHERE s.persona_id = ?
            ORDER BY s.fecha_seguimiento DESC
        ");
        $stmt->execute([$personaId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener seguimientos por responsable
     */
    public function getByResponsable($responsable) {
        return $this->where('responsable', $responsable);
    }
    
    /**
     * Obtener seguimientos próximos a vencer
     */
    public function getProximosAVencer($dias = 7) {
        $stmt = $this->db->query("
            SELECT s.*, ts.nombre as tipo_nombre,
                   CONCAT(p.primer_nombre, ' ', p.primer_apellido) as persona_nombre,
                   p.cedula
            FROM seguimientos s
            JOIN tipos_seguimiento ts ON s.tipo_seguimiento_id = ts.id
            JOIN personas p ON s.persona_id = p.id
            WHERE s.estado_seguimiento = 'Pendiente'
            AND s.proxima_fecha IS NOT NULL
            AND s.proxima_fecha BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL {$dias} DAY)
            ORDER BY s.proxima_fecha ASC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener seguimientos vencidos
     */
    public function getVencidos() {
        $stmt = $this->db->query("
            SELECT s.*, ts.nombre as tipo_nombre,
                   CONCAT(p.primer_nombre, ' ', p.primer_apellido) as persona_nombre,
                   p.cedula
            FROM seguimientos s
            JOIN tipos_seguimiento ts ON s.tipo_seguimiento_id = ts.id
            JOIN personas p ON s.persona_id = p.id
            WHERE s.estado_seguimiento = 'Pendiente'
            AND s.proxima_fecha < CURDATE()
            ORDER BY s.proxima_fecha ASC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estadísticas de seguimientos
     */
    public function getEstadisticas() {
        $stats = [];
        
        $stmt = $this->db->query("
            SELECT estado_seguimiento, COUNT(*) as total 
            FROM {$this->table} 
            GROUP BY estado_seguimiento
        ");
        $stats['por_estado'] = $stmt->fetchAll();
        
        $stmt = $this->db->query("
            SELECT ts.nombre as tipo, COUNT(*) as total
            FROM {$this->table} s
            JOIN tipos_seguimiento ts ON s.tipo_seguimiento_id = ts.id
            GROUP BY s.tipo_seguimiento_id
            ORDER BY total DESC
        ");
        $stats['por_tipo'] = $stmt->fetchAll();
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE estado_seguimiento = 'Pendiente'");
        $stats['pendientes'] = $stmt->fetch()['total'];
        
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE estado_seguimiento = 'Completado'");
        $stats['completados'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    /**
     * Marcar seguimiento como completado
     */
    public function marcarCompletado($id, $resultado) {
        $data = [
            'estado_seguimiento' => 'Completado',
            'resultado' => $resultado
        ];
        return $this->update($id, $data);
    }
    
    /**
     * Marcar seguimiento como en proceso
     */
    public function marcarEnProceso($id) {
        $data = ['estado_seguimiento' => 'En_Proceso'];
        return $this->update($id, $data);
    }
    
    /**
     * Cancelar seguimiento
     */
    public function cancelar($id) {
        $data = ['estado_seguimiento' => 'Cancelado'];
        return $this->update($id, $data);
    }
}
