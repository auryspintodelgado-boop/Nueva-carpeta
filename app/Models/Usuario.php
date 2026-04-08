<?php
/**
 * Modelo Usuario
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

require_once __DIR__ . '/Model.php';

class Usuario extends Model {
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'username',
        'password',
        'nombre_completo',
        'correo',
        'rol_id',
        'persona_id',
        'estado'
    ];
    
    protected $hidden = ['password'];
    
    /**
     * Relación con Rol
     */
    public function rol() {
        return $this->belongsTo('Rol', 'rol_id');
    }
    
    /**
     * Relación con Persona
     */
    public function persona() {
        return $this->belongsTo('Persona', 'persona_id');
    }
    
    /**
     * Autenticar usuario
     */
    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE username = ? AND estado = 'Activo'
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Actualizar último acceso
            $this->update($user['id'], ['ultimo_acceso' => date('Y-m-d H:i:s')]);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Buscar por username
     */
    public function findByUsername($username) {
        return $this->findBy('username', $username);
    }
    
    /**
     * Buscar por correo
     */
    public function findByEmail($correo) {
        return $this->findBy('correo', $correo);
    }
    
    /**
     * Obtener usuarios activos
     */
    public function getActivos() {
        return $this->where('estado', 'Activo');
    }
    
    /**
     * Obtener usuarios por rol
     */
    public function getByRol($rolId) {
        return $this->where('rol_id', $rolId);
    }
    
    /**
     * Verificar siusername existe
     */
    public function usernameExists($username, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total FROM {$this->table} 
                WHERE username = ? AND id != ?
            ");
            $stmt->execute([$username, $excludeId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total FROM {$this->table} 
                WHERE username = ?
            ");
            $stmt->execute([$username]);
        }
        
        return $stmt->fetch()['total'] > 0;
    }
    
    /**
     * Verificar si correo existe
     */
    public function emailExists($correo, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total FROM {$this->table} 
                WHERE correo = ? AND id != ?
            ");
            $stmt->execute([$correo, $excludeId]);
        } else {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total FROM {$this->table} 
                WHERE correo = ?
            ");
            $stmt->execute([$correo]);
        }
        
        return $stmt->fetch()['total'] > 0;
    }
    
    /**
     * Encriptar password
     */
    public static function encryptPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verificar password
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Obtener usuarios con información de rol
     */
    public function getWithRoles() {
        $stmt = $this->db->query("
            SELECT u.*, r.nombre as rol_nombre
            FROM {$this->table} u
            JOIN roles r ON u.rol_id = r.id
            ORDER BY u.id DESC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Cambiar estado de usuario
     */
    public function cambiarEstado($id, $estado) {
        return $this->update($id, ['estado' => $estado]);
    }
    
    /**
     * Cambiar password
     */
    public function cambiarPassword($id, $nuevaPassword) {
        $password = self::encryptPassword($nuevaPassword);
        return $this->update($id, ['password' => $password]);
    }
}
