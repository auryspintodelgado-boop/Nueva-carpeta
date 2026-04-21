<?php
/**
 * Vista Índice de Usuarios
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

$title = 'Usuarios';
$active = 'usuarios';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Usuarios</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/usuarios/create" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Usuario
        </a>
    </div>
</div>

<?php if (isset($message) && $message['message']): ?>
    <div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
        <?php echo htmlspecialchars($message['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/usuarios" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <select name="rol" class="form-select">
                    <option value="">Todos los roles</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?php echo $r['id']; ?>" <?php echo ($rol ?? '') == $r['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($r['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Buscar</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Último Acceso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($usuarios)): ?>
                <tr>
                    <td colspan="8" class="text-center py-4">No hay usuarios registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($usuarios as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['nombre_completo']); ?></td>
                    <td><?php echo htmlspecialchars($user['correo']); ?></td>
                    <td><?php echo htmlspecialchars($user['rol_nombre'] ?? 'N/A'); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $user['estado'] === 'Activo' ? 'success' : 'secondary'; ?>">
                            <?php echo htmlspecialchars($user['estado']); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($user['ultimo_acceso'] ?? 'Nunca'); ?></td>
                    <td>
                        <a href="/usuarios/show/<?php echo $user['id']; ?>" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="/usuarios/edit/<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <?php if ($user['id'] != ($_SESSION['usuario_id'] ?? 0)): ?>
                        <a href="/usuarios/delete/<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este usuario?');">
                            <i class="bi bi-trash"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<nav>
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
            <a class="page-link" href="/usuarios?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>
