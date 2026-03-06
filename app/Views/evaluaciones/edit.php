<?php
/**
 * Vista de Editar Evaluación
 * Sistema de Evaluación, Seguimiento y Caracterización
 */
$personas = $personas ?? [];
$tiposEvaluacion = $tiposEvaluacion ?? [];
$evaluacion = $evaluacion ?? [];
$data = $data ?? $evaluacion;
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-clipboard-plus"></i> Editar Evaluación</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/evaluaciones" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<form method="POST" action="/evaluaciones/update/<?= htmlspecialchars($evaluacion['id'] ?? '') ?>">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Datos de la Evaluación</h5>
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
                    <label class="form-label">Tipo de Evaluación *</label>
                    <select name="tipo_evaluacion_id" class="form-select" required>
                        <option value="">Seleccionar tipo</option>
                        <?php foreach ($tiposEvaluacion as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ($data['tipo_evaluacion_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha de Evaluación *</label>
                    <input type="date" name="fecha_evaluacion" class="form-control" value="<?= htmlspecialchars($data['fecha_evaluacion'] ?? '') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Puntaje</label>
                    <input type="number" step="0.01" name="puntaje" class="form-control" value="<?= htmlspecialchars($data['puntaje'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Resultado</label>
                    <input type="text" name="resultado" class="form-control" value="<?= htmlspecialchars($data['resultado'] ?? '') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Evaluador</label>
                    <input type="text" name="evaluador" class="form-control" value="<?= htmlspecialchars($data['evaluador'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="3"><?= htmlspecialchars($data['observaciones'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Guardar Cambios
        </button>
        <a href="/evaluaciones" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
</main>
</div>
</div>
</body>
</html>
