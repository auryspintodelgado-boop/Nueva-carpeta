-- =====================================================
-- BASE DE DATOS NORMALIZADA (SAFE VERSION)
-- Sistema de Evaluación, Seguimiento y Caracterización
-- Normalización: 3NF (Tercera Forma Normal)
-- 
-- Esta versión usa "CREATE TABLE IF NOT EXISTS" y 
-- "INSERT IGNORE" para evitar errores si las tablas
-- o datos ya existen.
-- =====================================================

USE sistema_caracterizacion_aurys;

-- =====================================================
-- TABLAS DE REFERENCIA (LOOKUP TABLES)
-- =====================================================

-- 1. Nacionalidades
CREATE TABLE IF NOT EXISTS nacionalidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    codigo_iso CHAR(2) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Sexos
CREATE TABLE IF NOT EXISTS sexos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL UNIQUE,
    abreviatura CHAR(1) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Estados Civiles
CREATE TABLE IF NOT EXISTS estados_civiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tipos de Sangre
CREATE TABLE IF NOT EXISTS tipos_sangre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(5) NOT NULL UNIQUE,
    factor_rh ENUM('Positivo', 'Negativo') NOT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Países
CREATE TABLE IF NOT EXISTS paises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    codigo_iso CHAR(3) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Estados/Provincias/Regiones
CREATE TABLE IF NOT EXISTS estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pais_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(10) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (pais_id) REFERENCES paises(id) ON DELETE RESTRICT,
    UNIQUE KEY uk_estado_pais (pais_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Municipios
CREATE TABLE IF NOT EXISTS municipios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(10) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE CASCADE,
    UNIQUE KEY uk_municipio_estado (estado_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Parroquias
CREATE TABLE IF NOT EXISTS parroquias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    municipio_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (municipio_id) REFERENCES municipios(id) ON DELETE CASCADE,
    UNIQUE KEY uk_parroquia_municipio (municipio_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Tipos de Instituciones de Educación Superior
CREATE TABLE IF NOT EXISTS tipos_ieu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Universidades
CREATE TABLE IF NOT EXISTS universidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_ieu_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    siglas VARCHAR(20) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (tipo_ieu_id) REFERENCES tipos_ieu(id) ON DELETE RESTRICT,
    UNIQUE KEY uk_universidad_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Sedes
CREATE TABLE IF NOT EXISTS sedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    universidad_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255) DEFAULT NULL,
    activa TINYINT(1) DEFAULT 1,
    FOREIGN KEY (universidad_id) REFERENCES universidades(id) ON DELETE CASCADE,
    UNIQUE KEY uk_sede_universidad (universidad_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Carreras/Programas
CREATE TABLE IF NOT EXISTS carreras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT DEFAULT NULL,
    area_conocimiento VARCHAR(100) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    UNIQUE KEY uk_carrera_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Tipos de Discapacidad
CREATE TABLE IF NOT EXISTS tipos_discapacidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Condiciones Médicas
CREATE TABLE IF NOT EXISTS condiciones_medicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL UNIQUE,
    categoria VARCHAR(100) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Tipos de Empleo
CREATE TABLE IF NOT EXISTS tipos_empleo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Medios de Transporte
CREATE TABLE IF NOT EXISTS medios_transporte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Tipos de Evaluación
CREATE TABLE IF NOT EXISTS tipos_evaluacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Tipos de Seguimiento
CREATE TABLE IF NOT EXISTS tipos_seguimiento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Roles de Usuario
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    permisos JSON DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. Estados de Registro
CREATE TABLE IF NOT EXISTS estados_registro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE,
    color VARCHAR(20) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS PRINCIPALES
-- =====================================================

-- Personas (Entidad principal)
CREATE TABLE IF NOT EXISTS personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Datos de identificación
    numero VARCHAR(20) DEFAULT NULL,
    nacionalidad_id INT DEFAULT NULL,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    primer_nombre VARCHAR(100) NOT NULL,
    segundo_nombre VARCHAR(100) DEFAULT NULL,
    primer_apellido VARCHAR(100) NOT NULL,
    segundo_apellido VARCHAR(100) DEFAULT NULL,
    sexo_id INT DEFAULT NULL,
    fecha_nacimiento DATE DEFAULT NULL,
    edad INT DEFAULT NULL,
    correo_electronico VARCHAR(255) DEFAULT NULL,
    telefono_1 VARCHAR(20) DEFAULT NULL,
    foto BLOB DEFAULT NULL,
    
    -- Datos académicos
    carrera_id INT DEFAULT NULL,
    anio_semestre VARCHAR(20) DEFAULT NULL,
    posee_beca TINYINT(1) DEFAULT 0,
    tipo_beca VARCHAR(100) DEFAULT NULL,
    sede_id INT DEFAULT NULL,
    universidad_id INT DEFAULT NULL,
    nivel_academico ENUM('Pregrado', 'Postgrado') DEFAULT NULL,
    
    -- Ubicación
    pais_id INT DEFAULT 1,
    estado_id INT DEFAULT NULL,
    municipio_id INT DEFAULT NULL,
    parroquia_id INT DEFAULT NULL,
    urbanizacion VARCHAR(200) DEFAULT NULL,
    direccion_exacta TEXT DEFAULT NULL,
    comuna VARCHAR(100) DEFAULT NULL,
    
    -- Datos familiares
    estado_civil_id INT DEFAULT NULL,
    tiene_hijos TINYINT(1) DEFAULT 0,
    cantidad_hijos INT DEFAULT 0,
    carga_familiar INT DEFAULT 0,
    
    -- Salud
    posee_discapacidad TINYINT(1) DEFAULT 0,
    tipo_discapacidad_id INT DEFAULT NULL,
    presenta_enfermedad TINYINT(1) DEFAULT 0,
    condicion_medica_id INT DEFAULT NULL,
    medicamentos TEXT DEFAULT NULL,
    tipo_sangre_id INT DEFAULT NULL,
    altura DECIMAL(5,2) DEFAULT NULL,
    peso DECIMAL(5,2) DEFAULT NULL,
    
    -- Laboral
    trabaja TINYINT(1) DEFAULT 0,
    tipo_empleo_id INT DEFAULT NULL,
    nombre_empresa VARCHAR(200) DEFAULT NULL,
    cargo VARCHAR(100) DEFAULT NULL,
    ingreso_mensual DECIMAL(12,2) DEFAULT NULL,
    medio_transporte_id INT DEFAULT NULL,
    
    -- Electoral (Venezuela)
    inscrito_cne TINYINT(1) DEFAULT 0,
    centro_electoral VARCHAR(200) DEFAULT NULL,
    municipio_electoral VARCHAR(100) DEFAULT NULL,
    puesto_votacion VARCHAR(100) DEFAULT NULL,
    
    -- Antropométrico
    talla_camisa VARCHAR(10) DEFAULT NULL,
    talla_zapato VARCHAR(10) DEFAULT NULL,
    talla_pantalon VARCHAR(10) DEFAULT NULL,
    
    -- Metadatos
    estado_registro_id INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (nacionalidad_id) REFERENCES nacionalidades(id) ON DELETE SET NULL,
    FOREIGN KEY (sexo_id) REFERENCES sexos(id) ON DELETE SET NULL,
    FOREIGN KEY (carrera_id) REFERENCES carreras(id) ON DELETE SET NULL,
    FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE SET NULL,
    FOREIGN KEY (universidad_id) REFERENCES universidades(id) ON DELETE SET NULL,
    FOREIGN KEY (pais_id) REFERENCES paises(id) ON DELETE SET NULL,
    FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE SET NULL,
    FOREIGN KEY (municipio_id) REFERENCES municipios(id) ON DELETE SET NULL,
    FOREIGN KEY (parroquia_id) REFERENCES parroquias(id) ON DELETE SET NULL,
    FOREIGN KEY (estado_civil_id) REFERENCES estados_civiles(id) ON DELETE SET NULL,
    FOREIGN KEY (tipo_discapacidad_id) REFERENCES tipos_discapacidad(id) ON DELETE SET NULL,
    FOREIGN KEY (condicion_medica_id) REFERENCES condiciones_medicas(id) ON DELETE SET NULL,
    FOREIGN KEY (tipo_sangre_id) REFERENCES tipos_sangre(id) ON DELETE SET NULL,
    FOREIGN KEY (tipo_empleo_id) REFERENCES tipos_empleo(id) ON DELETE SET NULL,
    FOREIGN KEY (medio_transporte_id) REFERENCES medios_transporte(id) ON DELETE SET NULL,
    FOREIGN KEY (estado_registro_id) REFERENCES estados_registro(id) ON DELETE SET NULL,
    
    -- Índices
    INDEX idx_cedula (cedula),
    INDEX idx_nombre_completo (primer_nombre, primer_apellido),
    INDEX idx_fecha_nacimiento (fecha_nacimiento),
    INDEX idx_carrera (carrera_id),
    INDEX idx_universidad (universidad_id),
    INDEX idx_sede (sede_id),
    INDEX idx_estado (estado_id),
    INDEX idx_municipio (municipio_id),
    INDEX idx_estado_registro (estado_registro_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Evaluaciones
CREATE TABLE IF NOT EXISTS evaluaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    tipo_evaluacion_id INT NOT NULL,
    fecha_evaluacion DATE NOT NULL,
    puntaje DECIMAL(5,2) DEFAULT NULL,
    observaciones TEXT DEFAULT NULL,
    resultado VARCHAR(50) DEFAULT NULL,
    evaluador VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE CASCADE,
    FOREIGN KEY (tipo_evaluacion_id) REFERENCES tipos_evaluacion(id) ON DELETE RESTRICT,
    
    INDEX idx_persona (persona_id),
    INDEX idx_tipo (tipo_evaluacion_id),
    INDEX idx_fecha (fecha_evaluacion),
    INDEX idx_evaluador (evaluador)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seguimientos
CREATE TABLE IF NOT EXISTS seguimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    tipo_seguimiento_id INT NOT NULL,
    fecha_seguimiento DATE NOT NULL,
    descripcion TEXT NOT NULL,
    resultado TEXT DEFAULT NULL,
    proxima_fecha DATE DEFAULT NULL,
    responsable VARCHAR(100) DEFAULT NULL,
    estado_seguimiento ENUM('Pendiente', 'En_Proceso', 'Completado', 'Cancelado') DEFAULT 'Pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE CASCADE,
    FOREIGN KEY (tipo_seguimiento_id) REFERENCES tipos_seguimiento(id) ON DELETE RESTRICT,
    
    INDEX idx_persona (persona_id),
    INDEX idx_tipo (tipo_seguimiento_id),
    INDEX idx_fecha (fecha_seguimiento),
    INDEX idx_estado (estado_seguimiento),
    INDEX idx_responsable (responsable)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuarios del Sistema
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(200) NOT NULL,
    correo VARCHAR(255) NOT NULL UNIQUE,
    rol_id INT NOT NULL,
    persona_id INT DEFAULT NULL,
    ultimo_acceso DATETIME DEFAULT NULL,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (rol_id) REFERENCES roles(id) ON DELETE RESTRICT,
    FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE SET NULL,
    
    INDEX idx_username (username),
    INDEX idx_rol (rol_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Logs de Actividad
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT DEFAULT NULL,
    accion VARCHAR(100) NOT NULL,
    tabla_afectada VARCHAR(100) DEFAULT NULL,
    registro_id INT DEFAULT NULL,
    datos_anteriores JSON DEFAULT NULL,
    datos_nuevos JSON DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    
    INDEX idx_usuario (usuario_id),
    INDEX idx_accion (accion),
    INDEX idx_tabla (tabla_afectada),
    INDEX idx_fecha (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATOS INICIALES (SEEDS) - Usar INSERT IGNORE
-- =====================================================

-- Insertar países
INSERT IGNORE INTO paises (id, nombre, codigo_iso) VALUES 
(1, 'Venezuela', 'VE'),
(2, 'Colombia', 'CO'),
(3, 'Ecuador', 'EC'),
(4, 'Perú', 'PE'),
(5, 'Chile', 'CL'),
(6, 'Argentina', 'AR');

-- Insertar sexos
INSERT IGNORE INTO sexos (id, nombre, abreviatura) VALUES 
(1, 'Masculino', 'M'),
(2, 'Femenino', 'F'),
(3, 'Otro', 'O');

-- Insertar estados civiles
INSERT IGNORE INTO estados_civiles (id, nombre) VALUES 
(1, 'Soltero'),
(2, 'Casado'),
(3, 'Divorciado'),
(4, 'Viudo'),
(5, 'Unión Libre');

-- Insertar nacionalidades
INSERT IGNORE INTO nacionalidades (id, nombre, codigo_iso) VALUES 
(1, 'Venezolano', 'VE'),
(2, 'Colombiano', 'CO'),
(3, 'Ecuatoriano', 'EC'),
(4, 'Peruano', 'PE'),
(5, 'Chileno', 'CL'),
(6, 'Argentino', 'AR'),
(7, 'Español', 'ES'),
(8, 'Portugués', 'PT'),
(9, 'Italiano', 'IT'),
(10, 'Otro', 'OT');

-- Insertar tipos de sangre
INSERT IGNORE INTO tipos_sangre (id, nombre, factor_rh) VALUES 
(1, 'A+', 'Positivo'),
(2, 'A-', 'Negativo'),
(3, 'B+', 'Positivo'),
(4, 'B-', 'Negativo'),
(5, 'AB+', 'Positivo'),
(6, 'AB-', 'Negativo'),
(7, 'O+', 'Positivo'),
(8, 'O-', 'Negativo');

-- Insertar tipos de IEU
INSERT IGNORE INTO tipos_ieu (id, nombre, descripcion) VALUES 
(1, 'Pública', 'Institución de Educación Superior Pública'),
(2, 'Privada', 'Institución de Educación Superior Privada');

-- Insertar tipos de evaluación
INSERT IGNORE INTO tipos_evaluacion (id, nombre, descripcion) VALUES 
(1, 'Académica', 'Evaluación del rendimiento académico'),
(2, 'Psicológica', 'Evaluación del estado psicológico'),
(3, 'Económica', 'Evaluación de la situación económica'),
(4, 'Social', 'Evaluación del contexto social'),
(5, 'Completa', 'Evaluación integral del estudiante');

-- Insertar tipos de seguimiento
INSERT IGNORE INTO tipos_seguimiento (id, nombre, descripcion) VALUES 
(1, 'Académico', 'Seguimiento del rendimiento académico'),
(2, 'Becario', 'Seguimiento de condiciones de beca'),
(3, 'Social', 'Seguimiento de situación social'),
(4, 'Salud', 'Seguimiento de condiciones de salud'),
(5, 'Económico', 'Seguimiento de situación económica'),
(6, 'General', 'Seguimiento general');

-- Insertar roles
INSERT IGNORE INTO roles (id, nombre, descripcion, permisos) VALUES 
(1, 'Administrador', 'Acceso total al sistema', '{"all": true}'),
(2, 'Usuario', 'Acceso a operaciones básicas', '{"read": true, "create": true, "update": true}'),
(3, 'Invitado', 'Solo lectura', '{"read": true}');

-- Insertar estados de registro
INSERT IGNORE INTO estados_registro (id, nombre, color) VALUES 
(1, 'Activo', 'success'),
(2, 'Inactivo', 'secondary'),
(3, 'Pendiente', 'warning'),
(4, 'Eliminado', 'danger');

-- Insertar medios de transporte
INSERT IGNORE INTO medios_transporte (id, nombre) VALUES 
(1, 'Público'),
(2, 'Privado'),
(3, 'Caminando'),
(4, 'Bicicleta'),
(5, 'Motocicleta'),
(6, 'Otro');

-- Insertar tipos de empleo
INSERT IGNORE INTO tipos_empleo (id, nombre) VALUES 
(1, 'Formal'),
(2, 'Informal'),
(3, 'Negocio Propio'),
(4, 'Contrato'),
(5, 'Medio Tiempo'),
(6, 'Temporal');

-- Insertar usuario administrador por defecto (solo si no existe)
INSERT IGNORE INTO usuarios (id, username, password, nombre_completo, correo, rol_id) 
VALUES (1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin@localhost', 1);

-- =====================================================
-- VISTAS PARA CONSULTAS COMUNES
-- =====================================================

-- Eliminar vistas existentes antes de recrear
DROP VIEW IF EXISTS v_personas_completa;
DROP VIEW IF EXISTS v_seguimientos_activos;
DROP VIEW IF EXISTS v_evaluaciones_recientes;

-- Vista de personas con información relacionada
CREATE VIEW v_personas_completa AS
SELECT 
    p.id,
    p.numero,
    p.cedula,
    CONCAT(p.primer_nombre, ' ', COALESCE(p.segundo_nombre, ''), ' ', p.primer_apellido, ' ', COALESCE(p.segundo_apellido, '')) AS nombre_completo,
    p.primer_nombre,
    p.segundo_nombre,
    p.primer_apellido,
    p.segundo_apellido,
    p.fecha_nacimiento,
    p.edad,
    p.correo_electronico,
    p.telefono_1,
    p.posee_beca,
    p.anio_semestre,
    p.trabaja,
    p.inscrito_cne,
    p.estado_registro_id,
    er.nombre AS estado_registro,
    n.nombre AS nacionalidad,
    s.nombre AS sexo,
    c.nombre AS carrera,
    u.nombre AS universidad,
    u.siglas AS siglas_universidad,
    tie.nombre AS tipo_ieu,
    sed.nombre AS sede,
    pa.nombre AS pais,
    e.nombre AS estado_residencia,
    m.nombre AS municipio,
    pq.nombre AS parroquia,
    ec.nombre AS estado_civil,
    ts.nombre AS tipo_sangre,
    td.nombre AS tipo_discapacidad,
    cm.nombre AS condicion_medica,
    te.nombre AS tipo_empleo,
    mt.nombre AS medio_transporte,
    p.created_at,
    p.updated_at
FROM personas p
LEFT JOIN nacionalidades n ON p.nacionalidad_id = n.id
LEFT JOIN sexos s ON p.sexo_id = s.id
LEFT JOIN carreras c ON p.carrera_id = c.id
LEFT JOIN universidades u ON p.universidad_id = u.id
LEFT JOIN tipos_ieu tie ON u.tipo_ieu_id = tie.id
LEFT JOIN sedes sed ON p.sede_id = sed.id
LEFT JOIN paises pa ON p.pais_id = pa.id
LEFT JOIN estados e ON p.estado_id = e.id
LEFT JOIN municipios m ON p.municipio_id = m.id
LEFT JOIN parroquias pq ON p.parroquia_id = pq.id
LEFT JOIN estados_civiles ec ON p.estado_civil_id = ec.id
LEFT JOIN tipos_sangre ts ON p.tipo_sangre_id = ts.id
LEFT JOIN tipos_discapacidad td ON p.tipo_discapacidad_id = td.id
LEFT JOIN condiciones_medicas cm ON p.condicion_medica_id = cm.id
LEFT JOIN tipos_empleo te ON p.tipo_empleo_id = te.id
LEFT JOIN medios_transporte mt ON p.medio_transporte_id = mt.id
LEFT JOIN estados_registro er ON p.estado_registro_id = er.id;

-- Vista de seguimientos activos
CREATE VIEW v_seguimientos_activos AS
SELECT 
    seg.id,
    seg.persona_id,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS nombre_persona,
    p.cedula,
    ts.nombre AS tipo_seguimiento,
    seg.fecha_seguimiento,
    seg.descripcion,
    seg.resultado,
    seg.proxima_fecha,
    seg.responsable,
    seg.estado_seguimiento,
    seg.created_at
FROM seguimientos seg
JOIN personas p ON seg.persona_id = p.id
JOIN tipos_seguimiento ts ON seg.tipo_seguimiento_id = ts.id
WHERE seg.estado_seguimiento IN ('Pendiente', 'En_Proceso');

-- Vista de evaluaciones recientes
CREATE VIEW v_evaluaciones_recientes AS
SELECT 
    ev.id,
    ev.persona_id,
    CONCAT(p.primer_nombre, ' ', p.primer_apellido) AS nombre_persona,
    p.cedula,
    te.nombre AS tipo_evaluacion,
    ev.fecha_evaluacion,
    ev.puntaje,
    ev.resultado,
    ev.evaluador,
    ev.created_at
FROM evaluaciones ev
JOIN personas p ON ev.persona_id = p.id
JOIN tipos_evaluacion te ON ev.tipo_evaluacion_id = te.id
ORDER BY ev.fecha_evaluacion DESC;

-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS ÚTILES
-- =====================================================

DELIMITER //

-- Procedimiento para obtener estadísticas de personas
DROP PROCEDURE IF EXISTS sp_estadisticas_personas//
CREATE PROCEDURE sp_estadisticas_personas()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM personas) AS total_personas,
        (SELECT COUNT(*) FROM personas WHERE posee_beca = 1) AS total_con_beca,
        (SELECT COUNT(*) FROM personas WHERE trabaja = 1) AS total_trabajan,
        (SELECT COUNT(*) FROM personas WHERE inscrito_cne = 1) AS total_inscritos_cne,
        (SELECT COUNT(*) FROM personas WHERE posee_discapacidad = 1) AS total_discapacidad,
        (SELECT AVG(edad) FROM personas WHERE edad > 0) AS promedio_edad;
END//

-- Procedimiento para buscar personas
DROP PROCEDURE IF EXISTS sp_buscar_personas//
CREATE PROCEDURE sp_buscar_personas(IN buscar VARCHAR(100))
BEGIN
    SELECT * FROM personas 
    WHERE primer_nombre LIKE CONCAT('%', buscar, '%')
       OR segundo_nombre LIKE CONCAT('%', buscar, '%')
       OR primer_apellido LIKE CONCAT('%', buscar, '%')
       OR segundo_apellido LIKE CONCAT('%', buscar, '%')
       OR cedula LIKE CONCAT('%', buscar, '%')
    ORDER BY primer_nombre, primer_apellido;
END//

DELIMITER ;

-- =====================================================
-- MENSAJE DE ÉXITO
-- =====================================================

SELECT 'Base de datos configurada correctamente' AS mensaje;
