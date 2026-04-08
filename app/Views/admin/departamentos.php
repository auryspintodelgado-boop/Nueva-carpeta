<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-building"></i> Gestión de Departamentos</h2>
        <a href="<?= base_url('/admin/departamentos/crear') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Departamento
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($departamentos)): ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No hay departamentos registrados. Cree el primer departamento.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departamentos as $dept): ?>
                                <tr>
                                    <td><?= $dept['id'] ?></td>
                                    <td><strong><?= $dept['nombre'] ?></strong></td>
                                    <td><?= $dept['codigo'] ?></td>
                                    <td><?= $dept['descripcion'] ?? 'Sin descripción' ?></td>
                                    <td>
                                        <span class="badge bg-<?= $dept['estado'] === 'ACTIVO' ? 'success' : 'secondary' ?>">
                                            <?= $dept['estado'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('/admin/departamentos/editar/' . $dept['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('/admin/departamentos/eliminar/' . $dept['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este departamento?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?= base_url('/admin') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>
</div>
<?= $this->endSection() ?>
