-- =====================================================
-- AURYS - Sistema de Gestión de Recursos Humanos
-- Database Schema (Compatible con Migraciones CI4)
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS aurys_hr DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aurys_hr;

-- =====================================================
-- TABLE: usuarios
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100),
    rol ENUM('ADMIN', 'EVALUADOR', 'DIRECTOR', 'CONSULTA') DEFAULT 'CONSULTA',
    departamento_id INT UNSIGNED,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    ultimo_login DATETIME,
    two_factor_enabled ENUM('S', 'N') DEFAULT 'N',
    two_factor_code VARCHAR(6),
    two_factor_expires DATETIME,
    reset_token VARCHAR(64),
    reset_expires DATETIME,
    password_changed_at DATETIME,
    failed_login_attempts INT DEFAULT 0,
    locked_until DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_rol (rol),
    INDEX idx_estado (estado),
    INDEX idx_departamento (departamento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: departamentos
-- =====================================================
CREATE TABLE IF NOT EXISTS departamentos (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    director_id INT UNSIGNED,
    estado ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre),
    INDEX idx_director (director_id),
    INDEX idx_estado (estado),
    FOREIGN KEY (director_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: personas
-- =====================================================
CREATE TABLE IF NOT EXISTS personas (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    numero INT,
    nacionalidad VARCHAR(50),
    cedula VARCHAR(20) UNIQUE,
    primer_nombre VARCHAR(50) NOT NULL,
    segundo_nombre VARCHAR(50),
    primer_apellido VARCHAR(50) NOT NULL,
    segundo_apellido VARCHAR(50),
    sexo ENUM('M', 'F'),
    fecha_nacimiento DATE,
    edad INT,
    telefono1 VARCHAR(20),
    telefono2 VARCHAR(20),
    correo_electronico VARCHAR(100),
    carrera VARCHAR(100),
    ano_semestre VARCHAR(20),
    posee_beca ENUM('SI', 'NO') DEFAULT 'NO',
    sede VARCHAR(100),
    estado VARCHAR(50),
    siglas_universidad VARCHAR(20),
    tipo_ieu ENUM('PUBLICA', 'PRIVADA'),
    nivel_academico VARCHAR(50),
    urbanismo VARCHAR(100),
    municipio VARCHAR(50),
    parroquia VARCHAR(50),
    tiene_hijos ENUM('SI', 'NO') DEFAULT 'NO',
    cantidad_hijos INT DEFAULT 0,
    posee_discapacidad ENUM('SI', 'NO') DEFAULT 'NO',
    descripcion_discapacidad TEXT,
    presenta_enfermedad ENUM('SI', 'NO') DEFAULT 'NO',
    condicion_medica TEXT,
    medicamentos TEXT,
    trabaja ENUM('SI', 'NO') DEFAULT 'NO',
    tipo_empleo VARCHAR(50),
    medio_transporte VARCHAR(50),
    inscrito_cne ENUM('SI', 'NO') DEFAULT 'NO',
    centro_electoral VARCHAR(100),
    comuna VARCHAR(50),
    estado_civil VARCHAR(20),
    talla_camisa VARCHAR(10),
    talla_zapatos VARCHAR(10),
    talla_pantalon VARCHAR(10),
    altura DECIMAL(4,2),
    peso DECIMAL(5,2),
    tipo_sangre VARCHAR(5),
    carga_familiar INT DEFAULT 0,
    fotos JSON,
    foto VARCHAR(255),
    observaciones TEXT,
    departamento_id INT UNSIGNED,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado_registro ENUM('ACTIVO', 'INACTIVO') DEFAULT 'ACTIVO',
    usuario_registro INT UNSIGNED,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_cedula (cedula),
    INDEX idx_departamento (departamento_id),
    INDEX idx_estado (estado_registro),
    INDEX idx_nombre (primer_nombre, primer_apellido),
    FOREIGN KEY (departamento_id) REFERENCES departamentos(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: evaluaciones
-- =====================================================
CREATE TABLE IF NOT EXISTS evaluaciones (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    persona_id INT UNSIGNED NOT NULL,
    evaluador_id INT UNSIGNED NOT NULL,
    fecha_evaluacion DATE NOT NULL,
    mes VARCHAR(20) NOT NULL,
    ano YEAR NOT NULL,
    -- Área 1: Orientación de Resultados
    orientation_cumple_tareas TINYINT DEFAULT 3,
    orientation_volumen_adecuado TINYINT DEFAULT 3,
    orientation_comentarios TEXT,
    -- Área 2: Calidad y Organización
    quality_no_errores TINYINT DEFAULT 3,
    quality_recursos TINYINT DEFAULT 3,
    quality_supervision TINYINT DEFAULT 3,
    quality_profesional TINYINT DEFAULT 3,
    quality_respetuoso TINYINT DEFAULT 3,
    quality_planifica TINYINT DEFAULT 3,
    quality_indicadores TINYINT DEFAULT 3,
    quality_metas TINYINT DEFAULT 3,
    quality_comentarios TEXT,
    -- Área 3: Relaciones Interpersonales y Trabajo en Equipo
    teamwork_cortes TINYINT DEFAULT 3,
    teamwork_orientacion TINYINT DEFAULT 3,
    teamwork_conflictos TINYINT DEFAULT 3,
    teamwork_integracion TINYINT DEFAULT 3,
    teamwork_objetivos TINYINT DEFAULT 3,
    teamwork_comentarios TEXT,
    -- Área 4: Iniciativa
    initiative_ideas TINYINT DEFAULT 3,
    initiative_cambio TINYINT DEFAULT 3,
    initiative_dificultades TINYINT DEFAULT 3,
    initiative_resolver TINYINT DEFAULT 3,
    initiative_comentarios TEXT,
    -- Puntuación total
    total_score INT DEFAULT 0,
    -- Comentarios generales
    general_comments TEXT,
    -- Firmas
    employee_signature TINYINT DEFAULT 0,
    evaluator_signature TINYINT DEFAULT 0,
    -- Timestamps
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_persona (persona_id),
    INDEX idx_evaluador (evaluador_id),
    INDEX idx_fecha (fecha_evaluacion),
    INDEX idx_periodo (mes, ano),
    FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE CASCADE,
    FOREIGN KEY (evaluador_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: seguimientos
-- =====================================================
CREATE TABLE IF NOT EXISTS seguimientos (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    persona_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATE,
    fecha_fin DATE,
    prioridad ENUM('BAJA', 'MEDIA', 'ALTA') DEFAULT 'MEDIA',
    estado ENUM('PENDIENTE', 'EN_PROCESO', 'COMPLETADO', 'CANCELADO') DEFAULT 'PENDIENTE',
    recordatorio_fecha DATE,
    recordatorio_enviado TINYINT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_persona (persona_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha_inicio (fecha_inicio),
    INDEX idx_fecha_fin (fecha_fin),
    FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Insert default admin user
-- Password: Admin123! (debe cambiarse en primer login)
-- =====================================================
INSERT INTO usuarios (username, email, password, nombre_completo, rol, estado) 
VALUES ('admin', 'admin@aurys.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador del Sistema', 'ADMIN', 'ACTIVO');

-- =====================================================
-- Sample departments
-- =====================================================
INSERT INTO departamentos (nombre, descripcion) VALUES
('Guías', 'Departamento de guías turísticos'),
('Administración', 'Departamento de administración general'),
('Recursos Humanos', 'Departamento de gestión de personal'),
('Mantenimiento', 'Departamento de mantenimiento de instalaciones');
