<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $title ?></h2>
        <div>
            <a href="<?= base_url('/seguimientos/create') ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Seguimiento
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <a href="<?= base_url('/seguimientos') ?>" class="btn btn-sm <?= !$estado ? 'btn-primary' : 'btn-outline-primary' ?>">Todos</a>
                    <a href="<?= base_url('/seguimientos?estado=PENDIENTE') ?>" class="btn btn-sm <?= $estado == 'PENDIENTE' ? 'btn-warning' : 'btn-outline-warning' ?>">Pendientes</a>
                    <a href="<?= base_url('/seguimientos?estado=EN_PROCESO') ?>" class="btn btn-sm <?= $estado == 'EN_PROCESO' ? 'btn-primary' : 'btn-outline-primary' ?>">En Proceso</a>
                    <a href="<?= base_url('/seguimientos?estado=COMPLETADO') ?>" class="btn btn-sm <?= $estado == 'COMPLETADO' ? 'btn-success' : 'btn-outline-success' ?>">Completados</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($seguimientos)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-journal-text fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No hay seguimientos registrados</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Próxima Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($seguimientos as $seg): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($seg['fecha_seguimiento'])) ?></td>
                                <td><?= $seg['tipo_seguimiento'] ?></td>
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
                                <td><?= $seg['proxima_fecha'] ? date('d/m/Y', strtotime($seg['proxima_fecha'])) : '-' ?></td>
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
