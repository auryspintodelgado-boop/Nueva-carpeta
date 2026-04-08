<?php
/**
 * Script completo de importación del Excel a la base de datos
 */

require 'vendor/autoload.php';
require 'app/Config/Database.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Config\Database;

// Configurar conexión a base de datos
$dbConfig = new Database();
$db = \Config\Database::connect();

$excelFile = 'Fundación _Castillo San Antonio de la Eminencia_ (Respuestas).xlsx';

try {
    echo "=== INICIANDO IMPORTACIÓN COMPLETA ===\n\n";

    $spreadsheet = IOFactory::load($excelFile);
    $sheet = $spreadsheet->getSheet(0);
    $highestRow = $sheet->getHighestRow();

    echo "Procesando $highestRow filas...\n\n";

    $imported = 0;
    $errors = 0;
    $skipped = 0;

    // Procesar cada fila (empezar desde 2 para saltar encabezados)
    for ($row = 2; $row <= $highestRow; $row++) {
        try {
            // Obtener cédula
            $cedula = trim($sheet->getCell('B' . $row)->getValue());
            if (empty($cedula)) {
                $skipped++;
                echo "Fila $row: Cédula vacía, saltando...\n";
                continue;
            }

            // Verificar si ya existe
            $existing = $db->table('personas')->where('cedula', $cedula)->get()->getRow();
            if ($existing) {
                $skipped++;
                echo "Fila $row: Persona con cédula $cedula ya existe\n";
                continue;
            }

            // Mapear datos del Excel a la estructura de la base de datos
            $nombres = trim($sheet->getCell('C' . $row)->getValue());
            $apellidos = trim($sheet->getCell('D' . $row)->getValue());

            // Separar nombres y apellidos si es necesario
            $primerNombre = '';
            $segundoNombre = '';
            $primerApellido = '';
            $segundoApellido = '';

            if (!empty($nombres)) {
                $partesNombres = explode(' ', $nombres);
                $primerNombre = $partesNombres[0] ?? '';
                $segundoNombre = $partesNombres[1] ?? '';
            }

            if (!empty($apellidos)) {
                $partesApellidos = explode(' ', $apellidos);
                $primerApellido = $partesApellidos[0] ?? '';
                $segundoApellido = $partesApellidos[1] ?? '';
            }

            // Mapear sexo
            $sexoExcel = trim($sheet->getCell('F' . $row)->getValue());
            $sexoId = null;
            if ($sexoExcel == 'Masculino') $sexoId = 1;
            elseif ($sexoExcel == 'Femenino') $sexoId = 2;

            // Mapear nacionalidad
            $nacionalidadExcel = trim($sheet->getCell('E' . $row)->getValue());
            $nacionalidadId = 1; // Venezolano por defecto
            if (stripos($nacionalidadExcel, 'colombiano') !== false) $nacionalidadId = 2;
            elseif (stripos($nacionalidadExcel, 'ecuatoriano') !== false) $nacionalidadId = 3;

            // Mapear estado civil
            $estadoCivilExcel = trim($sheet->getCell('I' . $row)->getValue());
            $estadoCivilId = null;
            if (stripos($estadoCivilExcel, 'soltero') !== false) $estadoCivilId = 1;
            elseif (stripos($estadoCivilExcel, 'casado') !== false) $estadoCivilId = 2;
            elseif (stripos($estadoCivilExcel, 'divorciado') !== false) $estadoCivilId = 3;
            elseif (stripos($estadoCivilExcel, 'viudo') !== false) $estadoCivilId = 4;

            // Procesar fecha de nacimiento
            $fechaNacimientoExcel = trim($sheet->getCell('G' . $row)->getValue());
            $fechaNacimiento = null;
            $edad = null;

            if (!empty($fechaNacimientoExcel)) {
                // Intentar diferentes formatos de fecha
                if (is_numeric($fechaNacimientoExcel)) {
                    // Formato Excel (número de serie)
                    $fechaNacimiento = date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($fechaNacimientoExcel));
                } else {
                    // Intentar parsear como fecha normal
                    $parsed = date_parse($fechaNacimientoExcel);
                    if ($parsed['year'] && $parsed['month'] && $parsed['day']) {
                        $fechaNacimiento = sprintf('%04d-%02d-%02d', $parsed['year'], $parsed['month'], $parsed['day']);
                    }
                }

                if ($fechaNacimiento) {
                    $edad = date_diff(date_create($fechaNacimiento), date_create('today'))->y;
                }
            }

            // Preparar datos para inserción
            $data = [
                'cedula' => $cedula,
                'primer_nombre' => $primerNombre ?: null,
                'segundo_nombre' => $segundoNombre ?: null,
                'primer_apellido' => $primerApellido ?: null,
                'segundo_apellido' => $segundoApellido ?: null,
                'nacionalidad_id' => $nacionalidadId,
                'sexo_id' => $sexoId,
                'fecha_nacimiento' => $fechaNacimiento,
                'edad' => $edad,
                'estado_civil_id' => $estadoCivilId,
                'telefono_1' => trim($sheet->getCell('J' . $row)->getValue()) ?: null,
                'correo_electronico' => trim($sheet->getCell('K' . $row)->getValue()) ?: null,
                'pais_id' => 1, // Venezuela por defecto
                'estado_registro_id' => 1, // Activo
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Limpiar valores vacíos
            foreach ($data as $key => $value) {
                if ($value === '' || $value === 0) {
                    $data[$key] = null;
                }
            }

            // Insertar en la base de datos
            $result = $db->table('personas')->insert($data);

            if ($result) {
                $imported++;
                echo "Fila $row: ✓ Importada persona $cedula ($primerNombre $primerApellido)\n";
            } else {
                $errors++;
                echo "Fila $row: ✗ Error al insertar $cedula\n";
            }

        } catch (Exception $e) {
            $errors++;
            echo "Fila $row: ✗ Error procesando - " . $e->getMessage() . "\n";
        }
    }

    echo "\n=== IMPORTACIÓN COMPLETADA ===\n";
    echo "✅ Registros importados: $imported\n";
    echo "❌ Errores: $errors\n";
    echo "⏭️  Saltados (duplicados/vacíos): $skipped\n";
    echo "📊 Total procesado: " . ($imported + $errors + $skipped) . "\n";

} catch (Exception $e) {
    echo "Error general: " . $e->getMessage() . "\n";
}
