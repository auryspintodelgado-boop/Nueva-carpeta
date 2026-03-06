<?php
/**
 * Vista Índice de Seguimientos
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

$title = 'Seguimientos';
$active = 'seguimientos';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1">Seguimientos class="h2</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/seguimientos/create" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Seguimiento
        </a>
    </div>
</div>

<?php if (isset($message) && $message['message']): ?>
    <div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
        <?php echo htmlspecialchars($message['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/seguimientos" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="Pendiente" <?php echo ($estado ?? '') == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                    <option value="En_Proceso" <?php echo ($estado ?? '') == 'En_Proceso' ? 'selected' : ''; ?>>En Proceso</option>
                    <option value="Completado" <?php echo ($estado ?? '') == 'Completado' ? 'selected' : ''; ?>>Completado</option>
                    <option value="Cancelado" <?php echo ($estado ?? '') == 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
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
                <th>Fecha</th>
                <th>Persona</th>
                <th>Tipo</th>
                <th>Responsable</th>
                <th>Próxima Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($seguimientos)): ?>
                <tr>
                    <td colspan="8" class="text-center py-4">No hay seguimientos registrados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($seguimientos as $seg): ?>
                <tr>
                    <td><?php echo htmlspecialchars($seg['id']); ?></td>
                    <td><?php echo htmlspecialchars($seg['fecha_seguimiento']); ?></td>
                    <td><?php echo htmlspecialchars($seg['persona_id']); ?></td>
                    <td><?php echo htmlspecialchars($seg['tipo_seguimiento_id']); ?></td>
                    <td><?php echo htmlspecialchars($seg['responsable'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($seg['proxima_fecha'] ?? 'N/A'); ?></td>
                    <td>
                        <?php 
                        $badgeClass = match($seg['estado_seguimiento'] ?? 'Pendiente') {
                            'Pendiente' => 'warning',
                            'En_Proceso' => 'info',
                            'Completado' => 'success',
                            'Cancelado' => 'danger',
                            default => 'secondary'
                        };
                        ?>
                        <span class="badge bg-<?php echo $badgeClass; ?>">
                            <?php echo htmlspecialchars(str_replace('_', ' ', $seg['estado_seguimiento'] ?? 'Pendiente')); ?>
                        </span>
                    </td>
                    <td>
                        <a href="/seguimientos/show/<?php echo $seg['id']; ?>" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="/seguimientos/edit/<?php echo $seg['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="/seguimientos/delete/<?php echo $seg['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar este seguimiento?');">
                            <i class="bi bi-trash"></i>
                        </a>
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
            <a class="page-link" href="/seguimientos?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>
