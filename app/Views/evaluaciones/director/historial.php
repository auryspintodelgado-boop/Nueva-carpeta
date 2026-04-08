<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clock-history"></i> Historial de Evaluaciones</h2>
        <a href="<?= base_url('/evaluaciones/director') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Selector de Mes -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" action="<?= base_url('/evaluaciones/director/historial') ?>" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Consultar Mes</label>
                    <input type="month" name="mes" class="form-control" value="<?= $mes ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas del Mes Seleccionado -->
    <?php if ($estadisticas && $estadisticas['total_evaluaciones'] > 0): ?>
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Estadísticas de <?= $mes ?></h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-2 mb-3">
                    <div class="p-3 bg-light rounded">
                        <h4 class="text-primary"><?= $estadisticas['total_evaluaciones'] ?></h4>
                        <small>Total Evaluaciones</small>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="p-3 bg-primary bg-opacity-10 rounded">
                        <h4 class="text-primary"><?= number_format($estadisticas['avg_orientacion'] ?? 0, 1) ?></h4>
                        <small>Orient. Resultados</small>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="p-3 bg-success bg-opacity-10 rounded">
                        <h4 class="text-success"><?= number_format($estadisticas['avg_calidad'] ?? 0, 1) ?></h4>
                        <small>Calidad</small>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="p-3 bg-info bg-opacity-10 rounded">
                        <h4 class="text-info"><?= number_format($estadisticas['avg_relaciones'] ?? 0, 1) ?></h4>
                        <small>Relaciones</small>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                        <h4 class="text-warning"><?= number_format($estadisticas['avg_iniciativa'] ?? 0, 1) ?></h4>
                        <small>Iniciativa</small>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <div class="p-3 bg-primary rounded">
                        <h4 class="text-white"><?= number_format($estadisticas['avg_total'] ?? 0, 1) ?></h4>
                        <small class="text-white">Promedio Total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Lista de Evaluaciones -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Evaluaciones de <?= $mes ?></h5>
        </div>
        <div class="card-body">
            <?php if (empty($evaluaciones)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">No hay evaluaciones para este período</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cédula</th>
                                <th>Nombre Completo</th>
                                <th class="text-center">Ori. Res.</th>
                                <th class="text-center">Calidad</th>
                                <th class="text-center">Relac.</th>
                                <th class="text-center">Inici.</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Resultado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($evaluaciones as $eval): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $eval['cedula'] ?></td>
                                    <td><?= $eval['primer_nombre'] ?> <?= $eval['primer_apellido'] ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= number_format($eval['orientacion_resultados'], 1) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?= number_format($eval['calidad_organizacion'], 1) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info"><?= number_format($eval['relaciones_interpersonales'], 1) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning"><?= number_format($eval['iniciativa'], 1) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <strong><?= number_format($eval['puntuacion'], 1) ?>/20</strong>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $puntuacion = floatval($eval['puntuacion']);
                                        if ($puntuacion >= 18) {
                                            $badgeClass = 'bg-success';
                                        } elseif ($puntuacion >= 15) {
                                            $badgeClass = 'bg-info';
                                        } elseif ($puntuacion >= 12) {
                                            $badgeClass = 'bg-primary';
                                        } elseif ($puntuacion >= 10) {
                                            $badgeClass = 'bg-warning';
                                        } else {
                                            $badgeClass = 'bg-danger';
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $eval['resultado'] ?></span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($eval['fecha_evaluacion'])) ?></td>
                                    <td>
                                        <a href="<?= base_url('/evaluaciones/director/show/' . $eval['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
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
</div>
<?= $this->endSection() ?>
