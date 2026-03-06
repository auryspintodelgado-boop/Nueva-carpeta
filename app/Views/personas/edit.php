<?php
/**
 * Vista de Edición de Persona
 * Sistema de Evaluación, Seguimiento y Caracterización
 */
$persona = $persona ?? [];
$data = $data ?? $persona;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-person-edit"></i> Editar Persona</h1>
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
                <li><?= htmlspecialchars(is_array($error) ? implode(', ', $error) : $error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/personas/update/<?= htmlspecialchars($persona['id'] ?? '') ?>">
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
                    <select name="nacionalidad" class="form-select">
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
                    <select name="sexo" class="form-select">
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
                    <select name="carrera" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="1" <?= ($data['carrera_id'] ?? '') == '1' ? 'selected' : '' ?>>Carrera 1</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Universidad</label>
                    <select name="nombre_universidad" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="1" <?= ($data['universidad_id'] ?? '') == '1' ? 'selected' : '' ?>>Universidad 1</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Sede</label>
                    <select name="sede" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="1" <?= ($data['sede_id'] ?? '') == '1' ? 'selected' : '' ?>>Sede 1</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Año/Semestre</label>
                    <input type="text" name="anio_semestre" class="form-control" value="<?= htmlspecialchars($data['anio_semestre'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Posee Beca</label>
                    <select name="posee_beca" class="form-select">
                        <option value="0" <?= ($data['posee_beca'] ?? 0) == 0 ? 'selected' : '' ?>>No</option>
                        <option value="1" <?= ($data['posee_beca'] ?? 0) == 1 ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo de Beca</label>
                    <input type="text" name="tipo_beca" class="form-control" value="<?= htmlspecialchars($data['tipo_beca'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Información de Ubicación -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Ubicación</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado_residencia" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="1" <?= ($data['estado_id'] ?? '') == '1' ? 'selected' : '' ?>>Distrito Capital</option>
                        <option value="2" <?= ($data['estado_id'] ?? '') == '2' ? 'selected' : '' ?>>Miranda</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Municipio</label>
                    <select name="municipio" class="form-select">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Parroquia</label>
                    <select name="parroquia" class="form-select">
                        <option value="">Seleccionar</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Urbanización</label>
                    <input type="text" name="urbanizacion" class="form-control" value="<?= htmlspecialchars($data['urbanizacion'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dirección Exacta</label>
                    <textarea name="direccion_exacta" class="form-control" rows="2"><?= htmlspecialchars($data['direccion_exacta'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Familiar -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-people"></i> Información Familiar</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Estado Civil</label>
                    <select name="estado_civil" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="1" <?= ($data['estado_civil_id'] ?? '') == '1' ? 'selected' : '' ?>>Soltero/a</option>
                        <option value="2" <?= ($data['estado_civil_id'] ?? '') == '2' ? 'selected' : '' ?>>Casado/a</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tiene Hijos</label>
                    <select name="tiene_hijos" class="form-select">
                        <option value="0" <?= ($data['tiene_hijos'] ?? 0) == 0 ? 'selected' : '' ?>>No</option>
                        <option value="1" <?= ($data['tiene_hijos'] ?? 0) == 1 ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cantidad de Hijos</label>
                    <input type="number" name="cantidad_hijos" class="form-control" value="<?= htmlspecialchars($data['cantidad_hijos'] ?? 0) ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Información Laboral -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-briefcase"></i> Información Laboral</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Trabaja</label>
                    <select name="trabaja" class="form-select">
                        <option value="0" <?= ($data['trabaja'] ?? 0) == 0 ? 'selected' : '' ?>>No</option>
                        <option value="1" <?= ($data['trabaja'] ?? 0) == 1 ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nombre de Empresa</label>
                    <input type="text" name="nombre_empresa" class="form-control" value="<?= htmlspecialchars($data['nombre_empresa'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cargo</label>
                    <input type="text" name="cargo" class="form-control" value="<?= htmlspecialchars($data['cargo'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- Información Electoral -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-card-checklist"></i> Información Electoral</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Inscrito en el CNE</label>
                    <select name="inscrito_cne" class="form-select">
                        <option value="0" <?= ($data['inscrito_cne'] ?? 0) == 0 ? 'selected' : '' ?>>No</option>
                        <option value="1" <?= ($data['inscrito_cne'] ?? 0) == 1 ? 'selected' : '' ?>>Sí</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Centro Electoral</label>
                    <input type="text" name="centro_electoral" class="form-control" value="<?= htmlspecialchars($data['centro_electoral'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Municipio Electoral</label>
                    <input type="text" name="municipio_electoral" class="form-control" value="<?= htmlspecialchars($data['municipio_electoral'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-save"></i> Guardar Cambios
        </button>
        <a href="/personas" class="btn btn-secondary btn-lg">Cancelar</a>
    </div>
</form>
</div>
</main>
</div>
</div>
</body>
</html>
