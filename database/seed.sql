-- =====================================================
-- DATOS INICIALES (SEED)
-- Sistema de Evaluación, Seguimiento y Caracterización
-- =====================================================

USE sistema_caracterizacion_aurys;

-- =====================================================
-- ROLES
-- =====================================================
INSERT INTO roles (nombre, descripcion, permisos, activo) VALUES
('Administrador', 'Acceso total al sistema', '{"all": true}', 1),
('Usuario', 'Usuario regular con acceso básico', '{"read": true, "create": true}', 1),
('Invitado', 'Solo acceso de lectura', '{"read": true}', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- =====================================================
-- TIPOS DE EVALUACIÓN
-- =====================================================
INSERT INTO tipos_evaluacion (nombre, descripcion, activo) VALUES
('Académica', 'Evaluación del rendimiento académico', 1),
('Psicológica', 'Evaluación del estado psicológico', 1),
('Social', 'Evaluación del contexto social', 1),
('Económica', 'Evaluación de la situación económica', 1),
('Laboral', 'Evaluación de la situación laboral', 1),
('Médica', 'Evaluación del estado de salud', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- =====================================================
-- TIPOS DE SEGUIMIENTO
-- =====================================================
INSERT INTO tipos_seguimiento (nombre, descripcion, activo) VALUES
('Académico', 'Seguimiento del rendimiento académico', 1),
('Psicológico', 'Seguimiento del estado psicológico', 1),
('Social', 'Seguimiento del contexto social', 1),
('Económico', 'Seguimiento de la situación económica', 1),
('Laboral', 'Seguimiento de la situación laboral', 1),
('Médico', 'Seguimiento del estado de salud', 1),
('General', 'Seguimiento general', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- =====================================================
-- NACIONALIDADES
-- =====================================================
INSERT INTO nacionalidades (nombre, codigo_iso, activo) VALUES
('Venezolana', 'VE', 1),
('Colombiana', 'CO', 1),
('Peruana', 'PE', 1),
('Ecuatoriana', 'EC', 1),
('Chilena', 'CL', 1),
('Argentina', 'AR', 1),
('Brasileña', 'BR', 1),
('Otra', 'OT', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- =====================================================
-- SEXOS
-- =====================================================
INSERT INTO sexos (nombre, abreviatura, activo) VALUES
('Masculino', 'M', 1),
('Femenino', 'F', 1),
('Otro', 'O', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- =====================================================
-- ESTADOS CIVILES
-- =====================================================
INSERT INTO estados_civiles (nombre, activo) VALUES
('Soltero/a', 1),
('Casado/a', 1),
('Divorciado/a', 1),
('Viudo/a', 1),
('Concubino/a', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- =====================================================
-- TIPOS DE SANGRE
-- =====================================================
INSERT INTO tipos_sangre (nombre, factor_rh, activo) VALUES
('A', 'Positivo', 1),
('A', 'Negativo', 1),
('B', 'Positivo', 1),
('B', 'Negativo', 1),
('AB', 'Positivo', 1),
('AB', 'Negativo', 1),
('O', 'Positivo', 1),
('O', 'Negativo', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- =====================================================
-- USUARIO ADMINISTRADOR POR DEFECTO
-- =====================================================
-- Username: admin
-- Password: admin123
INSERT INTO usuarios (username, password, nombre_completo, correo, rol_id, estado) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador del Sistema', 'admin@aurys.com', 1, 'Activo')
ON DUPLICATE KEY UPDATE username = username;

-- =====================================================
-- PAÍSES, ESTADOS, MUNICIPIOS, PARROQUIAS (VENEZUELA)
-- =====================================================
INSERT INTO paises (nombre, codigo_iso, activo) VALUES
('Venezuela', 'VE', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- Estados de Venezuela
INSERT INTO estados (pais_id, nombre, codigo, activo) VALUES
(1, 'Distrito Capital', 'DC', 1),
(1, 'Miranda', 'MI', 1),
(1, 'Carabobo', 'CA', 1),
(1, 'Lara', 'LA', 1),
(1, 'Táchira', 'TA', 1),
(1, 'Zulia', 'ZU', 1),
(1, 'Aragua', 'AR', 1),
(1, 'Bolívar', 'BO', 1)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);
