<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $title ?></h2>
        <div>
            <a href="<?= base_url('/reportes') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="<?= base_url('/reportes/estadisticas') ?>" method="get" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Departamento</label>
                    <select name="departamento" class="form-select">
                        <option value="">Todos los departamentos</option>
                        <?php foreach ($departamentos as $dept): ?>
                            <option value="<?= $dept['id'] ?>" <?= (isset($selectedDepartamento) && $selectedDepartamento == $dept['id']) ? 'selected' : '' ?>>
                                <?= $dept['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Información de filtros aplicados -->
    <div class="alert alert-info mb-4">
        <strong><i class="bi bi-funnel"></i> Filtros activos:</strong>
        <span class="badge bg-primary"><?= $filtros['departamento'] ?? 'Todos' ?></span>
        <span class="ms-2">|</span>
        <span class="ms-2"><strong>Total personas:</strong> <?= $totalPersonas ?></span>
    </div>

    <!-- Gráfico de Tipo de Sangre -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-droplet"></i> Distribución por Tipo de Sangre</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($tiposSangre) && $totalPersonas > 0): ?>
                        <div style="position: relative; height: 300px; width: 100%;">
                            <canvas id="graficoTipoSangre"></canvas>
                        </div>
                        
                        <!-- Tabla de datos -->
                        <div class="mt-4">
                            <h6>Detalles:</h6>
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Tipo de Sangre</th>
                                        <th>Cantidad</th>
                                        <th>Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tiposSangre as $tipo => $cantidad): ?>
                                    <tr>
                                        <td>
                                            <span class="badge" style="background-color: <?= $colores[$tipo] ?? '#6c757d' ?>">
                                                <?= $tipo ?>
                                            </span>
                                        </td>
                                        <td><?= $cantidad ?></td>
                                        <td><?= round(($cantidad / $totalPersonas) * 100, 1) ?>%</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No hay datos disponibles</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Resumen de Tipos de Sangre -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Resumen de Tipos de Sangre</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($tiposSangre) && $totalPersonas > 0): ?>
                    <div class="row text-center">
                        <?php 
                        $tiposOrdenados = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'No definido'];
                        foreach ($tiposOrdenados as $tipo): 
                            if (isset($tiposSangre[$tipo])):
                        ?>
                        <div class="col-4 col-sm-3 mb-3">
                            <div class="card bg-light">
                                <div class="card-body p-2">
                                    <h4 class="mb-0" style="color: <?= $colores[$tipo] ?? '#6c757d' ?>">
                                        <?= $tiposSangre[$tipo] ?>
                                    </h4>
                                    <small class="text-muted"><?= $tipo ?></small>
                                </div>
                            </div>
                        </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                    
                    <!-- Leyenda -->
                    <div class="mt-4">
                        <h6>Leyenda de Colores:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($colores as $tipo => $color): ?>
                                <div class="d-flex align-items-center">
                                    <span class="badge me-1" style="background-color: <?= $color ?>"><?= $tipo ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                        <p class="text-muted">No hay datos disponibles</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (!empty($tiposSangre) && $totalPersonas > 0): ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var canvas = document.getElementById('graficoTipoSangre');
    if (canvas) {
        var ctx = canvas.getContext('2d');
        
        // Preparar datos para el gráfico
        var labels = <?= json_encode(array_keys($tiposSangre)) ?>;
        var data = <?= json_encode(array_values($tiposSangre)) ?>;
        var colors = <?= json_encode(array_map(function($tipo) use ($colores) { 
            return $colores[$tipo] ?? '#6c757d'; 
        }, array_keys($tiposSangre))) ?>;

        // Crear gráfico
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 15
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Personas por Tipo de Sangre',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                var percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>
