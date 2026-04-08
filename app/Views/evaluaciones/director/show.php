<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clipboard-check"></i> Detalle de Evaluación</h2>
        <a href="<?= base_url('/evaluaciones/director/historial') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Datos del Evaluación -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Datos de la Evaluación</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Unidad/Departamento:</strong> <?= $evaluacion['departamento_id'] ?? '' ?>
                </div>
                <div class="col-md-3">
                    <strong>Evaluado:</strong> <?= $persona['primer_nombre'] ?? '' ?> <?= $persona['primer_apellido'] ?? '' ?>
                </div>
                <div class="col-md-3">
                    <strong>Cédula:</strong> <?= $persona['cedula'] ?? '' ?>
                </div>
                <div class="col-md-3">
                    <strong>Período:</strong> <?= $evaluacion['mes_evaluado'] ?? '' ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3">
                    <strong>Fecha de Evaluación:</strong> <?= !empty($evaluacion['fecha_evaluacion']) ? date('d/m/Y', strtotime($evaluacion['fecha_evaluacion'])) : '' ?>
                </div>
                <div class="col-md-3">
                    <strong>Fecha de Ingreso:</strong> <?= !empty($evaluacion['fecha_ingreso']) ? date('d/m/Y', strtotime($evaluacion['fecha_ingreso'])) : '' ?>
                </div>
                <div class="col-md-3">
                    <strong>Evaluador:</strong> <?= $evaluacion['nombre_evaluador'] ?? ($evaluador['nombre_completo'] ?? 'Sistema') ?>
                </div>
                <div class="col-md-3">
                    <strong>Tipo:</strong> <?= $evaluacion['tipo_evaluacion'] ?? 'MENSUAL' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Puntuación Total -->
    <div class="card mb-4 bg-light">
        <div class="card-body text-center">
            <h4 class="mb-3">PUNTUACIÓN TOTAL</h4>
            <?php
            $puntuacion = floatval($evaluacion['puntuacion'] ?? 0);
            $colorClass = $puntuacion >= 18 ? 'text-success' : ($puntuacion >= 15 ? 'text-info' : ($puntuacion >= 12 ? 'text-primary' : ($puntuacion >= 10 ? 'text-warning' : 'text-danger')));
            $bgClass = $puntuacion >= 18 ? 'bg-success' : ($puntuacion >= 15 ? 'bg-info' : ($puntuacion >= 12 ? 'bg-primary' : ($puntuacion >= 10 ? 'bg-warning' : 'bg-danger')));
            ?>
            <div class="display-1 fw-bold <?= $colorClass ?>">
                <?= number_format($puntuacion, 1) ?> / 20
            </div>
            <span class="badge <?= $bgClass ?> fs-5">
                <?= $evaluacion['resultado'] ?? 'Sin resultado' ?>
            </span>
        </div>
    </div>

    <!-- Detalle de las 4 Secciones con Sub-campos -->
    <div class="row">
        <!-- Sección 1: Orientación de Resultados -->
        <div class="col-md-6 mb-4">
            <div class="card border-start border-4 border-primary h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-bullseye"></i> 1. Orientación de Resultados</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge bg-primary fs-4"><?= number_format($evaluacion['orientacion_resultados'] ?? 0, 1) ?>/5</span>
                    </div>
                    
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <td>Termina su trabajo oportunamente</td>
                                <td class="text-center"><strong><?= $evaluacion['ori_termino_oportuno'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Cumple con las tareas que se le encomienda</td>
                                <td class="text-center"><strong><?= $evaluacion['ori_cumple_tareas'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Realiza un volumen adecuado de trabajo</td>
                                <td class="text-center"><strong><?= $evaluacion['ori_volumen_adecuado'] ?? '-' ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <?php if (!empty($evaluacion['obs_orientacion'])): ?>
                        <p><strong>Observaciones:</strong></p>
                        <p class="text-muted"><?= nl2br($evaluacion['obs_orientacion']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sección 2: Calidad y Organización -->
        <div class="col-md-6 mb-4">
            <div class="card border-start border-4 border-success h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-check2-square"></i> 2. Calidad y Organización</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge bg-success fs-4"><?= number_format($evaluacion['calidad_organizacion'] ?? 0, 1) ?>/5</span>
                    </div>
                    
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <td>No comete errores en el trabajo</td>
                                <td class="text-center"><strong><?= $evaluacion['cal_no_errores'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Hace uso racional de los recursos</td>
                                <td class="text-center"><strong><?= $evaluacion['cal_recursos_racionales'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>No requiere de supervisión frecuente</td>
                                <td class="text-center"><strong><?= $evaluacion['cal_supervision'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Se muestra profesional en el trabajo</td>
                                <td class="text-center"><strong><?= $evaluacion['cal_profesional'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Se muestra respetuoso y amable en el trato</td>
                                <td class="text-center"><strong><?= $evaluacion['cal_respetuoso'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Planifica sus actividades</td>
                                <td class="text-center"><strong><?= $evaluacion['cal_planifica'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Hace uso de indicadores</td>
                                <td class="text-center"><strong><?= $evaluacion['cal_indicadores'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Se preocupa por alcanzar las metas</td>
                                <td class="text-center"><strong><?= $evaluacion['cal_metas'] ?? '-' ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <?php if (!empty($evaluacion['obs_calidad'])): ?>
                        <p><strong>Observaciones:</strong></p>
                        <p class="text-muted"><?= nl2br($evaluacion['obs_calidad']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sección 3: Relaciones Interpersonales -->
        <div class="col-md-6 mb-4">
            <div class="card border-start border-4 border-info h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-people"></i> 3. Relaciones Interpersonales y Trabajo en Equipo</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge bg-info fs-4"><?= number_format($evaluacion['relaciones_interpersonales'] ?? 0, 1) ?>/5</span>
                    </div>
                    
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <td>Se muestra cortés con el personal y compañeros</td>
                                <td class="text-center"><strong><?= $evaluacion['rel_cortes'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Brinda adecuada orientación a sus compañeros</td>
                                <td class="text-center"><strong><?= $evaluacion['rel_orientacion'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Evita los conflictos dentro del trabajo</td>
                                <td class="text-center"><strong><?= $evaluacion['rel_conflictos'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Muestra aptitud para integrarse al equipo</td>
                                <td class="text-center"><strong><?= $evaluacion['rel_integracion'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Se identifica con los objetivos del equipo</td>
                                <td class="text-center"><strong><?= $evaluacion['rel_objetivos'] ?? '-' ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <?php if (!empty($evaluacion['obs_relaciones'])): ?>
                        <p><strong>Observaciones:</strong></p>
                        <p class="text-muted"><?= nl2br($evaluacion['obs_relaciones']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sección 4: Iniciativa -->
        <div class="col-md-6 mb-4">
            <div class="card border-start border-4 border-warning h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-lightbulb"></i> 4. Iniciativa</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge bg-warning fs-4"><?= number_format($evaluacion['iniciativa'] ?? 0, 1) ?>/5</span>
                    </div>
                    
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <td>Muestra nuevas ideas para mejorar procesos</td>
                                <td class="text-center"><strong><?= $evaluacion['ini_ideas'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Se muestra accesible al cambio</td>
                                <td class="text-center"><strong><?= $evaluacion['ini_cambio'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Se anticipa a las dificultades</td>
                                <td class="text-center"><strong><?= $evaluacion['ini_anticipacion'] ?? '-' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Tiene capacidad para resolver problemas</td>
                                <td class="text-center"><strong><?= $evaluacion['ini_resolucion'] ?? '-' ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <?php if (!empty($evaluacion['obs_iniciativa'])): ?>
                        <p><strong>Observaciones:</strong></p>
                        <p class="text-muted"><?= nl2br($evaluacion['obs_iniciativa']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Observaciones Generales -->
    <?php if (!empty($evaluacion['observaciones']) || !empty($evaluacion['comentarios_adicionales'])): ?>
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-card-text"></i> Observaciones Generales</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($evaluacion['observaciones'])): ?>
                <p><?= nl2br($evaluacion['observaciones']) ?></p>
            <?php endif; ?>
            <?php if (!empty($evaluacion['comentarios_adicionales'])): ?>
                <p><strong>Comentarios Adicionales:</strong></p>
                <p><?= nl2br($evaluacion['comentarios_adicionales']) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Escala de Evaluación -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Escala de Evaluación</h5>
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr class="table-light">
                        <th>Puntaje</th>
                        <th>Nivel</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>5</strong></td>
                        <td>Muy Alto</td>
                        <td>Rendimiento laboral excelente</td>
                    </tr>
                    <tr>
                        <td><strong>4</strong></td>
                        <td>Alto</td>
                        <td>Rendimiento laboral muy bueno</td>
                    </tr>
                    <tr>
                        <td><strong>3</strong></td>
                        <td>Moderado</td>
                        <td>Rendimiento laboral bueno</td>
                    </tr>
                    <tr>
                        <td><strong>2</strong></td>
                        <td>Bajo</td>
                        <td>Rendimiento laboral regular</td>
                    </tr>
                    <tr>
                        <td><strong>1</strong></td>
                        <td>Muy Bajo</td>
                        <td>Rendimiento laboral no aceptable</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Firma -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 text-center">
                    <p class="mb-5"><strong>Firma del Evaluador</strong></p>
                    <p>_____________________________</p>
                    <p><?= $evaluacion['nombre_evaluador'] ?? '' ?></p>
                </div>
                <div class="col-md-6 text-center">
                    <p class="mb-5"><strong>Firma del Ratificador</strong></p>
                    <p>_____________________________</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Imprimir -->
    <div class="d-grid gap-2">
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="bi bi-printer"></i> Imprimir Evaluación
        </button>
    </div>
</div>
<?= $this->endSection() ?>
