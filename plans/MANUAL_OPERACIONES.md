# Manual de Operaciones - Sistema de Caracterización AuryS

## 1. Introducción

El Sistema de Caracterización y Seguimiento AuryS es una aplicación web desarrollada en CodeIgniter 4 que permite gestionar la caracterización de personas, realizar evaluaciones periódicas y hacer seguimiento de cada individuo registrado.

### Propósito
- Registrar personas con 45 campos de caracterización
- Realizar evaluaciones mensuales del personal
- Determinar el empleado del mes basado en puntuación
- Seguimiento de casos y pendientes

---

## 2. Roles del Sistema

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **ADMIN** | Administrador | Acceso total a todas las funciones |
| **EVALUADOR** | Evaluador | Puede ver todas las personas y evaluar |
| **DIRECTOR** | Director de Departamento | Solo ve personas de su departamento |
| **CONSULTA** | Consulta | Solo lectura |

---

## 3. Módulos del Sistema

### 3.1 Personas (Caracterización)

**Objetivo:** Registrar y gestionar los datos completos de cada persona.

**Acceso:** `/personas`

**Campos de caracterización (45 campos):**

| Categoría | Campos |
|-----------|--------|
| **Datos Personales** | Número, Nacionalidad, Cédula, Primer Nombre, Segundo Nombre, Primer Apellido, Segundo Apellido, Sexo, Fecha de Nacimiento, Edad, Teléfono 1, Teléfono 2, Correo Electrónico |
| **Datos Académicos** | Universidad, Tipo IEU, Nivel Académico, Carrera, Año/Semestre, Sede, Beca |
| **Ubicación** | Urbanismo, Municipio, Parroquia, Comuna |
| **Familia** | Estado Civil, Hijos (Sí/No), Cantidad Hijos, Carga Familiar |
| **Salud** | Discapacidad, Descripción Discapacidad, Presenta Enfermedad, Condición Médica, Medicamentos |
| **Laborales** | Trabaja (Sí/No), Tipo Empleo, Medio de Transporte |
| **Electorales** | Inscrito CNE, Centro Electoral |
| **Físicos** | Talla Camisa, Talla Zapatos, Talla Pantalón, Altura, Peso, Tipo Sangre |
| **Departamento** | Asignación de departamento |

**Operaciones:**
- Crear nueva persona
- Buscar por nombre, apellido o cédula
- Ver detalle completo
- Editar información
- Eliminar (soft delete)

### 3.2 Evaluaciones

**Objetivo:** Registrar evaluaciones periódicas del personal.

**Acceso:** `/evaluaciones`

**Tipos de Evaluación:**
- Académica
- Psicológica
- Social
- Económica
- Mensual
- Seguimiento
- Otra

**Evaluación Mensual (para Empleado del Mes):**
| Campo | Ponderación |
|-------|--------------|
| Asistencia | 25% |
| Puntualidad | 20% |
| Trabajo en Equipo | 25% |
| Iniciativa | 30% |

**Operaciones:**
- Crear evaluación
- Ver historial de evaluaciones por persona
- Editar evaluación
- Eliminar evaluación

### 3.3 Empleado del Mes

**Objetivo:** Reconocer al mejor trabajador del mes basándose en sus evaluaciones.

**Acceso:** `/evaluaciones/empleado-del-mes`

**Funcionamiento:**
1. Se crean evaluaciones mensuales de tipo "MENSUAL"
2. El sistema ordena automáticamente por puntuación
3. El administrador puede seleccionar al empleado del mes
4. Se guarda el registro histórico

### 3.4 Seguimientos

**Objetivo:** Dar seguimiento a casos específicos de cada persona.

**Acceso:** `/seguimientos`

**Tipos de Seguimiento:**
- Telefónico
- Presencial
- Académico
- Salud
- Social
- Laboral
- Otro

**Estados:**
- Pendiente
- En Proceso
- Completado
- Cancelado

**Prioridades:**
- Baja
- Media
- Alta
- Urgente

**Vistas Especiales:**
- `/seguimientos/pendientes` - Muestra seguimientos pendientes
- `/seguimientos/proximos` - Muestra seguimientos próximos (7 días)

---

## 4. Dashboard

**Acceso:** `/dashboard`

Muestra estadísticas generales del sistema:
- Total de personas registradas
- Personas activas
- Total de evaluaciones
- Total de seguimientos
- Seguimientos pendientes
- Seguimientos próximos

---

## 5. Departamentos

Departamentos predefinidos:
1. Recursos Humanos
2. Académico
3. Becas
4. Bienestar Estudiantil
5. Secretaría

**Nota:** Los Directores solo pueden gestionar personas de su departamento asignado.

---

## 6. Usuarios y Acceso

### Credenciales por defecto:
| Usuario | Contraseña | Rol |
|---------|------------|-----|
| admin | admin123 | ADMIN |

### Sesión
- Login: `/login`
- Logout: `/logout`
- Registro: `/register`

---

## 7. Mejoras Sugeridas

### Funcionalidades Pendientes de Implementar:

1. **Reportes y Exportación**
   - Exportar personas a Excel/PDF
   - Generar reportes por departamento
   - Reportes de evaluaciones mensuales

2. **Notificaciones**
   - Alertas por email de seguimientos próximos
   - Recordatorios de evaluaciones mensuales

3. **Multimedia**
   - Subida de fotos de personas
   - Adjuntar documentos a evaluaciones

4. **Historial de Cambios**
   - Registrar quién cambió qué y cuándo
   - Auditoría de modificaciones

5. **Dashboard Personalizado**
   - Widgets configurables
   - Gráficos de estadísticas

6. **API REST**
   - Endpoints para integración externa
   - Autenticación por tokens

7. **Mejoras de Seguridad**
   - Recuperación de contraseña
   - Verificación en dos pasos
   - Roles más granulares

8. **Optimización**
   - Búsqueda avanzada con filtros
   - Paginación optimizada
   - Cache de consultas

---

## 8. Especificaciones Técnicas

- **Framework:** CodeIgniter 4.7.0
- **Base de Datos:** MySQL
- **Frontend:** Bootstrap 5 + Bootstrap Icons
- **Idioma:** Español

### Estructura de Base de Datos:
- `personas` - 45+ campos de caracterización
- `evaluaciones` - Evaluaciones del personal
- `seguimientos` - Seguimientos de casos
- `usuarios` - Usuarios del sistema
- `departamentos` - Departamentos organizacionales

---

## 9. Troubleshooting

### Problemas comunes:

| Problema | Solución |
|----------|----------|
| Error de conexión a BD | Verificar que MySQL esté corriendo y credenciales en .env |
| Migraciones no funcionan | Ejecutar `php spark migrate` |
| Sesión no inicia | Verificar configuración de sesiones en .env |
| Validaciones en inglés | Configurar idioma en app/Config/App.php |

---

*Sistema desarrollado para gestión de caracterización y seguimiento de personal.*
