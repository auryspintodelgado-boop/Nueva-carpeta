<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <h2 class="mb-4">Dashboard</h2>
    
    <div class="alert alert-info">
        <strong>Usuario actual:</strong> <?= session()->get('nombre_completo') ?> (<?= session()->get('username') ?>) - <strong>Rol:</strong> <?= session()->get('rol') ?> - <strong>ID:</strong> <?= session()->get('id') ?>
    </div>
    
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

    <!-- Gráficos Existentes -->
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

    <!-- Nuevos Gráficos - Fila 1 -->
    <h4 class="mb-3 mt-4">Estadísticas de Personas</h4>
    
    <div class="row mb-4">
        <!-- Estado Civil -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Estado Civil</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartEstadoCivil"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Edad -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Edad</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartEdad"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuevos Gráficos - Fila 2 -->
    <div class="row mb-4">
        <!-- Nacionalidad -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Nacionalidad</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartNacionalidad"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Estado Geográfico -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Estado (Geográfico)</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartEstadoGeo"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuevos Gráficos - Fila 3 -->
    <div class="row mb-4">
        <!-- Municipio -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Municipio</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartMunicipio"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Comuna -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Comuna</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartComuna"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Parroquia -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Parroquia</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartParroquia"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuevos Gráficos - Fila 4: Educación -->
    <h4 class="mb-3 mt-4">Estadísticas Educativas</h4>
    
    <div class="row mb-4">
        <!-- Nivel Académico -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Nivel Académico</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartNivelAcademico"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Beca -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> ¿Recibe Beca?</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartBeca"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuevos Gráficos - Fila 5: Universidad -->
    <div class="row mb-4">
        <!-- Tipo de Universidad -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Tipo de Universidad</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartTipoUniv"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Carrera -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Carreras (Top 10)</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartCarrera"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Universidad -->
    <div class="row mb-4">
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Universidades (Top 10)</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartUniversidad"></canvas>
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

<!-- Modal para mostrar lista de personas -->
<div class="modal fade" id="personasModal" tabindex="-1" aria-labelledby="personasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="personasModalLabel">Lista de Personas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="personasModalBody">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Almacenar todas las instancias de gráficos
window.charts = {};

// Función helper para preparar datos de objetos clave-valor
function prepararDatos(obj) {
    const labels = [];
    const data = [];
    for (const [key, value] of Object.entries(obj || {})) {
        labels.push(key);
        data.push(value);
    }
    return { labels, data };
}

// Función para cargar el modal con la lista de personas
function loadPersonas(filterType, filterValue) {
    const modalEl = document.getElementById('personasModal');
    const modal = new bootstrap.Modal(modalEl);
    const modalBody = document.getElementById('personasModalBody');
    
    // Mostrar spinner de carga
    modalBody.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando lista de personas...</p>
        </div>
    `;
    modal.show();
    
    // Construir URL
    const params = new URLSearchParams({
        filter_type: filterType,
        filter_value: filterValue
    });
    
    fetch(`<?= base_url('/home/personasByFilter') ?>?${params.toString()}`)
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta');
            return response.text();
        })
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los datos. Por favor intente nuevamente.</div>';
        });
}

// Función para configurar onClick en un gráfico
function configurarClick(chartId, filterType, valueMap = null) {
    const chart = window.charts[chartId];
    if (!chart) return;
    
    chart.options.onClick = function(evt, elements) {
        if (elements && elements.length > 0) {
            const index = elements[0].index;
            const label = chart.data.labels[index];
            const filterValue = valueMap ? valueMap(label) : label;
            if (filterValue) {
                loadPersonas(filterType, filterValue);
            }
        }
    };
    chart.update('none');
}

// === GRÁFICOS EXISTENTES ===

// Gráfico de Personal por Departamento
const ctxDept = document.getElementById('chartDepartamentos').getContext('2d');
window.charts['chartDepartamentos'] = new Chart(ctxDept, {
    type: 'bar',
    data: {
        labels: <?= json_encode($chart_data['labels_dept'] ?? []) ?>,
        datasets: [{
            label: 'Personal',
            data: <?= json_encode($chart_data['data_dept'] ?? []) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Gráfico de Evaluaciones por Mes
const ctxEval = document.getElementById('chartEvaluaciones').getContext('2d');
window.charts['chartEvaluaciones'] = new Chart(ctxEval, {
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
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
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
            backgroundColor: ['rgba(54, 162, 235, 0.7)', 'rgba(255, 99, 132, 0.7)'],
            borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
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
            backgroundColor: ['rgba(255, 193, 7, 0.7)', 'rgba(108, 117, 125, 0.7)'],
            borderColor: ['rgba(255, 193, 7, 1)', 'rgba(108, 117, 125, 1)'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Gráfico de Estado de Seguimientos
const ctxSeg = document.getElementById('chartSeguimientos').getContext('2d');
new Chart(ctxSeg, {
    type: 'doughnut',
    data: {
        labels: ['Pendientes', 'En Proceso', 'Completados'],
        datasets: [{
            data: [<?= $chart_data['seg_pendientes'] ?? 0 ?>, <?= $chart_data['seg_en_proceso'] ?? 0 ?>, <?= $chart_data['seg_completados'] ?? 0 ?>],
            backgroundColor: ['rgba(255, 193, 7, 0.7)', 'rgba(23, 162, 184, 0.7)', 'rgba(40, 167, 69, 0.7)'],
            borderColor: ['rgba(255, 193, 7, 1)', 'rgba(23, 162, 184, 1)', 'rgba(40, 167, 69, 1)'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// === NUEVOS GRÁFICOS ===

// Función helper para preparar datos de objetos clave-valor
function prepararDatos(obj) {
    const labels = [];
    const data = [];
    for (const [key, value] of Object.entries(obj || {})) {
        labels.push(key);
        data.push(value);
    }
    return { labels, data };
}

// Gráfico de Estado Civil
const estadoCivilData = prepararDatos(<?= json_encode($chart_data['estado_civil'] ?? []) ?>);
const ctxEstadoCivil = document.getElementById('chartEstadoCivil').getContext('2d');
new Chart(ctxEstadoCivil, {
    type: 'doughnut',
    data: {
        labels: estadoCivilData.labels,
        datasets: [{
            data: estadoCivilData.data,
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Gráfico de Edad
const edadData = prepararDatos(<?= json_encode($chart_data['edad'] ?? []) ?>);
const ctxEdad = document.getElementById('chartEdad').getContext('2d');
new Chart(ctxEdad, {
    type: 'bar',
    data: {
        labels: edadData.labels,
        datasets: [{
            label: 'Personas',
            data: edadData.data,
            backgroundColor: 'rgba(40, 167, 69, 0.7)',
            borderColor: 'rgba(40, 167, 69, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Gráfico de Nacionalidad
const nacionalidadData = prepararDatos(<?= json_encode($chart_data['nacionalidad'] ?? []) ?>);
const ctxNacionalidad = document.getElementById('chartNacionalidad').getContext('2d');
new Chart(ctxNacionalidad, {
    type: 'doughnut',
    data: {
        labels: nacionalidadData.labels,
        datasets: [{
            data: nacionalidadData.data,
            backgroundColor: [
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(255, 206, 86, 0.7)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Gráfico de Estado Geográfico
const estadoGeoData = prepararDatos(<?= json_encode($chart_data['estado_geo'] ?? []) ?>);
const ctxEstadoGeo = document.getElementById('chartEstadoGeo').getContext('2d');
new Chart(ctxEstadoGeo, {
    type: 'bar',
    data: {
        labels: estadoGeoData.labels,
        datasets: [{
            label: 'Personas',
            data: estadoGeoData.data,
            backgroundColor: 'rgba(255, 193, 7, 0.7)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Gráfico de Municipio
const municipioData = prepararDatos(<?= json_encode($chart_data['municipio'] ?? []) ?>);
const ctxMunicipio = document.getElementById('chartMunicipio').getContext('2d');
new Chart(ctxMunicipio, {
    type: 'bar',
    data: {
        labels: municipioData.labels,
        datasets: [{
            label: 'Personas',
            data: municipioData.data,
            backgroundColor: 'rgba(108, 117, 125, 0.7)',
            borderColor: 'rgba(108, 117, 125, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Gráfico de Comuna
const comunaData = prepararDatos(<?= json_encode($chart_data['comuna'] ?? []) ?>);
const ctxComuna = document.getElementById('chartComuna').getContext('2d');
new Chart(ctxComuna, {
    type: 'bar',
    data: {
        labels: comunaData.labels,
        datasets: [{
            label: 'Personas',
            data: comunaData.data,
            backgroundColor: 'rgba(33, 37, 41, 0.7)',
            borderColor: 'rgba(33, 37, 41, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Gráfico de Parroquia
const parroquiaData = prepararDatos(<?= json_encode($chart_data['parroquia'] ?? []) ?>);
const ctxParroquia = document.getElementById('chartParroquia').getContext('2d');
new Chart(ctxParroquia, {
    type: 'bar',
    data: {
        labels: parroquiaData.labels,
        datasets: [{
            label: 'Personas',
            data: parroquiaData.data,
            backgroundColor: 'rgba(0, 123, 255, 0.7)',
            borderColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Gráfico de Nivel Académico
const nivelAcadData = prepararDatos(<?= json_encode($chart_data['nivel_academico'] ?? []) ?>);
const ctxNivelAcad = document.getElementById('chartNivelAcademico').getContext('2d');
new Chart(ctxNivelAcad, {
    type: 'doughnut',
    data: {
        labels: nivelAcadData.labels,
        datasets: [{
            data: nivelAcadData.data,
            backgroundColor: [
                'rgba(40, 167, 69, 0.7)',
                'rgba(255, 193, 7, 0.7)',
                'rgba(54, 162, 235, 0.7)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Gráfico de Beca
const becaData = prepararDatos(<?= json_encode($chart_data['beca'] ?? []) ?>);
const ctxBeca = document.getElementById('chartBeca').getContext('2d');
new Chart(ctxBeca, {
    type: 'doughnut',
    data: {
        labels: becaData.labels,
        datasets: [{
            data: becaData.data,
            backgroundColor: [
                'rgba(23, 162, 184, 0.7)',
                'rgba(108, 117, 125, 0.7)',
                'rgba(255, 193, 7, 0.7)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Gráfico de Tipo de Universidad
const tipoUnivData = prepararDatos(<?= json_encode($chart_data['tipo_universidad'] ?? []) ?>);
const ctxTipoUniv = document.getElementById('chartTipoUniv').getContext('2d');
new Chart(ctxTipoUniv, {
    type: 'doughnut',
    data: {
        labels: tipoUnivData.labels,
        datasets: [{
            data: tipoUnivData.data,
            backgroundColor: [
                'rgba(255, 193, 7, 0.7)',
                'rgba(0, 123, 255, 0.7)',
                'rgba(108, 117, 125, 0.7)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Gráfico de Carrera (Top 10)
const carreraData = prepararDatos(<?= json_encode($chart_data['carrera'] ?? []) ?>);
const ctxCarrera = document.getElementById('chartCarrera').getContext('2d');
new Chart(ctxCarrera, {
    type: 'bar',
    data: {
        labels: carreraData.labels,
        datasets: [{
            label: 'Personas',
            data: carreraData.data,
            backgroundColor: 'rgba(108, 117, 125, 0.7)',
            borderColor: 'rgba(108, 117, 125, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Gráfico de Universidad (Top 10)
const universidadData = prepararDatos(<?= json_encode($chart_data['universidad'] ?? []) ?>);
const ctxUniversidad = document.getElementById('chartUniversidad').getContext('2d');
new Chart(ctxUniversidad, {
    type: 'bar',
    data: {
        labels: universidadData.labels,
        datasets: [{
            label: 'Personas',
            data: universidadData.data,
            backgroundColor: 'rgba(33, 37, 41, 0.7)',
            borderColor: 'rgba(33, 37, 41, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// === MANEJO DE CLICS EN GRÁFICOS ===

// Configuración de filtros por gráfico
const chartFilterConfig = {
    'chartGenero': {
        filterType: 'sexo',
        valueMap: (label) => label === 'Masculino' ? 'M' : 'F'
    },
    'chartDiscapacidad': {
        filterType: 'discapacidad',
        valueMap: (label) => label === 'Con Discapacidad' ? 'S' : 'N'
    },
    'chartEstadoCivil': {
        filterType: 'estado_civil',
        valueMap: null
    },
    'chartEdad': {
        filterType: 'edad',
        valueMap: null
    },
    'chartNacionalidad': {
        filterType: 'nacionalidad',
        valueMap: null
    },
    'chartEstadoGeo': {
        filterType: 'estado',
        valueMap: null
    },
    'chartMunicipio': {
        filterType: 'municipio',
        valueMap: null
    },
    'chartComuna': {
        filterType: 'comuna',
        valueMap: null
    },
    'chartParroquia': {
        filterType: 'parroquia',
        valueMap: null
    },
    'chartNivelAcademico': {
        filterType: 'nivel_academico',
        valueMap: null
    },
    'chartBeca': {
        filterType: 'beca',
        valueMap: (label) => label === 'Recibe beca' ? 'S' : (label === 'No recibe beca' ? 'N' : null)
    },
    'chartTipoUniv': {
        filterType: 'tipo_universidad',
        valueMap: (label) => label === 'Pública' ? 'PUBLICA' : (label === 'Privada' ? 'PRIVADA' : null)
    },
    'chartCarrera': {
        filterType: 'carrera',
        valueMap: null
    },
    'chartUniversidad': {
        filterType: 'universidad',
        valueMap: null
    }
};

// Función para cargar el modal con la lista de personas
function loadPersonas(filterType, filterValue) {
    const modalEl = document.getElementById('personasModal');
    const modal = new bootstrap.Modal(modalEl);
    const modalBody = document.getElementById('personasModalBody');
    
    // Mostrar spinner de carga
    modalBody.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-2">Cargando lista de personas...</p>
        </div>
    `;
    modal.show();
    
    // Construir URL
    const params = new URLSearchParams({
        filter_type: filterType,
        filter_value: filterValue
    });
    
    fetch(`<?= base_url('/home/personasByFilter') ?>?${params.toString()}`)
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta');
            return response.text();
        })
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los datos. Por favor intente nuevamente.</div>';
        });
}

// Adjuntar manejadores de clic a los gráficos
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que los gráficos se inicializen
    setTimeout(function() {
        // Obtener todos los canvas de gráficos
        document.querySelectorAll('canvas[id^="chart"]').forEach(canvas => {
            const chart = Chart.getChart(canvas);
            if (!chart) return;
            
            const canvasId = canvas.id;
            const config = chartFilterConfig[canvasId];
            if (config) {
                chart.options.onClick = function(evt, elements) {
                    if (elements && elements.length > 0) {
                        const index = elements[0].index;
                        const label = chart.data.labels[index];
                        const filterValue = config.valueMap ? config.valueMap(label) : label;
                        if (filterValue) {
                            loadPersonas(config.filterType, filterValue);
                        }
                    }
                };
                chart.update('none');
            }
        });
    }, 500);
});
// Configurar onClick para todos los gráficos
document.addEventListener('DOMContentLoaded', function() {
    // Esperar a que los gráficos se inicialicen
    setTimeout(function() {
        // Gráficos existentes
        configurarClick('chartGenero', 'sexo', (label) => label === 'Masculino' ? 'M' : 'F');
        configurarClick('chartDiscapacidad', 'discapacidad', (label) => label === 'Con Discapacidad' ? 'S' : 'N');
        configurarClick('chartSeguimientos', 'seguimiento_estado');
        
        // Nuevos gráficos
        configurarClick('chartEstadoCivil', 'estado_civil');
        configurarClick('chartEdad', 'edad');
        configurarClick('chartNacionalidad', 'nacionalidad');
        configurarClick('chartEstadoGeo', 'estado');
        configurarClick('chartMunicipio', 'municipio');
        configurarClick('chartComuna', 'comuna');
        configurarClick('chartParroquia', 'parroquia');
        configurarClick('chartNivelAcademico', 'nivel_academico');
        configurarClick('chartBeca', 'beca', (label) => label === 'Recibe beca' ? 'S' : (label === 'No recibe beca' ? 'N' : null));
        configurarClick('chartTipoUniv', 'tipo_universidad', (label) => label === 'Pública' ? 'PUBLICA' : (label === 'Privada' ? 'PRIVADA' : null));
        configurarClick('chartCarrera', 'carrera');
        configurarClick('chartUniversidad', 'universidad');
        
        // Gráficos de departamento y evaluaciones (por si acaso)
        configurarClick('chartDepartamentos', 'departamento');
        configurarClick('chartEvaluaciones', 'evaluacion_mes');
    }, 500);
});
</script>
<?= $this->endSection() ?>
