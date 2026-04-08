<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $title ?></h2>
        <a href="<?= base_url('/evaluaciones/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Evaluación
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($evaluaciones)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-check fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No hay evaluaciones registradas</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Calificación</th>
                                <th>Resultado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evaluaciones as $eval): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($eval['fecha_evaluacion'])) ?></td>
                                <td><?= $eval['tipo_evaluacion'] ?></td>
                                <td><?= $eval['calificacion'] ?? '-' ?></td>
                                <td><?= substr($eval['resultado'] ?? '', 0, 50) ?>...</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('/evaluaciones/show/' . $eval['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('/evaluaciones/edit/' . $eval['id']) ?>" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
