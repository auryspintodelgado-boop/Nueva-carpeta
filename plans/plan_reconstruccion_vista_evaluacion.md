# Plan: Reconstrucción de Vista de Evaluación

## Objetivo
Reconstruir la vista de evaluación (`evaluaciones/director/create.php`) para que coincida con el formato oficial de evaluación del desempeño laboral de la Fundación "Castillo San Antonio de la Eminencia".

## Análisis del Documento Oficial

### Estructura de Evaluación

| Área | Puntaje Máximo | Sub-campos |
|------|----------------|------------|
| Orientación de Resultados | 5 | 3 |
| Calidad y Organización | 5 | 8 |
| Relaciones Interpersonales | 5 | 5 |
| Iniciativa | 5 | 4 |
| **TOTAL** | **20** | **20 sub-campos** |

### Escala de Evaluación
- 1: Muy bajo - Rendimiento no aceptable
- 2: Bajo - Rendimiento regular
- 3: Moderado - Rendimiento bueno
- 4: Alto - Rendimiento muy bueno
- 5: Muy Alto - Rendimiento excelente

---

## Plan de Implementación

### 1. Actualizar la Migración de Evaluaciones
- Agregar campos para cada sub-campo en la tabla `evaluaciones`
- Crear migración: `2024-01-01-000011_UpdateEvaluacionesCamposDetallados.php`

**Campos a agregar:**
- `orientacion_resultados_termino` (terminar trabajo oportunamente)
- `orientacion_resultados_cumple_tareas` (cumple tareas)
- `orientacion_resultados_volumen` (volumen de trabajo)
- `calidad_organizacion_sin_errores` (no comete errores)
- `calidad_organizacion_recursos` (uso racional de recursos)
- `calidad_organizacion_supervision` (no requiere supervisión frecuente)
- `calidad_organizacion_profesional` (profesionalismo)
- `calidad_organizacion_respetuoso` (respetuoso y amable)
- `calidad_organizacion_planifica` (planifica actividades)
- `calidad_organizacion_indicadores` (usa indicadores)
- `calidad_organizacion_metas` (alcanza metas)
- `relaciones_interpersonales_cortes` (cortés)
- `relaciones_interpersonales_orientacion` (orienta compañeros)
- `relaciones_interpersonales_conflictos` (evita conflictos)
- `relaciones_interpersonales_integracion` (integración al equipo)
- `relaciones_interpersonales_objetivos` (identificación con objetivos)
- `iniciativa_ideas` (nuevas ideas)
- `iniciativa_cambio` (aceptable al cambio)
- `iniciativa_anticipacion` (se anticipa a dificultades)
- `iniciativa_resolucion` (resuelve problemas)

### 2. Actualizar el Modelo EvaluacionModel
- Agregar los nuevos campos a `allowedFields`
- Actualizar método `calcularPuntuacionEsquema()` para calcular correctamente

### 3. Actualizar el Controlador EvaluacionDirectorController
- Actualizar método `store()` para recibir los 20 campos
- Actualizar método `show()` para mostrar los datos
- Agregar método para generar PDF del formato oficial

### 4. Reconstruir la Vista create.php
- Rediseñar el formulario según el documento oficial
- Agregar los 20 sub-campos organizados en las 4 secciones
- Usar select/radio buttons para la escala 1-5
- Agregar campos de comentarios por sección
- Agregar sección de comentarios generales
- Agregar campos de firma (evaluador y ratificador)

### 5. Actualizar Vistas Relacionadas
- `evaluaciones/director/show.php` - Mostrar detalle completo
- `evaluaciones/director/historial.php` - Mostrar con formato
- Generar PDF del formato oficial

---

## Orden de Implementación

1. [ ] Crear migración con los 20 campos adicionales
2. [ ] Actualizar EvaluacionModel con los nuevos campos
3. [ ] Actualizar EvaluacionDirectorController::store()
4. [ ] Reconstruir vista create.php con el nuevo formato
5. [ ] Actualizar vista show.php para mostrar todos los campos
6. [ ] Generar PDF del formato oficial
