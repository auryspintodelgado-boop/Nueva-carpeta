<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <h2 class="mb-4">Reportes del Sistema</h2>

    <div class="row">
        <!-- Reporte de Personas -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Personas</h5>
                </div>
                <div class="card-body">
                    <p>Genere reportes de las personas registradas en el sistema.</p>
                    
                    <form action="<?= base_url('/reportes/personas') ?>" method="get" target="_blank">
                        <div class="mb-3">
                            <label class="form-label">Departamento</label>
                            <select name="departamento" class="form-select">
                                <option value="">Todos los departamentos</option>
                                <?php foreach ($departamentos as $dept): ?>
                                    <option value="<?= $dept['id'] ?>"><?= $dept['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="formato" value="html" class="btn btn-outline-primary">
                                <i class="bi bi-eye"></i> Ver en Pantalla
                            </button>
                            <button type="submit" name="formato" value="excel" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                            </button>
                            <button type="submit" name="formato" value="pdf" class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte de Evaluaciones -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Evaluaciones</h5>
                </div>
                <div class="card-body">
                    <p>Genere reportes de las evaluaciones realizadas.</p>
                    
                    <form action="<?= base_url('/reportes/evaluaciones') ?>" method="get" target="_blank">
                        <div class="mb-3">
                            <label class="form-label">Departamento</label>
                            <select name="departamento" class="form-select">
                                <option value="">Todos los departamentos</option>
                                <?php foreach ($departamentos as $dept): ?>
                                    <option value="<?= $dept['id'] ?>"><?= $dept['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mes</label>
                            <select name="mes" class="form-select">
                                <option value="">Todos los meses</option>
                                <option value="01">Enero</option>
                                <option value="02">Febrero</option>
                                <option value="03">Marzo</option>
                                <option value="04">Abril</option>
                                <option value="05">Mayo</option>
                                <option value="06">Junio</option>
                                <option value="07">Julio</option>
                                <option value="08">Agosto</option>
                                <option value="09">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Año</label>
                            <select name="año" class="form-select">
                                <option value="">Todos los años</option>
                                <?php for($y = date('Y'); $y >= date('Y')-5; $y--): ?>
                                    <option value="<?= $y ?>"><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="formato" value="html" class="btn btn-outline-primary">
                                <i class="bi bi-eye"></i> Ver en Pantalla
                            </button>
                            <button type="submit" name="formato" value="excel" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                            </button>
                            <button type="submit" name="formato" value="pdf" class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte de Seguimientos -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-journal-text"></i> Seguimientos</h5>
                </div>
                <div class="card-body">
                    <p>Genere reportes de los seguimientos realizados.</p>
                    
                    <form action="<?= base_url('/reportes/seguimientos') ?>" method="get" target="_blank">
                        <div class="mb-3">
                            <label class="form-label">Departamento</label>
                            <select name="departamento" class="form-select">
                                <option value="">Todos los departamentos</option>
                                <?php foreach ($departamentos as $dept): ?>
                                    <option value="<?= $dept['id'] ?>"><?= $dept['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="PENDIENTE">Pendiente</option>
                                <option value="EN_PROCESO">En Proceso</option>
                                <option value="COMPLETADO">Completado</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="formato" value="html" class="btn btn-outline-primary">
                                <i class="bi bi-eye"></i> Ver en Pantalla
                            </button>
                            <button type="submit" name="formato" value="excel" class="btn btn-success">
                                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                            </button>
                            <button type="submit" name="formato" value="pdf" class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas y Gráficos -->
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Estadísticas y Gráficos</h5>
                </div>
                <div class="card-body">
                    <p>Visualice gráficos y estadísticas del sistema, incluyendo la distribución por tipo de sangre.</p>
                    
                    <form action="<?= base_url('/reportes/estadisticas') ?>" method="get">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Departamento</label>
                                <select name="departamento" class="form-select">
                                    <option value="">Todos los departamentos</option>
                                    <?php foreach ($departamentos as $dept): ?>
                                        <option value="<?= $dept['id'] ?>"><?= $dept['nombre'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="bi bi-graph-up"></i> Ver Estadísticas
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> 
                            Incluyen gráficos de tipo de sangre, distribución por departamento y más.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?= base_url('/dashboard') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Dashboard
        </a>
    </div>
</div>
<?= $this->endSection() ?>
