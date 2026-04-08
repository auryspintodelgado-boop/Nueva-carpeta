<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $title ?></h2>
        <div>
            <a href="<?= base_url('/reportes') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="<?= base_url('/reportes/personas/excel') ?>" class="btn btn-success" target="_blank">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
            <a href="<?= base_url('/reportes/personas/pdf') ?>" class="btn btn-danger" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($personas)): ?>
                <p class="text-muted">No hay personas registradas</p>
            <?php else: ?>
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-striped table-hover" style="font-size: 11px; white-space: nowrap;">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Sexo</th>
                                <th>F. Nac.</th>
                                <th>Edad</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Beca</th>
                                <th>Sede</th>
                                <th>Universidad</th>
                                <th>Carrera</th>
                                <th>Municipio</th>
                                <th>Parroquia</th>
                                <th>Hijos</th>
                                <th>Discapacidad</th>
                                <th>Trabaja</th>
                                <th>Tipo Sangre</th>
                                <th>Estado Civil</th>
                                <th>Depto.</th>
                                <th>F. Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($personas as $p): ?>
                            <tr>
                                <td><?= $p['numero'] ?? '' ?></td>
                                <td><?= $p['cedula'] ?? '' ?></td>
                                <td><?= $p['primer_nombre'] ?? '' ?> <?= $p['segundo_nombre'] ?? '' ?></td>
                                <td><?= $p['primer_apellido'] ?? '' ?> <?= $p['segundo_apellido'] ?? '' ?></td>
                                <td><?= $p['sexo'] ?? '' ?></td>
                                <td><?= $p['fecha_nacimiento'] ?? '' ?></td>
                                <td><?= $p['edad'] ?? '' ?></td>
                                <td><?= $p['telefono1'] ?? '' ?></td>
                                <td><?= $p['correo_electronico'] ?? '' ?></td>
                                <td><?= $p['posee_beca'] ?? '' ?></td>
                                <td><?= $p['sede'] ?? '' ?></td>
                                <td><?= $p['siglas_universidad'] ?? '' ?></td>
                                <td><?= substr($p['carrera'] ?? '', 0, 20) ?></td>
                                <td><?= $p['municipio'] ?? '' ?></td>
                                <td><?= $p['parroquia'] ?? '' ?></td>
                                <td><?= $p['cantidad_hijos'] ?? '' ?></td>
                                <td><?= $p['posee_discapacidad'] ?? '' ?></td>
                                <td><?= $p['trabaja'] ?? '' ?></td>
                                <td><?= $p['tipo_sangre'] ?? '' ?></td>
                                <td><?= $p['estado_civil'] ?? '' ?></td>
                                <td><?= isset($p['departamento_id']) ? 'Sí' : '' ?></td>
                                <td><?= date('d/m/Y', strtotime($p['fecha_registro'] ?? 'now')) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted mt-2">Total: <?= count($personas) ?> personas</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
