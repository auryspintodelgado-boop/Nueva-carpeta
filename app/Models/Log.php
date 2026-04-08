<?php
/**
 * Modelo Log
 * Sistema de Evaluación, Seguimiento y Caracterización
 * 
 * Maneja el registro de actividad en el sistema
 */

require_once __DIR__ . '/Model.php';

class Log extends Model {
    protected $table = 'logs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'usuario_id',
        'accion',
        'tabla_afectada',
        'registro_id',
        'datos_anteriores',
        'datos_nuevos',
        'ip_address',
        'user_agent'
    ];
    
    protected $timestamps = false;
    
    /**
     * Relación con Usuario
     */
    public function usuario() {
        return $this->belongsTo('Usuario', 'usuario_id');
    }
    
    /**
     * Registrar una acción
     */
    public function registrar($data) {
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? null;
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        return $this->create($data);
    }
    
    /**
     * Obtener logs de un usuario
     */
    public function getByUsuario($usuarioId) {
        return $this->where('usuario_id', $usuarioId);
    }
    
    /**
     * Obtener logs por tabla afectada
     */
    public function getByTabla($tabla) {
        return $this->where('tabla_afectada', $tabla);
    }
    
    /**
     * Obtener logs por acción
     */
    public function getByAccion($accion) {
        return $this->where('accion', $accion);
    }
    
    /**
     * Obtener logs recientes
     */
    public function getRecientes($limite = 50) {
        $stmt = $this->db->prepare("
            SELECT l.*, u.username, u.nombre_completo
            FROM {$this->table} l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            ORDER BY l.created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener logs de un registro específico
     */
    public function getByRegistro($tabla, $registroId) {
        $stmt = $this->db->prepare("
            SELECT l.*, u.username
            FROM {$this->table} l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            WHERE l.tabla_afectada = ? AND l.registro_id = ?
            ORDER BY l.created_at DESC
        ");
        $stmt->execute([$tabla, $registroId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener logs en un rango de fechas
     */
    public function getByDateRange($fechaInicio, $fechaFin) {
        $stmt = $this->db->prepare("
            SELECT l.*, u.username, u.nombre_completo
            FROM {$this->table} l
            LEFT JOIN usuarios u ON l.usuario_id = u.id
            WHERE l.created_at BETWEEN ? AND ?
            ORDER BY l.created_at DESC
        ");
        $stmt->execute([$fechaInicio, $fechaFin]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener estadísticas de actividad
     */
    public function getEstadisticas() {
        $stats = [];
        
        // Total de acciones por tipo
        $stmt = $this->db->query("
            SELECT accion, COUNT(*) as total 
            FROM {$this->table} 
            GROUP BY accion 
            ORDER BY total DESC
        ");
        $stats['por_accion'] = $stmt->fetchAll();
        
        // Total de acciones por tabla
        $stmt = $this->db->query("
            SELECT tabla_afectada, COUNT(*) as total 
            FROM {$this->table} 
            GROUP BY tabla_afectada 
            ORDER BY total DESC
        ");
        $stats['por_tabla'] = $stmt->fetchAll();
        
        // Usuarios más activos
        $stmt = $this->db->query("
            SELECT u.username, COUNT(*) as total
            FROM {$this->table} l
            JOIN usuarios u ON l.usuario_id = u.id
            GROUP BY l.usuario_id
            ORDER BY total DESC
            LIMIT 10
        ");
        $stats['usuarios_activos'] = $stmt->fetchAll();
        
        // Acciones hoy
        $stmt = $this->db->query("
            SELECT COUNT(*) as total 
            FROM {$this->table} 
            WHERE DATE(created_at) = CURDATE()
        ");
        $stats['hoy'] = $stmt->fetch()['total'];
        
        // Acciones esta semana
        $stmt = $this->db->query("
            SELECT COUNT(*) as total 
            FROM {$this->table} 
            WHERE YEARWEEK(created_at) = YEARWEEK(NOW())
        ");
        $stats['semana'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    /**
     * Método estático para registrar fácilmente
     */
    public static function add($accion, $tabla, $registroId, $usuarioId = null, $datosAnteriores = null, $datosNuevos = null) {
        $log = new self();
        
        $data = [
            'usuario_id' => $usuarioId,
            'accion' => $accion,
            'tabla_afectada' => $tabla,
            'registro_id' => $registroId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];
        
        if ($datosAnteriores) {
            $data['datos_anteriores'] = json_encode($datosAnteriores);
        }
        
        if ($datosNuevos) {
            $data['datos_nuevos'] = json_encode($datosNuevos);
        }
        
        return $log->create($data);
    }
}
