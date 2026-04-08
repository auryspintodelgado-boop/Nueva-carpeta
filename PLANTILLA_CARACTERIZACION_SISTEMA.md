# PLANTILLA DE CARACTERIZACIÓN - SISTEMA DE EVALUACIÓN Y SEGUIMIENTO

## 1. DATOS DE IDENTIFICACIÓN PERSONAL

| # | Campo | Tipo de Dato | Descripción |
|---|-------|--------------|-------------|
| 1 | id_persona | INTEGER | Identificador único |
| 2 | numero | VARCHAR(20) | Número de registro en el sistema |
| 3 | nacionalidad | VARCHAR(50) | Nacionalidad |
| 4 | cedula | VARCHAR(20) | Número de cédula de identidad |
| 5 | primer_nombre | VARCHAR(100) | Primer nombre |
| 6 | segundo_nombre | VARCHAR(100) | Segundo nombre |
| 7 | primer_apellido | VARCHAR(100) | Primer apellido |
| 8 | segundo_apellido | VARCHAR(100) | Segundo apellido |
| 9 | sexo | ENUM | Masculino/Femenino/Otro |
| 10 | fecha_nacimiento | DATE | Fecha de nacimiento |
| 11 | edad | INTEGER | Edad (calculada automáticamente) |
| 12 | correo_electronico | VARCHAR(255) | Correo electrónico |
| 13 | telefono_1 | VARCHAR(20) | Teléfono principal |
| 14 | foto | BLOB | Fotografía del usuario |

---

## 2. DATOS ACADÉMICOS

| # | Campo | Tipo de Dato | Descripción |
|---|-------|--------------|-------------|
| 15 | carrera | VARCHAR(200) | Carrera o programa de estudios |
| 16 | anio_semestre | VARCHAR(20) | Año/Semestre/Trimestre |
| 17 | posee_beca | BOOLEAN | ¿Posee beca? |
| 18 | tipo_beca | VARCHAR(100) | Tipo de beca (si aplica) |
| 19 | sede | VARCHAR(100) | Sede universitaria |
| 20 | estado | VARCHAR(100) | Estado donde estudia |
| 21 | siglas_universidad | VARCHAR(20) | Siglas de la universidad |
| 22 | nombre_universidad | VARCHAR(200) | Nombre completo de la universidad |
| 23 | tipo_ieu | ENUM | Pública/Privada |
| 24 | nivel_academico | ENUM | Pregrado/Postgrado |

---

## 3. DATOS DE UBICACIÓN

| # | Campo | Tipo de Dato | Descripción |
|---|-------|--------------|-------------|
| 25 | pais | VARCHAR(100) | País de residencia |
| 26 | estado_residencia | VARCHAR(100) | Estado/Provincia |
| 27 | municipio | VARCHAR(100) | Municipio |
| 28 | parroquia | VARCHAR(100) | Parroquia |
| 29 | urbanization | VARCHAR(200) | Urbanización/Barrio |
| 30 | direccion_exacta | TEXT | Dirección exacta |
| 31 | comuna | VARCHAR(100) | Comuna (Venezuela) |

---

## 4. DATOS FAMILIARES

| # | Campo | Tipo de Dato | Descripción |
|---|-------|--------------|-------------|
| 32 | estado_civil | ENUM | Soltero/Casado/Divorciado/Viudo/Unión libre |
| 33 | tiene_hijos | BOOLEAN | ¿Tiene hijos? |
| 34 | cantidad_hijos | INTEGER | Cantidad de hijos |
| 35 | carga_familiar | INTEGER | Número de personas a cargo |

---

## 5. DATOS DE SALUD

| # | Campo | Tipo de Dato | Descripción |
|---|-------|--------------|-------------|
| 36 | posee_discapacidad | BOOLEAN | ¿Posee alguna discapacidad? |
| 37 | tipo_discapacidad | VARCHAR(200) | Tipo de discapacidad |
| 38 | presenta_enfermedad | BOOLEAN | ¿Presenta alguna enfermedad? |
| 39 | condicion_medica | VARCHAR(200) | Condición médica |
| 40 | medicamentos | TEXT | Medicamentos que consume |
| 41 | tipo_sangre | ENUM | Tipo de sangre (A+, A-, B+, B-, AB+, AB-, O+, O-) |
| 42 | altura | DECIMAL | Altura (en cm) |
| 43 | peso | DECIMAL | Peso (en kg) |

---

## 6. DATOS LABORALES

| # | Campo | Tipo de Dato | Descripción |
|---|-------|--------------|-------------|
| 44 | trabaja | BOOLEAN | ¿Trabaja? |
| 45 | tipo_empleo | VARCHAR(100) | Tipo de empleo |
| 46 | nombre_empresa | VARCHAR(200) | Nombre de la empresa |
| 47 | cargo | VARCHAR(100) | Cargo que ocupa |
| 48 | ingreso_mensual | DECIMAL | Ingreso mensual |
| 49 | medio_transporte | VARCHAR(50) | Medio de transporte habitual |

---

## 7. DATOS ELECTORALES (Venezuela)

| # | Campo | Tipo de Dato | Descripción |
|---|-------|--------------|-------------|
| 50 | inscrito_cne | BOOLEAN | ¿Está inscrito en el CNE? |
| 51 | centro_electoral | VARCHAR(200) | Centro electoral |
| 52 | municipio_electoral | VARCHAR(100) | Municipio electoral |
| 53 | puesto_votacion | VARCHAR(100) | Punto de votación |

---

## 8. DATOS ANTROPOMÉTRICOS (Uniformes)

| # | Campo | Tipo de Dato | Descripción |
|---|-------|--------------|-------------|
| 54 | talla_camisa | VARCHAR(10) | Talla de camisa |
| 55 | talla_zapato | VARCHAR(10) | Talla de zapato |
| 56 | talla_pantalon | VARCHAR(10) | Talla de pantalón |

---

## 9. METADATOS DEL REGISTRO

| # | Campo | Tipo de Dato | Descripción |
|---|-------|--------------|-------------|
| 57 | fecha_registro | DATETIME | Fecha de registro en el sistema |
| 58 | fecha_actualizacion | DATETIME | Última fecha de actualización |
| 59 | usuario_registro | VARCHAR(100) | Usuario que registró |
| 60 | estado_registro | ENUM | Activo/Inactivo/Pendiente |

---

# AGRUPACIONES PARA INTERFAZ DE USUARIO

## Pestaña 1: Información Personal
- Datos de Identificación Personal
- Datos de Contacto
- Fotografía

## Pestaña 2: Información Académica
- Datos Académicos

## Pestaña 3: Ubicación
- Datos de Ubicación

## Pestaña 4: Familia
- Datos Familiares

## Pestaña 5: Salud
- Datos de Salud

## Pestaña 6: Laboral
- Datos Laborales

## Pestaña 7: Electoral
- Datos Electorales

## Pestaña 8: Configuración
- Datos Antropométricos
- Metadatos

---

# NOTAS PARA IMPLEMENTACIÓN

1. **Validaciones recomendadas**:
   - Cédula: Formato numérico único por país
   - Correo: Formato de email válido
   - Teléfono: Formato con código de país
   - Fecha de nacimiento: No mayor a edad de 100 años, no menor a edad mínima

2. **Campos calculados automáticamente**:
   - Edad (a partir de fecha_nacimiento)
   - fecha_actualizacion (automático)

3. **Relaciones recomendadas**:
   - Una persona puede tener múltiples evaluaciones
   - Una persona puede tener múltiples seguimientos
   - Una persona puede cambiar de carrera/sede a lo largo del tiempo
