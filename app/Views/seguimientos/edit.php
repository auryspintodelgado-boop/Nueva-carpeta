<?php
/**
 * Vista de Editar Seguimiento
 * Sistema de Evaluación, Seguimiento y Caracterización
 */
$personas = $personas ?? [];
$tiposSeguimiento = $tiposSeguimiento ?? [];
$seguimiento = $seguimiento ?? [];
$data = $data ?? $seguimiento;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-journal-plus"></i> Editar Seguimiento</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/seguimientos" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<form method="POST" action="/seguimientos/update/<?= htmlspecialchars($seguimiento['id'] ?? '') ?>">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-journal-text"></i> Datos del Seguimiento</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Persona *</label>
                    <select name="persona_id" class="form-select" required>
                        <option value="">Seleccionar persona</option>
                        <?php foreach ($personas as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($data['persona_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['primer_nombre'] . ' ' . $p['primer_apellido'] . ' - ' . $p['cedula']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Seguimiento *</label>
                    <select name="tipo_seguimiento_id" class="form-select" required>
                        <option value="">Seleccionar tipo</option>
                        <?php foreach ($tiposSeguimiento as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ($data['tipo_seguimiento_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha de Seguimiento *</label>
                    <input type="date" name="fecha_seguimiento" class="form-control" value="<?= htmlspecialchars($data['fecha_seguimiento'] ?? '') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Próxima Fecha</label>
                    <input type="date" name="proxima_fecha" class="form-control" value="<?= htmlspecialchars($data['proxima_fecha'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Responsable</label>
                    <input type="text" name="responsable" class="form-control" value="<?= htmlspecialchars($data['responsable'] ?? '') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Descripción *</label>
                    <textarea name="descripcion" class="form-control" rows="4" required><?= htmlspecialchars($data['descripcion'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Resultado</label>
                    <textarea name="resultado" class="form-control" rows="3"><?= htmlspecialchars($data['resultado'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado_seguimiento" class="form-select">
                        <option value="Pendiente" <?= ($data['estado_seguimiento'] ?? 'Pendiente') == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="En_Proceso" <?= ($data['estado_seguimiento'] ?? '') == 'En_Proceso' ? 'selected' : '' ?>>En Proceso</option>
                        <option value="Completado" <?= ($data['estado_seguimiento'] ?? '') == 'Completado' ? 'selected' : '' ?>>Completado</option>
                        <option value="Cancelado" <?= ($data['estado_seguimiento'] ?? '') == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Guardar Cambios
        </button>
        <a href="/seguimientos" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
</main>
</div>
</div>
</body>
</html>
