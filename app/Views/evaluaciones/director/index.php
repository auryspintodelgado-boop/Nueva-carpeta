<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-building"></i> Evaluación de Personal - <?= $departamento['nombre'] ?? 'Departamento' ?></h2>
        <a href="<?= base_url('/evaluaciones/director/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Evaluación
        </a>
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

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-0"><?= $totalPersonal ?></h3>
                    <small>Total Personal</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0"><?= $evaluados ?></h3>
                    <small>Evaluados Este Mes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h3 class="mb-0"><?= $totalPersonal - $evaluados ?></h3>
                    <small>Pendientes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h3 class="mb-0"><?= $mesActual ?></h3>
                    <small>Mes en Curso</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas del Mes -->
    <?php if ($estadisticas && $estadisticas['total_evaluaciones'] > 0): ?>
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Estadísticas de <?= $mesActual ?></h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-primary bg-opacity-10 rounded">
                        <h4 class="text-primary"><?= number_format($estadisticas['avg_orientacion'] ?? 0, 1) ?></h4>
                        <small>Orientación de Resultados</small>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-success bg-opacity-10 rounded">
                        <h4 class="text-success"><?= number_format($estadisticas['avg_calidad'] ?? 0, 1) ?></h4>
                        <small>Calidad y Organización</small>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-info bg-opacity-10 rounded">
                        <h4 class="text-info"><?= number_format($estadisticas['avg_relaciones'] ?? 0, 1) ?></h4>
                        <small>Relaciones Interpersonales</small>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                        <h4 class="text-warning"><?= number_format($estadisticas['avg_iniciativa'] ?? 0, 1) ?></h4>
                        <small>Iniciativa</small>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <h3>Promedio General: <span class="text-primary"><?= number_format($estadisticas['avg_total'] ?? 0, 1) ?>/20</span></h3>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Lista de Evaluaciones del Mes -->
    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> Evaluaciones de <?= $mesActual ?></h5>
            <a href="<?= base_url('/evaluaciones/director/historial?mes=' . $mesActual) ?>" class="btn btn-sm btn-outline-secondary">
                Ver Historial
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($evaluacionesMes)): ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">No hay evaluaciones registradas este mes</p>
                    <a href="<?= base_url('/evaluaciones/director/create') ?>" class="btn btn-primary">
                        Realizar Primera Evaluación
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th class="text-center">Orient. Resultados</th>
                                <th class="text-center">Calidad</th>
                                <th class="text-center">Relaciones</th>
                                <th class="text-center">Iniciativa</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Resultado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evaluacionesMes as $eval): ?>
                                <tr>
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
                                        $puntuacion = $eval['puntuacion'];
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
