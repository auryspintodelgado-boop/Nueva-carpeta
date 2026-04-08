<?php
/**
 * Configuración de Base de Datos
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Configuración de la base de datos
    private $host = 'localhost';
    private $db_name = 'sistema_caracterizacion_aurys';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }
    
    /**
     * Obtener instancia única de la base de datos (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Obtener la conexión PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Prevenir clonación del objeto
     */
    private function __clone() {}
    
    /**
     * Prevenir deserialización
     */
    public function __wakeup() {
        throw new Exception("No se puede deserializar singleton");
    }
}

/**
 * Constantes de configuración de la aplicación
 */
class Config {
    const APP_NAME = 'Sistema de Caracterización';
    const APP_VERSION = '1.0.0';
    const APP_URL = 'http://localhost';
    
    // Rutas
    const ROOT_PATH = __DIR__ . '/..';
    const APP_PATH = self::ROOT_PATH . '/app';
    const VIEW_PATH = self::APP_PATH . '/Views';
    const CONTROLLER_PATH = self::APP_PATH . '/Controllers';
    const MODEL_PATH = self::APP_PATH . '/Models';
    
    // Configuración de paginación
    const ITEMS_PER_PAGE = 20;
    
    // Configuración de sesión
    const SESSION_NAME = 'SISTEMA_CARACTERIZACION';
    const SESSION_LIFETIME = 3600; // 1 hora
}
