-- =====================================================
-- TABLAS DE BASE DE DATOS (SIN VISTAS)
-- Sistema de Evaluación, Seguimiento y Caracterización
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

-- 6. Estados
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

-- 9. Tipos de IEU
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

-- 12. Carreras
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

-- 19. Roles
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

-- Personas
CREATE TABLE IF NOT EXISTS personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
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
    carrera_id INT DEFAULT NULL,
    anio_semestre VARCHAR(20) DEFAULT NULL,
    posee_beca TINYINT(1) DEFAULT 0,
    tipo_beca VARCHAR(100) DEFAULT NULL,
    sede_id INT DEFAULT NULL,
    universidad_id INT DEFAULT NULL,
    nivel_academico ENUM('Pregrado', 'Postgrado') DEFAULT NULL,
    pais_id INT DEFAULT 1,
    estado_id INT DEFAULT NULL,
    municipio_id INT DEFAULT NULL,
    parroquia_id INT DEFAULT NULL,
    urbanizacion VARCHAR(200) DEFAULT NULL,
    direccion_exacta TEXT DEFAULT NULL,
    comuna VARCHAR(100) DEFAULT NULL,
    estado_civil_id INT DEFAULT NULL,
    tiene_hijos TINYINT(1) DEFAULT 0,
    cantidad_hijos INT DEFAULT 0,
    carga_familiar INT DEFAULT 0,
    posee_discapacidad TINYINT(1) DEFAULT 0,
    tipo_discapacidad_id INT DEFAULT NULL,
    presenta_enfermedad TINYINT(1) DEFAULT 0,
    condicion_medica_id INT DEFAULT NULL,
    medicamentos TEXT DEFAULT NULL,
    tipo_sangre_id INT DEFAULT NULL,
    altura DECIMAL(5,2) DEFAULT NULL,
    peso DECIMAL(5,2) DEFAULT NULL,
    trabaja TINYINT(1) DEFAULT 0,
    tipo_empleo_id INT DEFAULT NULL,
    nombre_empresa VARCHAR(200) DEFAULT NULL,
    cargo VARCHAR(100) DEFAULT NULL,
    ingreso_mensual DECIMAL(12,2) DEFAULT NULL,
    medio_transporte_id INT DEFAULT NULL,
    inscrito_cne TINYINT(1) DEFAULT 0,
    centro_electoral VARCHAR(200) DEFAULT NULL,
    municipio_electoral VARCHAR(100) DEFAULT NULL,
    puesto_votacion VARCHAR(100) DEFAULT NULL,
    talla_camisa VARCHAR(10) DEFAULT NULL,
    talla_zapato VARCHAR(10) DEFAULT NULL,
    talla_pantalon VARCHAR(10) DEFAULT NULL,
    estado_registro_id INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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

-- Usuarios
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

-- Logs
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
-- DATOS INICIALES (SEEDS)
-- =====================================================

INSERT IGNORE INTO paises (id, nombre, codigo_iso) VALUES 
(1, 'Venezuela', 'VE'),
(2, 'Colombia', 'CO'),
(3, 'Ecuador', 'EC'),
(4, 'Perú', 'PE'),
(5, 'Chile', 'CL'),
(6, 'Argentina', 'AR');

INSERT IGNORE INTO sexos (id, nombre, abreviatura) VALUES 
(1, 'Masculino', 'M'),
(2, 'Femenino', 'F'),
(3, 'Otro', 'O');

INSERT IGNORE INTO estados_civiles (id, nombre) VALUES 
(1, 'Soltero'),
(2, 'Casado'),
(3, 'Divorciado'),
(4, 'Viudo'),
(5, 'Unión Libre');

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

INSERT IGNORE INTO tipos_sangre (id, nombre, factor_rh) VALUES 
(1, 'A+', 'Positivo'),
(2, 'A-', 'Negativo'),
(3, 'B+', 'Positivo'),
(4, 'B-', 'Negativo'),
(5, 'AB+', 'Positivo'),
(6, 'AB-', 'Negativo'),
(7, 'O+', 'Positivo'),
(8, 'O-', 'Negativo');

INSERT IGNORE INTO tipos_ieu (id, nombre, descripcion) VALUES 
(1, 'Pública', 'Institución de Educación Superior Pública'),
(2, 'Privada', 'Institución de Educación Superior Privada');

INSERT IGNORE INTO tipos_evaluacion (id, nombre, descripcion) VALUES 
(1, 'Académica', 'Evaluación del rendimiento académico'),
(2, 'Psicológica', 'Evaluación del estado psicológico'),
(3, 'Económica', 'Evaluación de la situación económica'),
(4, 'Social', 'Evaluación del contexto social'),
(5, 'Completa', 'Evaluación integral del estudiante');

INSERT IGNORE INTO tipos_seguimiento (id, nombre, descripcion) VALUES 
(1, 'Académico', 'Seguimiento del rendimiento académico'),
(2, 'Becario', 'Seguimiento de condiciones de beca'),
(3, 'Social', 'Seguimiento de situación social'),
(4, 'Salud', 'Seguimiento de condiciones de salud'),
(5, 'Económico', 'Seguimiento de situación económica'),
(6, 'General', 'Seguimiento general');

INSERT IGNORE INTO roles (id, nombre, descripcion, permisos) VALUES 
(1, 'Administrador', 'Acceso total al sistema', '{"all": true}'),
(2, 'Usuario', 'Acceso a operaciones básicas', '{"read": true, "create": true, "update": true}'),
(3, 'Invitado', 'Solo lectura', '{"read": true}');

INSERT IGNORE INTO estados_registro (id, nombre, color) VALUES 
(1, 'Activo', 'success'),
(2, 'Inactivo', 'secondary'),
(3, 'Pendiente', 'warning'),
(4, 'Eliminado', 'danger');

INSERT IGNORE INTO medios_transporte (id, nombre) VALUES 
(1, 'Público'),
(2, 'Privado'),
(3, 'Caminando'),
(4, 'Bicicleta'),
(5, 'Motocicleta'),
(6, 'Otro');

INSERT IGNORE INTO tipos_empleo (id, nombre) VALUES 
(1, 'Formal'),
(2, 'Informal'),
(3, 'Negocio Propio'),
(4, 'Contrato'),
(5, 'Medio Tiempo'),
(6, 'Temporal');

INSERT IGNORE INTO usuarios (id, username, password, nombre_completo, correo, rol_id) 
VALUES (1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin@localhost', 1);

SELECT 'Tablas creadas correctamente' AS mensaje;
