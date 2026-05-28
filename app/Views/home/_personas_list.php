<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h5>Personas con <?= $filter_name ?>: <span class="badge bg-primary"><?= htmlspecialchars($filter_value) ?></span></h5>
            <p class="text-muted">Total: <?= count($personas) ?> persona(s)</p>
        </div>
    </div>

    <?php if (empty($personas)): ?>
        <div class="alert alert-info">
            No se encontraron personas con este criterio.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cédula</th>
                        <th>Nombre Completo</th>
                        <th>Sexo</th>
                        <th>Edad</th>
                        <th>Carrera</th>
                        <th>Universidad</th>
                        <th>Departamento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($personas as $index => $p): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($p['cedula'] ?? 'N/A') ?></td>
                        <td>
                            <?php
                            $nombreCompleto = trim(($p['primer_nombre'] ?? '') . ' ' . 
                                           ($p['segundo_nombre'] ?? '') . ' ' . 
                                           ($p['primer_apellido'] ?? '') . ' ' . 
                                           ($p['segundo_apellido'] ?? ''));
                            echo htmlspecialchars($nombreCompleto);
                            ?>
                        </td>
                        <td>
                            <?php if (($p['sexo'] ?? '') === 'M'): ?>
                                <span class="badge bg-info">Masculino</span>
                            <?php elseif (($p['sexo'] ?? '') === 'F'): ?>
                                <span class="badge bg-pink">Femenino</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                            if (!empty($p['fecha_nacimiento'])) {
                                $edad = (new \DateTime($p['fecha_nacimiento']))->diff(new \DateTime())->y;
                                echo $edad . ' años';
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td><?= htmlspecialchars($p['carrera'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($p['siglas_universidad'] ?? 'N/A') ?></td>
                        <td>
                            <?php 
                            if (!empty($p['departamento_id'])) {
                                // Buscar nombre del departamento (se podría optimizar con un join)
                                echo htmlspecialchars($p['departamento_id']);
                            } else {
                                echo '<span class="text-muted">Sin asignar</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="<?= base_url('/personas/show/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">
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
