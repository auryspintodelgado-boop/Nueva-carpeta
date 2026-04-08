<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-exclamation-circle text-warning"></i> Seguimientos Pendientes</h2>
        <a href="<?= base_url('/seguimientos/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Seguimiento
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($seguimientos)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-check-circle fs-1 text-success"></i>
                    <p class="text-muted mt-2">No hay seguimientos pendientes</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Acciones</th>
                        </thead>
                            </tr>
                        <tbody>
                            <?php foreach ($seguimientos as $seg): ?>
                            <tr class="<?= $seg['prioridad'] === 'URGENTE' ? 'table-danger' : ($seg['prioridad'] === 'ALTA' ? 'table-warning' : '') ?>">
                                <td><?= date('d/m/Y', strtotime($seg['fecha_seguimiento'])) ?></td>
                                <td><?= $seg['tipo_seguimiento'] ?></td>
                                <td><?= substr($seg['descripcion'] ?? '', 0, 50) ?>...</td>
                                <td>
                                    <span class="badge bg-<?= $seg['estado'] == 'COMPLETADO' ? 'success' : ($seg['estado'] == 'PENDIENTE' ? 'warning' : 'primary') ?>">
                                        <?= $seg['estado'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $seg['prioridad'] == 'URGENTE' ? 'danger' : ($seg['prioridad'] == 'ALTA' ? 'warning' : 'secondary') ?>">
                                        <?= $seg['prioridad'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('/seguimientos/show/' . $seg['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('/seguimientos/edit/' . $seg['id']) ?>" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="<?= base_url('/seguimientos/cambiar-estado/' . $seg['id']) ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="estado" value="COMPLETADO">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Marcar como completado">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
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
