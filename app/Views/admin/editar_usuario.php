<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-pencil"></i> Editar Usuario</h2>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle"></i> Por favor corrija los errores
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?= form_open('/admin/usuarios/editar/' . $usuario['id']) ?>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Usuario *</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $usuario['username']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Dejar vacío para mantener">
                        <small class="text-muted">Solo complete si desea cambiar la contraseña</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre_completo" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= old('nombre_completo', $usuario['nombre_completo']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $usuario['email']) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="rol" class="form-label">Rol *</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="ADMIN" <?= $usuario['rol'] === 'ADMIN' ? 'selected' : '' ?>>Administrador</option>
                            <option value="DIRECTOR" <?= $usuario['rol'] === 'DIRECTOR' ? 'selected' : '' ?>>Director</option>
                            <option value="EVALUADOR" <?= $usuario['rol'] === 'EVALUADOR' ? 'selected' : '' ?>>Evaluador</option>
                            <option value="CONSULTA" <?= $usuario['rol'] === 'CONSULTA' ? 'selected' : '' ?>>Consulta</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="departamento_id" class="form-label">Departamento</label>
                        <select class="form-select" id="departamento_id" name="departamento_id">
                            <option value="">Seleccionar...</option>
                            <?php if (!empty($departamentos)): ?>
                                <?php foreach ($departamentos as $dept): ?>
                                    <option value="<?= $dept['id'] ?>" <?= (int)($usuario['departamento_id'] ?? 0) == (int)$dept['id'] ? 'selected' : '' ?>>
                                        <?= $dept['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="estado" class="form-label">Estado *</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="ACTIVO" <?= $usuario['estado'] === 'ACTIVO' ? 'selected' : '' ?>>Activo</option>
                            <option value="INACTIVO" <?= $usuario['estado'] === 'INACTIVO' ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Guardar Cambios
                    </button>
                    <a href="<?= base_url('/admin/usuarios') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
