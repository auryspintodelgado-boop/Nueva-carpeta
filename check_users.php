<?php

// Script para verificar usuarios en la base de datos
echo "=== VERIFICACIÓN DE USUARIOS EN BASE DE DATOS ===\n\n";

try {
    // Conectar directamente con PDO
    $pdo = new PDO('mysql:host=localhost;dbname=aurys;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si la tabla existe
    $result = $pdo->query("SHOW TABLES LIKE 'usuarios'");
    if ($result->rowCount() === 0) {
        echo "❌ La tabla 'usuarios' no existe en la base de datos.\n";
        exit;
    }

    echo "✅ Conexión a base de datos exitosa.\n\n";

    // Obtener todos los usuarios
    $stmt = $pdo->query("SELECT id, username, email, nombre_completo, rol, departamento_id, estado FROM usuarios ORDER BY rol, username");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "👥 USUARIOS ENCONTRADOS (" . count($usuarios) . "):\n";
    echo str_repeat("=", 80) . "\n";

    foreach ($usuarios as $usuario) {
        $estado = $usuario['estado'] === 'ACTIVO' ? '✅' : '❌';
        $dept = $usuario['departamento_id'] ? "Dept: {$usuario['departamento_id']}" : 'Sin departamento';

        echo sprintf("%-3s %-15s %-25s %-15s %-10s %-15s\n",
            $estado,
            $usuario['username'],
            $usuario['email'],
            $usuario['nombre_completo'],
            $usuario['rol'],
            $dept
        );
    }

    echo "\n" . str_repeat("=", 80) . "\n\n";

    // Verificar específicamente usuarios directores
    $directores = array_filter($usuarios, function($u) { return $u['rol'] === 'DIRECTOR'; });

    echo "🎯 USUARIOS DIRECTORES (" . count($directores) . "):\n";
    foreach ($directores as $director) {
        echo "- {$director['username']} ({$director['email']}) - Dept: " . ($director['departamento_id'] ?? 'NULL') . "\n";
    }

    echo "\n🔍 DEPURACIÓN:\n";

    // Verificar si existe el usuario 'director'
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->execute(['director']);
    $directorUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($directorUser) {
        echo "✅ Usuario 'director' existe\n";
        echo "   - ID: {$directorUser['id']}\n";
        echo "   - Estado: {$directorUser['estado']}\n";
        echo "   - Password hash: " . substr($directorUser['password'], 0, 20) . "...\n";
        echo "   - Departamento: " . ($directorUser['departamento_id'] ?? 'NULL') . "\n";

        // Verificar contraseña
        $passwordCorrect = password_verify('director123', $directorUser['password']);
        echo "   - Contraseña 'director123' correcta: " . ($passwordCorrect ? '✅ SÍ' : '❌ NO') . "\n";
    } else {
        echo "❌ Usuario 'director' NO existe\n";
    }

    // Verificar departamentos
    $stmt = $pdo->query("SELECT id, nombre, codigo FROM departamentos WHERE estado = 'ACTIVO' ORDER BY nombre");
    $departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "\n🏢 DEPARTAMENTOS ACTIVOS (" . count($departamentos) . "):\n";
    foreach ($departamentos as $dept) {
        echo "- {$dept['codigo']}: {$dept['nombre']} (ID: {$dept['id']})\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE VERIFICACIÓN ===\n";