<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-trophy text-warning"></i> Empleado del Mes</h2>
        <div>
            <select class="form-select" onchange="window.location.href='<?= base_url('/evaluaciones/empleado-del-mes') ?>?mes='+this.value">
                <?php 
                $meses = [];
                for ($i = 0; $i < 12; $i++) {
                    $meses[] = date('Y-m', strtotime("-$i months"));
                }
                foreach ($meses as $m): ?>
                    <option value="<?= $m ?>" <?= $mes_seleccionado == $m ? 'selected' : '' ?>><?= date('F Y', strtotime($m . '-01')) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Empleado del Mes Actual -->
    <?php if ($empleado_mes): ?>
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning">
            <h4 class="mb-0"><i class="bi bi-trophy"></i> 🏆 Empleado del Mes <?= date('F Y', strtotime($mes_seleccionado . '-01')) ?></h4>
        </div>
        <div class="card-body text-center">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="display-1">👤</div>
                </div>
                <div class="col-md-8">
                    <h3><?= $empleado_mes['primer_nombre'] ?> <?= $empleado_mes['primer_apellido'] ?></h3>
                    <p class="text-muted">Cédula: <?= $empleado_mes['cedula'] ?></p>
                    <div class="row mt-3">
                        <div class="col-6 col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="mb-0"><?= $empleado_mes['puntuacion'] ?? 'N/A' ?></h5>
                                    <small>Puntuación</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="mb-0"><?= $empleado_mes['asistencia'] ?? 'N/A' ?>%</h5>
                                    <small>Asistencia</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="mb-0"><?= $empleado_mes['puntualidad'] ?? 'N/A' ?>%</h5>
                                    <small>Puntualidad</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="mb-0"><?= $empleado_mes['iniciativa'] ?? 'N/A' ?>%</h5>
                                    <small>Iniciativa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> No se ha seleccionado un empleado del mes para <?= date('F Y', strtotime($mes_seleccionado . '-01')) ?>
    </div>
    <?php endif; ?>

    <!-- Candidatos -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Candidatos del Mes</h5>
        </div>
        <div class="card-body">
            <?php if (empty($candidatos)): ?>
                <p class="text-muted">No hay evaluaciones registradas para este mes</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Puntuación</th>
                                <th>Asistencia</th>
                                <th>Puntualidad</th>
                                <th>Trabajo en Equipo</th>
                                <th>Iniciativa</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatos as $index => $cand): ?>
                            <tr class="<?= $index == 0 && $empleado_mes ? 'table-warning' : '' ?>">
                                <td><?= $index + 1 ?></td>
                                <td><?= $cand['primer_nombre'] ?> <?= $cand['primer_apellido'] ?></td>
                                <td><?= $cand['cedula'] ?></td>
                                <td><strong><?= $cand['puntuacion'] ?? 'N/A' ?></strong></td>
                                <td><?= $cand['asistencia'] ?? 'N/A' ?>%</td>
                                <td><?= $cand['puntualidad'] ?? 'N/A' ?>%</td>
                                <td><?= $cand['trabajo_equipo'] ?? 'N/A' ?>%</td>
                                <td><?= $cand['iniciativa'] ?? 'N/A' ?>%</td>
                                <td>
                                    <?php if ($index == 0 && !$empleado_mes): ?>
                                        <form action="<?= base_url('/evaluaciones/seleccionar-empleado/' . $cand['id']) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="mes" value="<?= $mes_seleccionado ?>">
                                            <button type="submit" class="btn btn-sm btn-warning">
                                                <i class="bi bi-trophy"></i> Seleccionar
                                            </button>
                                        </form>
                                    <?php endif; ?>
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
