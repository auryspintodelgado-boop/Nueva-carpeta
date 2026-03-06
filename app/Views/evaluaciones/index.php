<?php
/**
 * Vista Índice de Evaluaciones
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

// Incluir el layout si no está incluido ya
if (!defined('LAYOUT_INCLUDED')) {
    $title = 'Evaluaciones';
    $active = 'evaluaciones';
    ob_start();
}
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Evaluaciones</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/evaluaciones/create" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Nueva Evaluación
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
        <form method="GET" action="/evaluaciones" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    <?php foreach ($tiposEvaluacion as $tipo): ?>
                        <option value="<?php echo $tipo['id']; ?>" <?php echo ($tipo ?? '') == $tipo['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tipo['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Buscar</button>
            </div>
        </form>
   </div>

<div class </div>
="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Persona</th>
                <th>Tipo</th>
                <th>Puntaje</th>
                <th>Resultado</th>
                <th>Evaluador</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($evaluaciones)): ?>
                <tr>
                    <td colspan="8" class="text-center py-4">No hay evaluaciones registradas</td>
                </tr>
            <?php else: ?>
                <?php foreach ($evaluaciones as $eval): ?>
                <tr>
                    <td><?php echo htmlspecialchars($eval['id']); ?></td>
                    <td><?php echo htmlspecialchars($eval['fecha_evaluacion']); ?></td>
                    <td><?php echo htmlspecialchars($eval['persona_id']); ?></td>
                    <td><?php echo htmlspecialchars($eval['tipo_evaluacion_id']); ?></td>
                    <td><?php echo htmlspecialchars($eval['puntaje'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($eval['resultado'] ?? 'Pendiente'); ?></td>
                    <td><?php echo htmlspecialchars($eval['evaluador'] ?? 'N/A'); ?></td>
                    <td>
                        <a href="/evaluaciones/show/<?php echo $eval['id']; ?>" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="/evaluaciones/edit/<?php echo $eval['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="/evaluaciones/delete/<?php echo $eval['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta evaluación?');">
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
            <a class="page-link" href="/evaluaciones?page=<?php echo $i; ?>"><?php echo $i; ?></a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php
if (!defined('LAYOUT_INCLUDED')) {
    $content = ob_get_clean();
    include __DIR__ . '/../layout.php';
}
?>
