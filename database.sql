-- =====================================================
-- AURYS - Sistema de Gestión de Recursos Humanos
-- Database Schema
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS aurys_hr DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aurys_hr;

-- =====================================================
-- TABLE: users
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'director', 'employee') NOT NULL DEFAULT 'employee',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: departments
-- =====================================================
CREATE TABLE IF NOT EXISTS departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    director_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_director (director_id),
    FOREIGN KEY (director_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: employees
-- =====================================================
CREATE TABLE IF NOT EXISTS employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    department_id INT,
    -- Identificación
    numero INT,
    nacionalidad VARCHAR(50),
    cedula VARCHAR(20) UNIQUE,
    primer_nombre VARCHAR(50),
    segundo_nombre VARCHAR(50),
    primer_apellido VARCHAR(50),
    segundo_apellido VARCHAR(50),
    sexo ENUM('M', 'F'),
    fecha_nacimiento DATE,
    edad INT,
    telefono1 VARCHAR(20),
    correo_electronico VARCHAR(100),
    -- Educación
    carrera VARCHAR(100),
    ano_semestre VARCHAR(20),
    posee_beca ENUM('SI', 'NO'),
    sede VARCHAR(100),
    estado VARCHAR(50),
    siglas_universidad VARCHAR(20),
    tipo_ieu ENUM('PUBLICA', 'PRIVADA'),
    pregrado_postgrado ENUM('PREGRADO', 'POSTGRADO'),
    -- Dirección
    urbanismo VARCHAR(100),
    municipio VARCHAR(50),
    parroquia VARCHAR(50),
    -- Familia
    tiene_hijos ENUM('SI', 'NO'),
    cantidad_hijos INT,
    carga_familiar INT,
    estado_civil ENUM('CASADO', 'SOLTERO'),
    -- Discapacidad
    posee_discapacidad ENUM('SI', 'NO'),
    describe_discapacidad TEXT,
    -- Salud
    presenta_enfermedad ENUM('SI', 'NO'),
    condicion_medica TEXT,
    medicamentos TEXT,
    -- Empleo
    trabaja ENUM('SI', 'NO'),
    tipo_empleo VARCHAR(50),
    medio_transporte VARCHAR(50),
    -- CNE
    inscrito_cne ENUM('SI', 'NO'),
    centro_electoral VARCHAR(100),
    comuna VARCHAR(50),
    -- Medidas
    talla_camisa VARCHAR(10),
    talla_zapatos VARCHAR(10),
    talla_pantalon VARCHAR(10),
    altura DECIMAL(4,2),
    peso DECIMAL(5,2),
    tipo_sangre VARCHAR(5),
    -- Fotos
    foto_perfil VARCHAR(255),
    -- Estado de verificación
    characterization_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    verified_by INT,
    verified_at TIMESTAMP NULL,
    rejection_reason TEXT,
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_department (department_id),
    INDEX idx_cedula (cedula),
    INDEX idx_status (characterization_status),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: evaluations
-- =====================================================
CREATE TABLE IF NOT EXISTS evaluations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    evaluator_id INT NOT NULL,
    evaluation_date DATE NOT NULL,
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_employee (employee_id),
    INDEX idx_evaluator (evaluator_id),
    INDEX idx_date (evaluation_date),
    INDEX idx_period (mes, ano),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (evaluator_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Insert default admin user (password: admin123)
-- =====================================================
INSERT INTO users (username, password, role, status) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- =====================================================
-- Sample departments
-- =====================================================
INSERT INTO departments (name, description) VALUES
('Guías', 'Departamento de guías turísticos'),
('Administración', 'Departamento de administración general'),
('Recursos Humanos', 'Departamento de gestión de personal'),
('Mantenimiento', 'Departamento de mantenimiento de instalaciones');
