<?php

// Script de prueba para verificar actualización de departamento_id
require_once __DIR__ . '/../vendor/autoload.php';

// Configurar el entorno
define('ENVIRONMENT', 'development');
define('ROOTPATH', __DIR__ . '/../');
define('APPPATH', __DIR__ . '/../app/');
define('SYSTEMPATH', __DIR__ . '/../vendor/codeigniter4/framework/system/');
define('WRITEPATH', __DIR__ . '/../writable/');

require_once SYSTEMPATH . 'Config/DotEnv.php';
(new \CodeIgniter\Config\DotEnv(ROOTPATH))->load();

// Configurar la base de datos
$dbConfig = [
    'DSN'      => '',
    'hostname' => getenv('database.default.hostname') ?: 'localhost',
    'username' => getenv('database.default.username') ?: 'root',
    'password' => getenv('database.default.password') ?: '',
    'database' => getenv('database.default.database') ?: 'aurys',
    'DBDriver' => 'MySQLi',
    'DBPrefix' => '',
    'pConnect' => false,
    'DBDebug'  => true,
    'charset'  => 'utf8',
    'DBCollat' => 'utf8_general_ci',
    'swapPre'  => '',
    'encrypt'  => false,
    'compress' => false,
    'strictOn' => false,
    'failover' => [],
    'port'     => 3306,
];

$db = \Config\Database::connect($dbConfig);

// Probar la actualización
echo "=== PRUEBA DE ACTUALIZACIÓN DE DEPARTAMENTO ===\n\n";

// Obtener una persona
$query = $db->query("SELECT id, primer_nombre, primer_apellido, departamento_id FROM personas WHERE estado_registro = 'ACTIVO' LIMIT 1");
$persona = $query->getRowArray();

if (!$persona) {
    echo "No se encontraron personas activas.\n";
    exit;
}

echo "Persona encontrada:\n";
echo "- ID: {$persona['id']}\n";
echo "- Nombre: {$persona['primer_nombre']} {$persona['primer_apellido']}\n";
echo "- Departamento actual: " . ($persona['departamento_id'] ?? 'NULL') . "\n\n";

// Intentar actualizar el departamento_id
$nuevoDeptId = 1; // Asumiendo que existe el departamento con ID 1
echo "Intentando cambiar departamento_id a: {$nuevoDeptId}\n";

// Usar query directa
$result = $db->query("UPDATE personas SET departamento_id = ? WHERE id = ?", [$nuevoDeptId, $persona['id']]);

if ($result) {
    echo "✅ UPDATE ejecutado correctamente.\n";

    // Verificar el resultado
    $query2 = $db->query("SELECT departamento_id FROM personas WHERE id = ?", [$persona['id']]);
    $personaActualizada = $query2->getRowArray();

    echo "Departamento después del update: " . ($personaActualizada['departamento_id'] ?? 'NULL') . "\n";
} else {
    echo "❌ Error en el UPDATE.\n";
}

// Revertir el cambio
$db->query("UPDATE personas SET departamento_id = ? WHERE id = ?", [$persona['departamento_id'], $persona['id']]);
echo "Cambio revertido para no afectar datos de prueba.\n";

echo "\n=== FIN DE PRUEBA ===\n";