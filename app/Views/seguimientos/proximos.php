<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-check text-info"></i> Seguimientos Próximos (Próximos 7 días)</h2>
        <a href="<?= base_url('/seguimientos/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Seguimiento
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($seguimientos)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-calendar-check fs-1 text-success"></i>
                    <p class="text-muted mt-2">No hay seguimientos programados para los próximos días</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha Programada</th>
                                <th>Días Restantes</th>
                                <th>Tipo</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Prioridad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($seguimientos as $seg): 
                                $dias = (strtotime($seg['proxima_fecha']) - strtotime(date('Y-m-d'))) / 86400;
                            ?>
                            <tr class="<?= $dias <= 2 ? 'table-danger' : ($dias <= 5 ? 'table-warning' : '') ?>">
                                <td><?= date('d/m/Y', strtotime($seg['proxima_fecha'])) ?></td>
                                <td>
                                    <?php if ($dias < 0): ?>
                                        <span class="badge bg-danger">Atrasado</span>
                                    <?php elseif ($dias == 0): ?>
                                        <span class="badge bg-danger">Hoy</span>
                                    <?php elseif ($dias == 1): ?>
                                        <span class="badge bg-warning">Mañana</span>
                                    <?php else: ?>
                                        <span class="badge bg-info"><?= floor($dias) ?> días</span>
                                    <?php endif; ?>
                                </td>
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
