<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalle de Persona</h2>
        <div>
            <a href="<?= base_url('/personas/edit/' . $persona['id']) ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <a href="<?= base_url('/personas') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Foto de Perfil -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <?php
                    $fotoPath = null;
                    $debugInfo = '';
                    if (!empty($persona['foto'])) {
                        $debugInfo = 'Foto en BD: ' . $persona['foto'];
                        $fullPath = ROOTPATH . 'public/uploads/fotos/' . $persona['foto'];
                        $debugInfo .= ' | Existe: ' . (file_exists($fullPath) ? 'SI' : 'NO');
                        if (file_exists($fullPath)) {
                            $fotoPath = base_url('uploads/fotos/' . $persona['foto']);
                        }
                    }
                    ?>
                    <?php if ($debugInfo): ?>
                        <small class="text-muted d-block mb-2"><?= $debugInfo ?></small>
                    <?php endif; ?>
                    <?php if ($fotoPath): ?>
                        <img src="<?= $fotoPath ?>" alt="Foto de perfil" class="img-thumbnail mb-3" style="max-width: 200px; max-height: 200px;">
                    <?php else: ?>
                        <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px;">
                            <i class="bi bi-person" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/personas/upload-foto/' . $persona['id']) ?>" method="post" enctype="multipart/form-data" id="uploadForm">
                        <?= csrf_field() ?>
                        <div class="mb-2">
                            <input type="file" name="foto" id="foto" accept="image/*" class="form-control" onchange="document.getElementById('uploadForm').submit()">
                        </div>
                    </form>

                    <?php if (!empty($persona['foto'])): ?>
                        <form action="<?= base_url('/personas/delete-foto/' . $persona['id']) ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta foto?')">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    <?php endif; ?>

                    <small class="text-muted d-block mt-2">Máx 2MB (JPG, PNG, GIF)</small>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Datos Personales -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Datos Personales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3"><strong>Cédula:</strong> <?= $persona['cedula'] ?></div>
                        <div class="col-md-3"><strong>Nombre:</strong> <?= $persona['primer_nombre'] . ' ' . $persona['segundo_nombre'] ?></div>
                        <div class="col-md-3"><strong>Apellido:</strong> <?= $persona['primer_apellido'] . ' ' . $persona['segundo_apellido'] ?></div>
                        <div class="col-md-3"><strong>Sexo:</strong> <?= $persona['sexo'] ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3"><strong>Fecha Nac.:</strong> <?= $persona['fecha_nacimiento'] ?></div>
                        <div class="col-md-3"><strong>Edad:</strong> <?= $persona['edad'] ?></div>
                        <div class="col-md-3"><strong>Teléfono:</strong> <?= $persona['telefono1'] ?></div>
                        <div class="col-md-3"><strong>Correo:</strong> <?= $persona['correo_electronico'] ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3"><strong>Estado Civil:</strong> <?= $persona['estado_civil'] ?></div>
                        <div class="col-md-3"><strong>Hijos:</strong> <?= $persona['tiene_hijos'] === 'S' ? 'Sí (' . $persona['cantidad_hijos'] . ')' : 'No' ?></div>
                        <div class="col-md-3"><strong>Carga Familiar:</strong> <?= $persona['carga_familiar'] ?></div>
                    </div>
                </div>
            </div>

            <!-- Datos Académicos -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Datos Académicos</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>Universidad:</strong> <?= $persona['siglas_universidad'] ?></div>
                <div class="col-md-3"><strong>Tipo:</strong> <?= $persona['tipo_ieu'] ?></div>
                <div class="col-md-3"><strong>Nivel:</strong> <?= $persona['nivel_academico'] ?></div>
                <div class="col-md-3"><strong>Carrera:</strong> <?= $persona['carrera'] ?></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3"><strong>Período:</strong> <?= $persona['ano_semestre'] ?></div>
                <div class="col-md-3"><strong>Sede:</strong> <?= $persona['sede'] ?></div>
                <div class="col-md-3"><strong>Beca:</strong> <?= $persona['posee_beca'] === 'S' ? 'Sí' : 'No' ?></div>
                <div class="col-md-3"><strong>Estado:</strong> <?= $persona['estado'] ?></div>
            </div>
        </div>
    </div>

    <!-- Ubicación -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Ubicación</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><strong>Urbanismo:</strong> <?= $persona['urbanismo'] ?></div>
                <div class="col-md-3"><strong>Municipio:</strong> <?= $persona['municipio'] ?></div>
                <div class="col-md-3"><strong>Parroquia:</strong> <?= $persona['parroquia'] ?></div>
                <div class="col-md-3"><strong>Comuna:</strong> <?= $persona['comuna'] ?></div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <a href="<?= base_url('/evaluaciones/create?persona_id=' . $persona['id']) ?>" class="btn btn-outline-primary w-100">
                <i class="bi bi-clipboard-check"></i> Nueva Evaluación
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?= base_url('/seguimientos/create?persona_id=' . $persona['id']) ?>" class="btn btn-outline-success w-100">
                <i class="bi bi-journal-plus"></i> Nuevo Seguimiento
            </a>
        </div>
        <div class="col-md-4">
            <form action="<?= base_url('/personas/delete/' . $persona['id']) ?>" method="post" onsubmit="return confirm('¿Está seguro de eliminar esta persona?')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-danger w-100">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>

    <!-- Evaluaciones -->
    <div class="card mb-4">
        <div class="card-header bg-warning">
            <h5 class="mb-0">Historial de Evaluaciones</h5>
        </div>
        <div class="card-body">
            <?php if (empty($evaluaciones)): ?>
                <p class="text-muted">No hay evaluaciones registradas</p>
            <?php else: ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Resultado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluaciones as $eval): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($eval['fecha_evaluacion'])) ?></td>
                            <td><?= $eval['tipo_evaluacion'] ?></td>
                            <td><?= substr($eval['resultado'] ?? '', 0, 50) ?>...</td>
                            <td>
                                <a href="<?= base_url('/evaluaciones/show/' . $eval['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Seguimientos -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Historial de Seguimientos</h5>
        </div>
        <div class="card-body">
            <?php if (empty($seguimientos)): ?>
                <p class="text-muted">No hay seguimientos registrados</p>
            <?php else: ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Prioridad</th>
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
                                <span class="badge bg-<?= $seg['prioridad'] == 'URGENTE' ? 'danger' : 'secondary' ?>">
                                    <?= $seg['prioridad'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('/seguimientos/show/' . $seg['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
