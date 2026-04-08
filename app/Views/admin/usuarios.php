<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> Gestión de Usuarios</h2>
        <a href="<?= base_url('/admin/usuarios/crear') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Departamento</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usuarios)): ?>
                            <?php foreach ($usuarios as $usr): ?>
                                <tr>
                                    <td><?= $usr['id'] ?></td>
                                    <td><strong><?= $usr['username'] ?></strong></td>
                                    <td><?= $usr['nombre_completo'] ?></td>
                                    <td><?= $usr['email'] ?></td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $usr['rol'] === 'ADMIN' ? 'danger' : 
                                            ($usr['rol'] === 'DIRECTOR' ? 'warning' : 
                                            ($usr['rol'] === 'EVALUADOR' ? 'info' : 'secondary')) 
                                        ?>">
                                            <?= $usr['rol'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $deptNombre = 'Sin asignar';
                                        if (!empty($usr['departamento_id']) && !empty($departamentos)) {
                                            foreach ($departamentos as $dept) {
                                                if ($dept['id'] == $usr['departamento_id'] || (int)$dept['id'] === (int)$usr['departamento_id']) {
                                                    $deptNombre = $dept['nombre'];
                                                    break;
                                                }
                                            }
                                        }
                                        echo $deptNombre;
                                        ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $usr['estado'] === 'ACTIVO' ? 'success' : 'secondary' ?>">
                                            <?= $usr['estado'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('/admin/usuarios/editar/' . $usr['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= base_url('/admin/usuarios/eliminar/' . $usr['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay usuarios registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?= base_url('/admin') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>
</div>
<?= $this->endSection() ?>
