<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-building"></i> Seleccionar Departamento</h2>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Seleccione el departamento al cual desea evaluar su personal.
    </div>

    <div class="row">
        <?php if (empty($departamentos)): ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    No hay departamentos registrados.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($departamentos as $dept): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><?= $dept['nombre'] ?> <small>(ID: <?= $dept['id'] ?>)</small></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= $dept['descripcion'] ?? 'Sin descripción' ?></p>
                            <a href="<?= base_url('/evaluaciones/director?departamento=' . $dept['id']) ?>" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-right"></i> Seleccionar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="mt-4">
        <a href="<?= base_url('/evaluaciones') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>
<?= $this->endSection() ?>
