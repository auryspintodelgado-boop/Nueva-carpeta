<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clipboard-check"></i> Panel de Evaluaciones - Administración</h2>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Información del Período -->
    <div class="card mb-4 bg-light">
        <div class="card-body text-center">
            <h4>Período Actual: <?= $mesActual ?></h4>
        </div>
    </div>

    <!-- Lista de Departamentos -->
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
                            <h5 class="mb-0"><?= $dept['nombre'] ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= $dept['descripcion'] ?? 'Sin descripción' ?></p>
                            <a href="<?= base_url('/evaluaciones/director?departamento=' . $dept['id']) ?>" class="btn btn-primary">
                                <i class="bi bi-clipboard-plus"></i> Ver Evaluaciones
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Acceso Rápido -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Acceso Rápido</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <a href="<?= base_url('/evaluaciones/director') ?>" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle"></i> Nueva Evaluación
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="<?= base_url('/evaluaciones/director/historial') ?>" class="btn btn-info w-100">
                        <i class="bi bi-clock-history"></i> Historial General
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="<?= base_url('/reportes/evaluaciones') ?>" class="btn btn-secondary w-100">
                        <i class="bi bi-file-earmark-text"></i> Reportes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
