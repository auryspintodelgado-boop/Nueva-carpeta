-- Agregar columna updated_at a la tabla usuarios
ALTER TABLE usuarios ADD COLUMN updated_at DATETIME NULL;

-- También verificar y agregar created_at si no existe
-- ALTER TABLE usuarios ADD COLUMN created_at DATETIME NULL;