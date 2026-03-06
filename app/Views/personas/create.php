<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person-plus"></i> Nueva Persona</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/personas" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<?php if (isset($errors) && !empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/personas/store">
    <!-- Información Personal -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person"></i> Información Personal</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label">Número</label>
                    <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($data['numero'] ?? '') ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Nacionalidad *</label>
                    <select name="nacionalidad_id" class="form-select" required>
                        <option value="">Seleccionar</option>
                        <option value="1" <?= ($data['nacionalidad_id'] ?? '') == '1' ? 'selected' : '' ?>>Venezolano</option>
                        <option value="2" <?= ($data['nacionalidad_id'] ?? '') == '2' ? 'selected' : '' ?>>Extranjero</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cédula *</label>
                    <input type="text" name="cedula" class="form-control" value="<?= htmlspecialchars($data['cedula'] ?? '') ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Primer Nombre *</label>
                    <input type="text" name="primer_nombre" class="form-control" value="<?= htmlspecialchars($data['primer_nombre'] ?? '') ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" class="form-control" value="<?= htmlspecialchars($data['segundo_nombre'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Primer Apellido *</label>
                    <input type="text" name="primer_apellido" class="form-control" value="<?= htmlspecialchars($data['primer_apellido'] ?? '') ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" class="form-control" value="<?= htmlspecialchars($data['segundo_apellido'] ?? '') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Sexo</label>
                    <select name="sexo_id" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="1" <?= ($data['sexo_id'] ?? '') == '1' ? 'selected' : '' ?>>Masculino</option>
                        <option value="2" <?= ($data['sexo_id'] ?? '') == '2' ? 'selected' : '' ?>>Femenino</option>
                        <option value="3" <?= ($data['sexo_id'] ?? '') == '3' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" value="<?= htmlspecialchars($data['fecha_nacimiento'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="correo_electronico" class="form-control" value="<?= htmlspecialchars($data['correo_electronico'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono_1" class="form-control" value="<?= htmlspecialchars($data['telefono_1'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Información Académica -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-book"></i> Información Académica</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Carrera</label>
                    <select name="carrera_id" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="1" <?= ($data['carrera_id'] ?? '') == '1' ? 'selected' : '' ?>>Carrera 1</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Año/Semestre</label>
                    <input type="text" name="anio_semestre" class="form-control" value="<?= htmlspecialchars($data['anio_semestre'] ?? '') ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">¿Posee Beca?</label>
                    <select name="posee_beca" class="form-select">
                        <option value="0">No</option>
                        <option value="1" <?= ($data['posee_beca'] ?? '') == '1' ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Sede</label>
                    <select name="sede_id" class="form-select">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Universidad</label>
                    <select name="universidad_id" class="form-select">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipo de IEU</label>
                    <select name="tipo_ieu" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="Pública" <?= ($data['tipo_ieu'] ?? '') == 'Pública' ? 'selected' : '' ?>>Pública</option>
                        <option value="Privada" <?= ($data['tipo_ieu'] ?? '') == 'Privada' ? 'selected' : '' ?>>Privada</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Nivel Académico</label>
                    <select name="nivel_academico" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="Pregrado" <?= ($data['nivel_academico'] ?? '') == 'Pregrado' ? 'selected' : '' ?>>Pregrado</option>
                        <option value="Postgrado" <?= ($data['nivel_academico'] ?? '') == 'Postgrado' ? 'selected' : '' ?>>Postgrado</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Ubicación -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Ubicación</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Estado</label>
                    <input type="text" name="estado_residencia" class="form-control" value="<?= htmlspecialchars($data['estado_residencia'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Municipio</label>
                    <input type="text" name="municipio" class="form-control" value="<?= htmlspecialchars($data['municipio'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Parroquia</label>
                    <input type="text" name="parroquia" class="form-control" value="<?= htmlspecialchars($data['parroquia'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Urbanización</label>
                    <input type="text" name="urbanizacion" class="form-control" value="<?= htmlspecialchars($data['urbanizacion'] ?? '') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dirección Exacta</label>
                    <textarea name="direccion_exacta" class="form-control" rows="2"><?= htmlspecialchars($data['direccion_exacta'] ?? '') ?></textarea>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Comuna</label>
                    <input type="text" name="comuna" class="form-control" value="<?= htmlspecialchars($data['comuna'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Datos Familiares -->
    <div class="card mb-4">
        <div class="card-header bg-warning">
            <h5 class="mb-0"><i class="bi bi-house-heart"></i> Datos Familiares</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Estado Civil</label>
                    <select name="estado_civil" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="Soltero" <?= ($data['estado_civil'] ?? '') == 'Soltero' ? 'selected' : '' ?>>Soltero</option>
                        <option value="Casado" <?= ($data['estado_civil'] ?? '') == 'Casado' ? 'selected' : '' ?>>Casado</option>
                        <option value="Divorciado" <?= ($data['estado_civil'] ?? '') == 'Divorciado' ? 'selected' : '' ?>>Divorciado</option>
                        <option value="Viudo" <?= ($data['estado_civil'] ?? '') == 'Viudo' ? 'selected' : '' ?>>Viudo</option>
                        <option value="Unión Libre" <?= ($data['estado_civil'] ?? '') == 'Unión Libre' ? 'selected' : '' ?>>Unión Libre</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">¿Tiene Hijos?</label>
                    <select name="tiene_hijos" class="form-select">
                        <option value="0">No</option>
                        <option value="1" <?= ($data['tiene_hijos'] ?? '') == '1' ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Cantidad de Hijos</label>
                    <input type="number" name="cantidad_hijos" class="form-control" value="<?= htmlspecialchars($data['cantidad_hijos'] ?? '0') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Carga Familiar</label>
                    <input type="number" name="carga_familiar" class="form-control" value="<?= htmlspecialchars($data['carga_familiar'] ?? '0') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Datos de Salud -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-heart-pulse"></i> Datos de Salud</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">¿Posee Discapacidad?</label>
                    <select name="posee_discapacidad" class="form-select">
                        <option value="0">No</option>
                        <option value="1" <?= ($data['posee_discapacidad'] ?? '') == '1' ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipo de Discapacidad</label>
                    <input type="text" name="tipo_discapacidad" class="form-control" value="<?= htmlspecialchars($data['tipo_discapacidad'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">¿Presenta Enfermedad?</label>
                    <select name="presenta_enfermedad" class="form-select">
                        <option value="0">No</option>
                        <option value="1" <?= ($data['presenta_enfermedad'] ?? '') == '1' ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipo de Sangre</label>
                    <select name="tipo_sangre" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="A+" <?= ($data['tipo_sangre'] ?? '') == 'A+' ? 'selected' : '' ?>>A+</option>
                        <option value="A-" <?= ($data['tipo_sangre'] ?? '') == 'A-' ? 'selected' : '' ?>>A-</option>
                        <option value="B+" <?= ($data['tipo_sangre'] ?? '') == 'B+' ? 'selected' : '' ?>>B+</option>
                        <option value="B-" <?= ($data['tipo_sangre'] ?? '') == 'B-' ? 'selected' : '' ?>>B-</option>
                        <option value="AB+" <?= ($data['tipo_sangre'] ?? '') == 'AB+' ? 'selected' : '' ?>>AB+</option>
                        <option value="AB-" <?= ($data['tipo_sangre'] ?? '') == 'AB-' ? 'selected' : '' ?>>AB-</option>
                        <option value="O+" <?= ($data['tipo_sangre'] ?? '') == 'O+' ? 'selected' : '' ?>>O+</option>
                        <option value="O-" <?= ($data['tipo_sangre'] ?? '') == 'O-' ? 'selected' : '' ?>>O-</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Condición Médica</label>
                    <input type="text" name="condicion_medica" class="form-control" value="<?= htmlspecialchars($data['condicion_medica'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Medicamentos</label>
                    <textarea name="medicamentos" class="form-control" rows="2"><?= htmlspecialchars($data['medicamentos'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Altura (cm)</label>
                    <input type="number" name="altura" class="form-control" value="<?= htmlspecialchars($data['altura'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" name="peso" class="form-control" value="<?= htmlspecialchars($data['peso'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Datos Laborales -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-briefcase"></i> Datos Laborales</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">¿Trabaja?</label>
                    <select name="trabaja" class="form-select">
                        <option value="0">No</option>
                        <option value="1" <?= ($data['trabaja'] ?? '') == '1' ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tipo de Empleo</label>
                    <input type="text" name="tipo_empleo" class="form-control" value="<?= htmlspecialchars($data['tipo_empleo'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Medio de Transporte</label>
                    <input type="text" name="medio_transporte" class="form-control" value="<?= htmlspecialchars($data['medio_transporte'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Datos Electorales -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-check-circle"></i> Datos Electorales</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">¿Inscrito en CNE?</label>
                    <select name="inscrito_cne" class="form-select">
                        <option value="0">No</option>
                        <option value="1" <?= ($data['inscrito_cne'] ?? '') == '1' ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-5 mb-3">
                    <label class="form-label">Centro Electoral</label>
                    <input type="text" name="centro_electoral" class="form-control" value="<?= htmlspecialchars($data['centro_electoral'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Comuna</label>
                    <input type="text" name="comuna" class="form-control" value="<?= htmlspecialchars($data['comuna'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Tallas -->
    <div class="card mb-4">
        <div class="card-header bg-purple text-white" style="background: #6f42c1;">
            <h5 class="mb-0"><i class="bi bi-rulers"></i> Tallas</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Talla Camisa</label>
                    <input type="text" name="talla_camisa" class="form-control" value="<?= htmlspecialchars($data['talla_camisa'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Talla Zapatos</label>
                    <input type="text" name="talla_zapato" class="form-control" value="<?= htmlspecialchars($data['talla_zapato'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Talla Pantalón</label>
                    <input type="text" name="talla_pantalon" class="form-control" value="<?= htmlspecialchars($data['talla_pantalon'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-save"></i> Guardar Persona
        </button>
        <a href="/personas" class="btn btn-secondary btn-lg">Cancelar</a>
    </div>
</form>
