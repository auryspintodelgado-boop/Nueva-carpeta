<?php
/**
 * Script completo de importación de TODOS los datos del Excel
 */

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Configurar conexión a MySQL
try {
    $db = new PDO('mysql:host=localhost;dbname=aurys;charset=utf8mb4', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión a base de datos exitosa.\n\n";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage() . "\n");
}

$excelFile = 'Fundación _Castillo San Antonio de la Eminencia_ (Respuestas).xlsx';

try {
    echo "=== INICIANDO IMPORTACIÓN COMPLETA DE TODOS LOS DATOS ===\n\n";

    $spreadsheet = IOFactory::load($excelFile);
    $sheet = $spreadsheet->getSheet(0);
    $highestRow = $sheet->getHighestRow();

    echo "Procesando $highestRow filas con datos completos...\n\n";

    $imported = 0;
    $errors = 0;
    $skipped = 0;

    // Procesar filas (empezar desde 2 para saltar encabezados)
    for ($row = 2; $row <= $highestRow; $row++) {
        try {
            $cedula = trim($sheet->getCell('B' . $row)->getValue());
            if (empty($cedula)) {
                $skipped++;
                continue;
            }

            // Verificar si ya existe
            $stmt = $db->prepare("SELECT id FROM personas WHERE cedula = ?");
            $stmt->execute([$cedula]);
            $existing = $stmt->fetch();

            if ($existing) {
                // Actualizar registro existente con datos adicionales
                $personId = $existing['id'];
                echo "Fila $row: Actualizando datos adicionales para cédula $cedula\n";
            } else {
                // Crear nuevo registro
                $personId = null;
                echo "Fila $row: Creando nuevo registro para cédula $cedula\n";
            }

            // DATOS PERSONALES BÁSICOS
            $nombres = trim($sheet->getCell('C' . $row)->getValue());
            $apellidos = trim($sheet->getCell('D' . $row)->getValue());

            $primerNombre = '';
            $segundoNombre = '';
            if (!empty($nombres)) {
                $partesNombres = explode(' ', $nombres, 2);
                $primerNombre = $partesNombres[0];
                $segundoNombre = $partesNombres[1] ?? '';
            }

            $primerApellido = '';
            $segundoApellido = '';
            if (!empty($apellidos)) {
                $partesApellidos = explode(' ', $apellidos, 2);
                $primerApellido = $partesApellidos[0];
                $segundoApellido = $partesApellidos[1] ?? '';
            }

            $sexoExcel = trim($sheet->getCell('F' . $row)->getValue());
            $sexo = null;
            if ($sexoExcel == 'Masculino') $sexo = 'M';
            elseif ($sexoExcel == 'Femenino') $sexo = 'F';

            $estadoCivilExcel = trim($sheet->getCell('I' . $row)->getValue());
            $estadoCivil = null;
            if (stripos($estadoCivilExcel, 'soltero') !== false) $estadoCivil = 'SOLTERO';
            elseif (stripos($estadoCivilExcel, 'casado') !== false) $estadoCivil = 'CASADO';
            elseif (stripos($estadoCivilExcel, 'divorciado') !== false) $estadoCivil = 'DIVORCIADO';
            elseif (stripos($estadoCivilExcel, 'viudo') !== false) $estadoCivil = 'VIUDO';
            elseif (stripos($estadoCivilExcel, 'unión') !== false) $estadoCivil = 'UNION_LIBRE';

            $fechaNacimientoExcel = trim($sheet->getCell('G' . $row)->getValue());
            $fechaNacimiento = null;
            $edad = null;

            if (!empty($fechaNacimientoExcel)) {
                if (is_numeric($fechaNacimientoExcel)) {
                    $fechaNacimiento = date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($fechaNacimientoExcel));
                } else {
                    $timestamp = strtotime($fechaNacimientoExcel);
                    if ($timestamp) {
                        $fechaNacimiento = date('Y-m-d', $timestamp);
                    }
                }

                if ($fechaNacimiento) {
                    $edad = date_diff(date_create($fechaNacimiento), date_create('today'))->y;
                }
            }

            // DATOS ADICIONALES
            $estado = trim($sheet->getCell('L' . $row)->getValue());
            $municipio = trim($sheet->getCell('M' . $row)->getValue());
            $parroquia = trim($sheet->getCell('N' . $row)->getValue());
            $comuna = trim($sheet->getCell('O' . $row)->getValue());

            // DATOS ACADÉMICOS
            $gradoInstruccion = trim($sheet->getCell('Q' . $row)->getValue());
            $estudia = trim($sheet->getCell('R' . $row)->getValue());
            $carrera = trim($sheet->getCell('S' . $row)->getValue());
            $anioSemestre = trim($sheet->getCell('T' . $row)->getValue());
            $beca = trim($sheet->getCell('U' . $row)->getValue());
            $sede = trim($sheet->getCell('V' . $row)->getValue());
            $universidad = trim($sheet->getCell('W' . $row)->getValue());
            $tipoIeu = trim($sheet->getCell('X' . $row)->getValue());
            $nivelAcademico = trim($sheet->getCell('Y' . $row)->getValue());

            // DEPARTAMENTO Y CARGO
            $departamento = trim($sheet->getCell('Z' . $row)->getValue());
            $cargo = trim($sheet->getCell('AA' . $row)->getValue());
            $fechaIngreso = trim($sheet->getCell('AB' . $row)->getValue());

            // SALUD
            $discapacidad = trim($sheet->getCell('AC' . $row)->getValue());
            $descripcionDiscapacidad = trim($sheet->getCell('AD' . $row)->getValue());
            $enfermedad = trim($sheet->getCell('AE' . $row)->getValue());
            $condicionMedica = trim($sheet->getCell('AF' . $row)->getValue());
            $medicamentos = trim($sheet->getCell('AG' . $row)->getValue());

            // MEDIDAS
            $tallaCamisa = trim($sheet->getCell('AH' . $row)->getValue());
            $tallaPantalon = trim($sheet->getCell('AI' . $row)->getValue());
            $tallaCalzado = trim($sheet->getCell('AJ' . $row)->getValue());
            $estatura = trim($sheet->getCell('AK' . $row)->getValue());
            $peso = trim($sheet->getCell('AL' . $row)->getValue());
            $tipoSangre = trim($sheet->getCell('AM' . $row)->getValue());

            // FAMILIAR
            $tieneHijos = trim($sheet->getCell('AN' . $row)->getValue());
            $cantidadHijos = trim($sheet->getCell('AO' . $row)->getValue());

            // TRANSPORTE Y ELECTORAL
            $medioTransporte = trim($sheet->getCell('AP' . $row)->getValue());
            $inscritoCne = trim($sheet->getCell('AQ' . $row)->getValue());
            $centroElectoral = trim($sheet->getCell('AR' . $row)->getValue());

            // DOCUMENTOS
            $cedulaVigente = trim($sheet->getCell('AS' . $row)->getValue());
            $pasaporte = trim($sheet->getCell('AT' . $row)->getValue());
            $licencia = trim($sheet->getCell('AU' . $row)->getValue());

            // Mapeos de valores
            $poseeDiscapacidad = ($discapacidad == 'Sí') ? 'S' : (($discapacidad == 'No') ? 'N' : null);
            $presentaEnfermedad = ($enfermedad == 'Sí') ? 'S' : (($enfermedad == 'No') ? 'N' : null);
            $tieneHijosMapped = ($tieneHijos == 'Sí') ? 'S' : (($tieneHijos == 'No') ? 'N' : null);
            $inscritoCneMapped = ($inscritoCne == 'Sí') ? 'S' : (($inscritoCne == 'No') ? 'N' : null);

            $tipoIeuMapped = null;
            if (stripos($tipoIeu, 'pública') !== false) $tipoIeuMapped = 'PUBLICA';
            elseif (stripos($tipoIeu, 'privada') !== false) $tipoIeuMapped = 'PRIVADA';

            $nivelAcademicoMapped = null;
            if (stripos($nivelAcademico, 'pregrado') !== false) $nivelAcademicoMapped = 'PREGRADO';
            elseif (stripos($nivelAcademico, 'postgrado') !== false) $nivelAcademicoMapped = 'POSTGRADO';

            $becaMapped = null;
            if (stripos($beca, 'sí') !== false) $becaMapped = 'S';
            elseif (stripos($beca, 'no') !== false) $becaMapped = 'N';

            // Procesar fecha de ingreso
            $fechaIngresoFormatted = null;
            if (!empty($fechaIngreso)) {
                if (is_numeric($fechaIngreso)) {
                    $fechaIngresoFormatted = date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($fechaIngreso));
                } else {
                    $timestamp = strtotime($fechaIngreso);
                    if ($timestamp) {
                        $fechaIngresoFormatted = date('Y-m-d', $timestamp);
                    }
                }
            }

            if ($existing) {
                // UPDATE registro existente
                $sql = "UPDATE personas SET
                    nacionalidad = :nacionalidad,
                    estado = :estado,
                    municipio = :municipio,
                    parroquia = :parroquia,
                    comuna = :comuna,
                    grado_instruccion = :grado_instruccion,
                    estudia = :estudia,
                    carrera = :carrera,
                    ano_semestre = :ano_semestre,
                    posee_beca = :posee_beca,
                    sede = :sede,
                    universidad = :universidad,
                    tipo_ieu = :tipo_ieu,
                    nivel_academico = :nivel_academico,
                    departamento = :departamento,
                    cargo = :cargo,
                    fecha_ingreso = :fecha_ingreso,
                    posee_discapacidad = :posee_discapacidad,
                    descripcion_discapacidad = :descripcion_discapacidad,
                    presenta_enfermedad = :presenta_enfermedad,
                    condicion_medica = :condicion_medica,
                    medicamentos = :medicamentos,
                    talla_camisa = :talla_camisa,
                    talla_pantalon = :talla_pantalon,
                    talla_zapatos = :talla_zapatos,
                    altura = :altura,
                    peso = :peso,
                    tipo_sangre = :tipo_sangre,
                    tiene_hijos = :tiene_hijos,
                    cantidad_hijos = :cantidad_hijos,
                    medio_transporte = :medio_transporte,
                    inscrito_cne = :inscrito_cne,
                    centro_electoral = :centro_electoral,
                    cedula_vigente = :cedula_vigente,
                    pasaporte_vigente = :pasaporte_vigente,
                    licencia_conducir = :licencia_conducir,
                    updated_at = NOW()
                    WHERE cedula = :cedula";

                $stmt = $db->prepare($sql);
                $result = $stmt->execute([
                    ':nacionalidad' => trim($sheet->getCell('E' . $row)->getValue()) ?: null,
                    ':estado' => $estado ?: null,
                    ':municipio' => $municipio ?: null,
                    ':parroquia' => $parroquia ?: null,
                    ':comuna' => $comuna ?: null,
                    ':grado_instruccion' => $gradoInstruccion ?: null,
                    ':estudia' => $estudia ?: null,
                    ':carrera' => $carrera ?: null,
                    ':ano_semestre' => $anioSemestre ?: null,
                    ':posee_beca' => $becaMapped,
                    ':sede' => $sede ?: null,
                    ':universidad' => $universidad ?: null,
                    ':tipo_ieu' => $tipoIeuMapped,
                    ':nivel_academico' => $nivelAcademicoMapped,
                    ':departamento' => $departamento ?: null,
                    ':cargo' => $cargo ?: null,
                    ':fecha_ingreso' => $fechaIngresoFormatted,
                    ':posee_discapacidad' => $poseeDiscapacidad,
                    ':descripcion_discapacidad' => $descripcionDiscapacidad ?: null,
                    ':presenta_enfermedad' => $presentaEnfermedad,
                    ':condicion_medica' => $condicionMedica ?: null,
                    ':medicamentos' => $medicamentos ?: null,
                    ':talla_camisa' => $tallaCamisa ?: null,
                    ':talla_pantalon' => $tallaPantalon ?: null,
                    ':talla_zapatos' => $tallaCalzado ?: null,
                    ':altura' => $estatura ?: null,
                    ':peso' => $peso ?: null,
                    ':tipo_sangre' => $tipoSangre ?: null,
                    ':tiene_hijos' => $tieneHijosMapped,
                    ':cantidad_hijos' => $cantidadHijos ?: null,
                    ':medio_transporte' => $medioTransporte ?: null,
                    ':inscrito_cne' => $inscritoCneMapped,
                    ':centro_electoral' => $centroElectoral ?: null,
                    ':cedula_vigente' => $cedulaVigente ?: null,
                    ':pasaporte_vigente' => $pasaporte ?: null,
                    ':licencia_conducir' => $licencia ?: null,
                    ':cedula' => $cedula
                ]);
            } else {
                // INSERT nuevo registro
                $sql = "INSERT INTO personas (
                    cedula, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                    sexo, fecha_nacimiento, edad, telefono1, correo_electronico,
                    estado_civil, nacionalidad, estado, municipio, parroquia, comuna,
                    grado_instruccion, estudia, carrera, ano_semestre, posee_beca,
                    sede, universidad, tipo_ieu, nivel_academico, departamento, cargo,
                    fecha_ingreso, posee_discapacidad, descripcion_discapacidad,
                    presenta_enfermedad, condicion_medica, medicamentos, talla_camisa,
                    talla_pantalon, talla_zapatos, altura, peso, tipo_sangre,
                    tiene_hijos, cantidad_hijos, medio_transporte, inscrito_cne,
                    centro_electoral, cedula_vigente, pasaporte_vigente, licencia_conducir,
                    estado_registro, fecha_registro, created_at, updated_at
                ) VALUES (
                    :cedula, :primer_nombre, :segundo_nombre, :primer_apellido, :segundo_apellido,
                    :sexo, :fecha_nacimiento, :edad, :telefono1, :correo_electronico,
                    :estado_civil, :nacionalidad, :estado, :municipio, :parroquia, :comuna,
                    :grado_instruccion, :estudia, :carrera, :ano_semestre, :posee_beca,
                    :sede, :universidad, :tipo_ieu, :nivel_academico, :departamento, :cargo,
                    :fecha_ingreso, :posee_discapacidad, :descripcion_discapacidad,
                    :presenta_enfermedad, :condicion_medica, :medicamentos, :talla_camisa,
                    :talla_pantalon, :talla_zapatos, :altura, :peso, :tipo_sangre,
                    :tiene_hijos, :cantidad_hijos, :medio_transporte, :inscrito_cne,
                    :centro_electoral, :cedula_vigente, :pasaporte_vigente, :licencia_conducir,
                    'ACTIVO', NOW(), NOW(), NOW()
                )";

                $stmt = $db->prepare($sql);
                $result = $stmt->execute([
                    ':cedula' => $cedula,
                    ':primer_nombre' => $primerNombre ?: null,
                    ':segundo_nombre' => $segundoNombre ?: null,
                    ':primer_apellido' => $primerApellido ?: null,
                    ':segundo_apellido' => $segundoApellido ?: null,
                    ':sexo' => $sexo,
                    ':fecha_nacimiento' => $fechaNacimiento,
                    ':edad' => $edad,
                    ':telefono1' => trim($sheet->getCell('J' . $row)->getValue()) ?: null,
                    ':correo_electronico' => trim($sheet->getCell('K' . $row)->getValue()) ?: null,
                    ':estado_civil' => $estadoCivil,
                    ':nacionalidad' => trim($sheet->getCell('E' . $row)->getValue()) ?: null,
                    ':estado' => $estado ?: null,
                    ':municipio' => $municipio ?: null,
                    ':parroquia' => $parroquia ?: null,
                    ':comuna' => $comuna ?: null,
                    ':grado_instruccion' => $gradoInstruccion ?: null,
                    ':estudia' => $estudia ?: null,
                    ':carrera' => $carrera ?: null,
                    ':ano_semestre' => $anioSemestre ?: null,
                    ':posee_beca' => $becaMapped,
                    ':sede' => $sede ?: null,
                    ':universidad' => $universidad ?: null,
                    ':tipo_ieu' => $tipoIeuMapped,
                    ':nivel_academico' => $nivelAcademicoMapped,
                    ':departamento' => $departamento ?: null,
                    ':cargo' => $cargo ?: null,
                    ':fecha_ingreso' => $fechaIngresoFormatted,
                    ':posee_discapacidad' => $poseeDiscapacidad,
                    ':descripcion_discapacidad' => $descripcionDiscapacidad ?: null,
                    ':presenta_enfermedad' => $presentaEnfermedad,
                    ':condicion_medica' => $condicionMedica ?: null,
                    ':medicamentos' => $medicamentos ?: null,
                    ':talla_camisa' => $tallaCamisa ?: null,
                    ':talla_pantalon' => $tallaPantalon ?: null,
                    ':talla_zapatos' => $tallaCalzado ?: null,
                    ':altura' => $estatura ?: null,
                    ':peso' => $peso ?: null,
                    ':tipo_sangre' => $tipoSangre ?: null,
                    ':tiene_hijos' => $tieneHijosMapped,
                    ':cantidad_hijos' => $cantidadHijos ?: null,
                    ':medio_transporte' => $medioTransporte ?: null,
                    ':inscrito_cne' => $inscritoCneMapped,
                    ':centro_electoral' => $centroElectoral ?: null,
                    ':cedula_vigente' => $cedulaVigente ?: null,
                    ':pasaporte_vigente' => $pasaporte ?: null,
                    ':licencia_conducir' => $licencia ?: null
                ]);
            }

            if ($result) {
                $imported++;
            } else {
                $errors++;
                echo "Fila $row: ✗ Error al procesar $cedula\n";
            }

        } catch (Exception $e) {
            $errors++;
            echo "Fila $row: ✗ Error procesando - " . $e->getMessage() . "\n";
        }
    }

    echo "\n=== IMPORTACIÓN COMPLETA FINALIZADA ===\n";
    echo "✅ Registros importados/actualizados: $imported\n";
    echo "❌ Errores: $errors\n";
    echo "⏭️  Saltados (vacíos): $skipped\n";
    echo "📊 Total procesado: " . ($imported + $errors + $skipped) . "\n";

    // Verificación final
    $stmt = $db->query("SELECT COUNT(*) as total FROM personas");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal de registros en base de datos: " . $result['total'] . "\n";

} catch (Exception $e) {
    echo "Error general: " . $e->getMessage() . "\n";
}
