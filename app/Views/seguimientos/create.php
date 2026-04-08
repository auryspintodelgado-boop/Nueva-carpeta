<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Nuevo Seguimiento</h2>
        <a href="<?= base_url('/seguimientos' . ($persona_id ? '?persona_id=' . $persona_id : '')) ?>" class="btn btn-secondary">
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

    <form action="<?= base_url('/seguimientos') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Persona *</label>
                        <select name="persona_id" class="form-select" required>
                            <option value="">Seleccionar persona</option>
                            <?php foreach ($personas as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= $persona_id == $p['id'] ? 'selected' : '' ?>>
                                    <?= $p['cedula'] ?> - <?= $p['primer_nombre'] ?> <?= $p['primer_apellido'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de Seguimiento *</label>
                        <select name="tipo_seguimiento" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="TELEFONICO">Telefónico</option>
                            <option value="PRESENCIAL">Presencial</option>
                            <option value="ACADEMICO">Académico</option>
                            <option value="SALUD">Salud</option>
                            <option value="SOCIAL">Social</option>
                            <option value="LABORAL">Laboral</option>
                            <option value="OTRO">Otro</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Seguimiento *</label>
                        <input type="date" name="fecha_seguimiento" class="form-control" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Prioridad</label>
                        <select name="prioridad" class="form-select">
                            <option value="BAJA">Baja</option>
                            <option value="MEDIA" selected>Media</option>
                            <option value="ALTA">Alta</option>
                            <option value="URGENTE">Urgente</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="PENDIENTE">Pendiente</option>
                            <option value="EN_PROCESO">En Proceso</option>
                            <option value="COMPLETADO">Completado</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripción del seguimiento..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Acciones Realizadas</label>
                    <textarea name="acciones" class="form-control" rows="3" placeholder="Acciones tomadas..."></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Resultado</label>
                        <textarea name="resultado" class="form-control" rows="2" placeholder="Resultado del seguimiento..."></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Próxima Fecha</label>
                        <input type="date" name="proxima_fecha" class="form-control">
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Guardar Seguimiento
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
