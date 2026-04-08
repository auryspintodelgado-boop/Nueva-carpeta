<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Nueva Evaluación</h2>
        <a href="<?= base_url('/evaluaciones' . (isset($persona_id) && $persona_id ? '?persona_id=' . $persona_id : '')) ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/evaluaciones') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Persona *</label>
                        <select name="persona_id" class="form-select" required>
                            <option value="">Seleccionar persona</option>
                            <?php if (isset($personas)): ?>
                                <?php foreach ($personas as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= (isset($persona_id) && $persona_id == $p['id']) ? 'selected' : '' ?>>
                                        <?= $p['cedula'] ?> - <?= $p['primer_nombre'] ?> <?= $p['primer_apellido'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de Evaluación *</label>
                        <select name="tipo_evaluacion" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="ACADEMICA">Académica</option>
                            <option value="PSICOLOGICA">Psicológica</option>
                            <option value="SOCIAL">Social</option>
                            <option value="ECONOMICA">Económica</option>
                            <option value="SEGUIMIENTO">Seguimiento</option>
                            <option value="OTRA">Otra</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Título de la evaluación">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fecha de Evaluación *</label>
                        <input type="date" name="fecha_evaluacion" class="form-control" required value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Calificación</label>
                        <input type="number" name="calificacion" class="form-control" step="0.01" min="0" max="20" placeholder="0-20">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Resultado</label>
                    <textarea name="resultado" class="form-control" rows="4" placeholder="Resultado de la evaluación..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="3" placeholder="Observaciones adicionales..."></textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Evaluación
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
