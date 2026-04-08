-- =====================================================
-- BASE DE DATOS NORMALIZADA
-- Sistema de Evaluación, Seguimiento y Caracterización
-- Normalización: 3NF (Tercera Forma Normal)
-- =====================================================

-- Crear base de datos

    
USE sistema_caracterizacion_aurys;

-- =====================================================
-- TABLAS DE REFERENCIA (LOOKUP TABLES)
-- =====================================================

-- 1. Nacionalidades
CREATE TABLE nacionalidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    codigo_iso CHAR(2) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Sexos
CREATE TABLE sexos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL UNIQUE,
    abreviatura CHAR(1) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Estados Civiles
CREATE TABLE estados_civiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tipos de Sangre
CREATE TABLE tipos_sangre (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(5) NOT NULL UNIQUE,
    factor_rh ENUM('Positivo', 'Negativo') NOT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Países
CREATE TABLE paises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    codigo_iso CHAR(3) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Estados/Provincias/Regiones
CREATE TABLE estados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pais_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(10) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (pais_id) REFERENCES paises(id) ON DELETE RESTRICT,
    UNIQUE KEY uk_estado_pais (pais_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Municipios
CREATE TABLE municipios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estado_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    codigo VARCHAR(10) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (estado_id) REFERENCES estados(id) ON DELETE CASCADE,
    UNIQUE KEY uk_municipio_estado (estado_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Parroquias
CREATE TABLE parroquias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    municipio_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (municipio_id) REFERENCES municipios(id) ON DELETE CASCADE,
    UNIQUE KEY uk_parroquia_municipio (municipio_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Tipos de Instituciones de Educación Superior
CREATE TABLE tipos_ieu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Universidades
CREATE TABLE universidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_ieu_id INT NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    siglas VARCHAR(20) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (tipo_ieu_id) REFERENCES tipos_ieu(id) ON DELETE RESTRICT,
    UNIQUE KEY uk_universidad_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Sedes
CREATE TABLE sedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    universidad_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255) DEFAULT NULL,
    activa TINYINT(1) DEFAULT 1,
    FOREIGN KEY (universidad_id) REFERENCES universidades(id) ON DELETE CASCADE,
    UNIQUE KEY uk_sede_universidad (universidad_id, nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Carreras/Programas
CREATE TABLE carreras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT DEFAULT NULL,
    area_conocimiento VARCHAR(100) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1,
    UNIQUE KEY uk_carrera_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Tipos de Discapacidad
CREATE TABLE tipos_discapacidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Condiciones Médicas
CREATE TABLE condiciones_medicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL UNIQUE,
    categoria VARCHAR(100) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Tipos de Empleo
CREATE TABLE tipos_empleo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Medios de Transporte
CREATE TABLE medios_transporte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Tipos de Evaluación
CREATE TABLE tipos_evaluacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Tipos de Seguimiento
CREATE TABLE tipos_seguimiento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Roles de Usuario
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    permisos JSON DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. Estados de Registro
CREATE TABLE estados_registro (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL UNIQUE,
    color VARCHAR(20) DEFAULT NULL,
    activo TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLAS PRINCIPALES
-- =====================================================

-- Personas (Entidad principal)
CREATE TABLE personas (
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
CREATE TABLE evaluaciones (
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
CREATE TABLE seguimientos (
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
CREATE TABLE usuarios (
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
CREATE TABLE logs (
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
-- DATOS INICIALES (SEEDS)
-- =====================================================

-- Insertar países
INSERT INTO paises (nombre, codigo_iso) VALUES 
('Venezuela', 'VE'),
('Colombia', 'CO'),
('Ecuador', 'EC'),
('Perú', 'PE'),
('Chile', 'CL'),
('Argentina', 'AR');

-- Insertar estados de Venezuela
INSERT INTO estados (pais_id, nombre, codigo) VALUES 
(1, 'Distrito Capital', 'DC'),
(1, 'Miranda', 'MIR'),
(1, 'Carabobo', 'CAR'),
(1, 'Lara', 'LAR'),
(1, 'Táchira', 'TAC'),
(1, 'Zulia', 'ZUL'),
(1, 'Mérida', 'MER'),
(1, 'Barinas', 'BAR'),
(1, 'Portuguesa', 'POR'),
(1, 'Cojedes', 'COJ'),
(1, 'Aragua', 'ARA'),
(1, 'Guárico', 'GUA'),
(1, 'Anzoátegui', 'ANZ'),
(1, 'Sucre', 'SUC'),
(1, 'Monagas', 'MON'),
(1, 'Delta Amacuro', 'DAM'),
(1, 'Bolívar', 'BOL'),
(1, 'Apure', 'APU'),
(1, 'Amazonas', 'AMA'),
(1, 'Falcón', 'FAL'),
(1, 'Yaracuy', 'YAR'),
(1, 'Vargas', 'VAR');

-- Insertar sexos
INSERT INTO sexos (nombre, abreviatura) VALUES 
('Masculino', 'M'),
('Femenino', 'F'),
('Otro', 'O');

-- Insertar estados civiles
INSERT INTO estados_civiles (nombre) VALUES 
('Soltero'),
('Casado'),
('Divorciado'),
('Viudo'),
('Unión Libre');

-- Insertar nacionalidades
INSERT INTO nacionalidades (nombre, codigo_iso) VALUES 
('Venezolano', 'VE'),
('Colombiano', 'CO'),
('Ecuatoriano', 'EC'),
('Peruano', 'PE'),
('Chileno', 'CL'),
('Argentino', 'AR'),
('Español', 'ES'),
('Portugués', 'PT'),
('Italiano', 'IT'),
('Otro', 'OT');

-- Insertar tipos de sangre
INSERT INTO tipos_sangre (nombre, factor_rh) VALUES 
('A+', 'Positivo'),
('A-', 'Negativo'),
('B+', 'Positivo'),
('B-', 'Negativo'),
('AB+', 'Positivo'),
('AB-', 'Negativo'),
('O+', 'Positivo'),
('O-', 'Negativo');

-- Insertar tipos de IEU
INSERT INTO tipos_ieu (nombre, descripcion) VALUES 
('Pública', 'Institución de Educación Superior Pública'),
('Privada', 'Institución de Educación Superior Privada');

-- Insertar tipos de evaluación
INSERT INTO tipos_evaluacion (nombre, descripcion) VALUES 
('Académica', 'Evaluación del rendimiento académico'),
('Psicológica', 'Evaluación del estado psicológico'),
('Económica', 'Evaluación de la situación económica'),
('Social', 'Evaluación del contexto social'),
('Completa', 'Evaluación integral del estudiante');

-- Insertar tipos de seguimiento
INSERT INTO tipos_seguimiento (nombre, descripcion) VALUES 
('Académico', 'Seguimiento del rendimiento académico'),
('Becario', 'Seguimiento de condiciones de beca'),
('Social', 'Seguimiento de situación social'),
('Salud', 'Seguimiento de condiciones de salud'),
('Económico', 'Seguimiento de situación económica'),
('General', 'Seguimiento general');

-- Insertar roles
INSERT INTO roles (nombre, descripcion, permisos) VALUES 
('Administrador', 'Acceso total al sistema', '{"all": true}'),
('Usuario', 'Acceso a operaciones básicas', '{"read": true, "create": true, "update": true}'),
('Invitado', 'Solo lectura', '{"read": true}');

-- Insertar estados de registro
INSERT INTO estados_registro (nombre, color) VALUES 
('Activo', 'success'),
('Inactivo', 'secondary'),
('Pendiente', 'warning'),
('Eliminado', 'danger');

-- Insertar medios de transporte
INSERT INTO medios_transporte (nombre) VALUES 
('Público'),
('Privado'),
('Caminando'),
('Bicicleta'),
('Motocicleta'),
('Otro');

-- Insertar tipos de empleo
INSERT INTO tipos_empleo (nombre) VALUES 
('Formal'),
('Informal'),
('Negocio Propio'),
('Contrato'),
('Medio Tiempo'),
('Temporal');

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (username, password, nombre_completo, correo, rol_id) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin@localhost', 1);

-- =====================================================
-- VISTAS PARA CONSULTAS COMUNES
-- =====================================================

-- Vista de personas con información relacionada
CREATE OR REPLACE VIEW v_personas_completa AS
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
CREATE OR REPLACE VIEW v_seguimientos_activos AS
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
WHERE seg.estado_seguimiento != 'Cancelado';

-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS ÚTILES
-- =====================================================

DELIMITER //

-- Procedimiento para obtener estadísticas de caracterización
CREATE PROCEDURE sp_estadisticas_caracterizacion()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM personas) AS total_personas,
        (SELECT COUNT(*) FROM personas WHERE estado_registro_id = 1) AS activos,
        (SELECT COUNT(*) FROM personas WHERE posee_beca = 1) AS con_beca,
        (SELECT COUNT(*) FROM personas WHERE trabaja = 1) AS trabajadores,
        (SELECT COUNT(*) FROM personas WHERE posee_discapacidad = 1) AS con_discapacidad,
        (SELECT COUNT(*) FROM personas WHERE presenta_enfermedad = 1) AS con_enfermedad,
        (SELECT COUNT(*) FROM personas WHERE inscribed_cne = 1) AS inscritos_cne,
        (SELECT AVG(edad) FROM personas WHERE edad > 0) AS promedio_edad;
END //

-- Procedimiento para buscar personas
CREATE PROCEDURE sp_buscar_personas(IN busqueda VARCHAR(100))
BEGIN
    SELECT * FROM v_personas_completa 
    WHERE 
        cedula LIKE CONCAT('%', busqueda, '%')
        OR primer_nombre LIKE CONCAT('%', busqueda, '%')
        OR segundo_nombre LIKE CONCAT('%', busqueda, '%')
        OR primer_apellido LIKE CONCAT('%', busqueda, '%')
        OR segundo_apellido LIKE CONCAT('%', busqueda, '%')
        OR correo_electronico LIKE CONCAT('%', busqueda, '%');
END //

DELIMITER ;
