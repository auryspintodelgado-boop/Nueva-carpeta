<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Personas</h2>
        <a href="<?= base_url('/personas/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Persona
        </a>
    </div>

    <!-- Buscador -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" action="<?= base_url('/personas') ?>" class="d-flex gap-2">
                <input type="text" name="q" class="form-control" 
                       placeholder="Buscar por nombre, apellido o cédula..."
                       value="<?= $busqueda ?? '' ?>">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <?php if ($busqueda): ?>
                    <a href="<?= base_url('/personas') ?>" class="btn btn-outline-secondary">
                        Limpiar
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tabla de Personas -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($personas)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-people fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No hay personas registradas</p>
                    <a href="<?= base_url('/personas/create') ?>" class="btn btn-primary">
                        Registrar primera persona
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre Completo</th>
                                <th>Teléfono</th>
                                <th>Correo</th>
                                <th>Departamento</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($personas as $p): ?>
                            <tr>
                                <td><?= $p['cedula'] ?></td>
                                <td><?= $p['primer_nombre'] . ' ' . $p['primer_apellido'] ?></td>
                                <td><?= $p['telefono1'] ?? '-' ?></td>
                                <td><?= $p['correo_electronico'] ?? '-' ?></td>
                                <td>
                                    <?php 
                                    $deptNombre = 'Sin asignar';
                                    $deptBadge = 'warning';
                                    if (!empty($p['departamento_id']) && !empty($departamentos)) {
                                        foreach ($departamentos as $dept) {
                                    if ($dept['id'] == $p['departamento_id'] || (int)$dept['id'] === (int)$p['departamento_id']) {
                                                $deptNombre = $dept['nombre'];
                                                $deptBadge = 'info';
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <span class="badge bg-<?= $deptBadge ?>"><?= $deptNombre ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $p['estado_registro'] == 'ACTIVO' ? 'success' : 'secondary' ?>">
                                        <?= $p['estado_registro'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('/personas/show/' . $p['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('/personas/edit/' . $p['id']) ?>" 
                                           class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('/evaluaciones/create?persona_id=' . $p['id']) ?>" 
                                           class="btn btn-sm btn-outline-info" title="Evaluar">
                                            <i class="bi bi-clipboard-check"></i>
                                        </a>
                                        <a href="<?= base_url('/seguimientos/create?persona_id=' . $p['id']) ?>" 
                                           class="btn btn-sm btn-outline-success" title="Seguimiento">
                                            <i class="bi bi-journal-plus"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <?= $pager->links() ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
