<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-gear"></i> Panel de Administración</h2>
        <span class="badge bg-primary">ADMIN</span>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people"></i> Usuarios</h5>
                    <h2 class="mb-0"><?= $totalUsuarios ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-building"></i> Departamentos</h5>
                    <h2 class="mb-0"><?= $totalDepartamentos ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-person-badge"></i> Personas</h5>
                    <h2 class="mb-0"><?= $totalPersonas ?? 0 ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Menú de opciones -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Gestión de Usuarios</h5>
                </div>
                <div class="card-body">
                    <p>Administrar usuarios del sistema, asignar roles y departamentos.</p>
                    <a href="<?= base_url('/admin/usuarios') ?>" class="btn btn-primary">
                        <i class="bi bi-arrow-right"></i> Ir a Usuarios
                    </a>
                    <a href="<?= base_url('/admin/usuarios/crear') ?>" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Nuevo Usuario
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Gestión de Departamentos</h5>
                </div>
                <div class="card-body">
                    <p>Crear y administrar departamentos de la organización.</p>
                    <a href="<?= base_url('/admin/departamentos') ?>" class="btn btn-success">
                        <i class="bi bi-arrow-right"></i> Ir a Departamentos
                    </a>
                    <a href="<?= base_url('/admin/departamentos/crear') ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Departamento
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos usuarios creados -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Últimos Usuarios</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($usuarios)): ?>
                                    <?php foreach (array_slice($usuarios, 0, 5) as $usr): ?>
                                        <tr>
                                            <td><?= $usr['username'] ?></td>
                                            <td><?= $usr['nombre_completo'] ?></td>
                                            <td><span class="badge bg-<?= $usr['rol'] === 'ADMIN' ? 'danger' : ($usr['rol'] === 'DIRECTOR' ? 'warning' : 'info') ?>"><?= $usr['rol'] ?></span></td>
                                            <td><span class="badge bg-<?= $usr['estado'] === 'ACTIVO' ? 'success' : 'secondary' ?>"><?= $usr['estado'] ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No hay usuarios</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
