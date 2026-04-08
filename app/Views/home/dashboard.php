<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <h2 class="mb-4">Dashboard</h2>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-stat bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Personas</h6>
                            <h2 class="mb-0"><?= $stats['personas_total'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= base_url('/personas') ?>" class="text-white text-decoration-none">
                        Ver todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stat bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Personas Activas</h6>
                            <h2 class="mb-0"><?= $stats['personas_activas'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-person-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stat bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Evaluaciones</h6>
                            <h2 class="mb-0"><?= $stats['evaluaciones'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-clipboard-check fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= base_url('/evaluaciones') ?>" class="text-white text-decoration-none">
                        Ver todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-stat bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Seguimientos</h6>
                            <h2 class="mb-0"><?= $stats['seguimientos'] ?? 0 ?></h2>
                        </div>
                        <i class="bi bi-journal-text fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= base_url('/seguimientos') ?>" class="text-white text-decoration-none">
                        Ver todos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <!-- Gráfico de Personal por Departamento -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Personal por Departamento</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartDepartamentos"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Evaluaciones por Mes -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-line-chart"></i> Evaluaciones por Mes</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartEvaluaciones"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Gráfico de Género -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Distribución por Género</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartGenero"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Discapacidad -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Personas con Discapacidad</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartDiscapacidad"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Estado de Seguimientos -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Estado de Seguimientos</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartSeguimientos"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-exclamation-circle"></i> Pendientes</h5>
                </div>
                <div class="card-body">
                    <h3 class="text-warning"><?= $stats['pendientes'] ?? 0 ?></h3>
                    <p class="text-muted">Seguimientos pendientes</p>
                    <a href="<?= base_url('/seguimientos/pendientes') ?>" class="btn btn-warning btn-sm">
                        Ver pendientes
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Próximos</h5>
                </div>
                <div class="card-body">
                    <h3 class="text-info"><?= $stats['proximos'] ?? 0 ?></h3>
                    <p class="text-muted">Seguimientos en los próximos 7 días</p>
                    <a href="<?= base_url('/seguimientos/proximos') ?>" class="btn btn-info btn-sm">
                        Ver próximos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Seguimientos Recientes -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Seguimientos Recientes</h5>
        </div>
        <div class="card-body">
            <?php if (empty($seguimientos_recientes)): ?>
                <p class="text-muted">No hay seguimientos recientes</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
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
                            <?php foreach ($seguimientos_recientes as $seg): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($seg['fecha_seguimiento'])) ?></td>
                                <td><?= $seg['tipo_seguimiento'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $seg['estado'] == 'COMPLETADO' ? 'success' : ($seg['estado'] == 'PENDIENTE' ? 'warning' : 'primary') ?>">
                                        <?= $seg['estado'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $seg['prioridad'] == 'URGENTE' ? 'danger' : ($seg['prioridad'] == 'ALTA' ? 'warning' : 'secondary') ?>">
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
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de Personal por Departamento
const ctxDept = document.getElementById('chartDepartamentos').getContext('2d');
new Chart(ctxDept, {
    type: 'bar',
    data: {
        labels: <?= json_encode($chart_data['labels_dept'] ?? []) ?>,
        datasets: [{
            label: 'Personal',
            data: <?= json_encode($chart_data['data_dept'] ?? []) ?>,
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)',
                'rgba(255, 159, 64, 0.7)',
                'rgba(199, 199, 199, 0.7)',
                'rgba(83, 102, 255, 0.7)',
                'rgba(40, 159, 64, 0.7)',
                'rgba(210, 99, 132, 0.7)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
                'rgba(83, 102, 255, 1)',
                'rgba(40, 159, 64, 1)',
                'rgba(210, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Gráfico de Evaluaciones por Mes
const ctxEval = document.getElementById('chartEvaluaciones').getContext('2d');
new Chart(ctxEval, {
    type: 'line',
    data: {
        labels: <?= json_encode($chart_data['labels_meses'] ?? []) ?>,
        datasets: [{
            label: 'Evaluaciones',
            data: <?= json_encode($chart_data['data_evaluaciones'] ?? []) ?>,
            fill: true,
            backgroundColor: 'rgba(40, 167, 69, 0.2)',
            borderColor: 'rgba(40, 167, 69, 1)',
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Gráfico de Género
const ctxGenero = document.getElementById('chartGenero').getContext('2d');
new Chart(ctxGenero, {
    type: 'doughnut',
    data: {
        labels: ['Masculino', 'Femenino'],
        datasets: [{
            data: [<?= $chart_data['hombres'] ?? 0 ?>, <?= $chart_data['mujeres'] ?? 0 ?>],
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Gráfico de Discapacidad
const ctxDiscapacidad = document.getElementById('chartDiscapacidad').getContext('2d');
new Chart(ctxDiscapacidad, {
    type: 'doughnut',
    data: {
        labels: ['Con Discapacidad', 'Sin Discapacidad'],
        datasets: [{
            data: [<?= $chart_data['con_discapacidad'] ?? 0 ?>, <?= $chart_data['sin_discapacidad'] ?? 0 ?>],
            backgroundColor: [
                'rgba(255, 193, 7, 0.7)',
                'rgba(108, 117, 125, 0.7)'
            ],
            borderColor: [
                'rgba(255, 193, 7, 1)',
                'rgba(108, 117, 125, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Gráfico de Estado de Seguimientos
const ctxSeg = document.getElementById('chartSeguimientos').getContext('2d');
new Chart(ctxSeg, {
    type: 'doughnut',
    data: {
        labels: ['Pendientes', 'En Proceso', 'Completados'],
        datasets: [{
            data: [
                <?= $chart_data['seg_pendientes'] ?? 0 ?>, 
                <?= $chart_data['seg_en_proceso'] ?? 0 ?>, 
                <?= $chart_data['seg_completados'] ?? 0 ?>
            ],
            backgroundColor: [
                'rgba(255, 193, 7, 0.7)',
                'rgba(23, 162, 184, 0.7)',
                'rgba(40, 167, 69, 0.7)'
            ],
            borderColor: [
                'rgba(255, 193, 7, 1)',
                'rgba(23, 162, 184, 1)',
                'rgba(40, 167, 69, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
<?= $this->endSection() ?>
