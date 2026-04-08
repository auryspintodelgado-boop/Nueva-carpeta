<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Evaluación del Desempeño Laboral</h2>
        <a href="<?= base_url('/evaluaciones/director') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Información del Evaluación -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Datos de la Evaluación</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Unidad/Departamento</label>
                    <input type="text" class="form-control" value="<?= $departamento['nombre'] ?? '' ?>" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Período a Evaluar *</label>
                    <input type="month" name="mes_evaluado" class="form-control" required value="<?= $mesEvaluacion ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha de Ingreso</label>
                    <input type="date" name="fecha_ingreso" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Personal a Evaluar *</label>
                    <select name="persona_id" class="form-select" required id="personaSelect">
                        <option value="">Seleccionar persona</option>
                        <?php foreach ($personal as $p): ?>
                            <?php 
                            // Verificar si ya tiene evaluación en este mes
                            $yaEvaluado = false;
                            if (isset($evaluacionesPersona[$p['id']])) {
                                $yaEvaluado = true;
                            }
                            ?>
                            <option value="<?= $p['id'] ?>" <?= $yaEvaluado ? 'disabled' : '' ?>>
                                <?= $p['cedula'] ?> - <?= $p['primer_nombre'] ?> <?= $p['primer_apellido'] ?>
                                <?= $yaEvaluado ? ' (Ya evaluado)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <form action="<?= base_url('/evaluaciones/director/store') ?>" method="post" id="formEvaluacion">
        <?= csrf_field() ?>
        
        <?php if (isset($departamento_id)): ?>
        <input type="hidden" name="departamento_id" value="<?= $departamento_id ?>">
        <?php endif; ?>
        
        <!-- Instrucciones -->
        <div class="alert alert-info mb-4">
            <h5><i class="bi bi-info-circle"></i> Instrucciones</h5>
            <p class="mb-0">Antes de iniciar la evaluación, lea bien las instrucciones. En forma objetiva y de conciencia asigne el puntaje correspondiente. Recuerde que cada puntaje corresponde a un nivel que va de 1 a 5:</p>
            <ul class="mb-0 mt-2">
                <li><strong>1 = Muy bajo</strong> - Rendimiento laboral no aceptable</li>
                <li><strong>2 = Bajo</strong> - Rendimiento laboral regular</li>
                <li><strong>3 = Moderado</strong> - Rendimiento laboral bueno</li>
                <li><strong>4 = Alto</strong> - Rendimiento laboral muy bueno</li>
                <li><strong>5 = Muy Alto</strong> - Rendimiento laboral excelente</li>
            </ul>
        </div>

        <!-- Sección 1: Orientación de Resultados -->
        <div class="card mb-4 border-start border-4 border-primary">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-bullseye"></i> 1. Orientación de Resultados</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Termina su trabajo oportunamente *</label>
                        <select name="ori_termino_oportuno" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Cumple con las tareas que se le encomienda *</label>
                        <select name="ori_cumple_tareas" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Realiza un volumen adecuado de trabajo *</label>
                        <select name="ori_volumen_adecuado" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="obs_orientacion" class="form-control" rows="2" placeholder="Observaciones sobre orientación de resultados..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección 2: Calidad y Organización -->
        <div class="card mb-4 border-start border-4 border-success">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-check2-square"></i> 2. Calidad y Organización</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">No comete errores en el trabajo *</label>
                        <select name="cal_no_errores" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Hace uso racional de los recursos *</label>
                        <select name="cal_recursos_racionales" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">No requiere de supervisión frecuente *</label>
                        <select name="cal_supervision" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Se muestra profesional en el trabajo *</label>
                        <select name="cal_profesional" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Se muestra respetuoso y amable en el trato *</label>
                        <select name="cal_respetuoso" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Planifica sus actividades *</label>
                        <select name="cal_planifica" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Hace uso de indicadores *</label>
                        <select name="cal_indicadores" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Se preocupa por alcanzar las metas *</label>
                        <select name="cal_metas" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="obs_calidad" class="form-control" rows="2" placeholder="Observaciones sobre calidad y organización..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección 3: Relaciones Interpersonales y Trabajo en Equipo -->
        <div class="card mb-4 border-start border-4 border-info">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-people"></i> 3. Relaciones Interpersonales y Trabajo en Equipo</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Se muestra cortés con el personal y con sus compañeros *</label>
                        <select name="rel_cortes" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Brinda una adecuada orientación a sus compañeros *</label>
                        <select name="rel_orientacion" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Evita los conflictos dentro del trabajo *</label>
                        <select name="rel_conflictos" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Muestra aptitud para integrarse al equipo *</label>
                        <select name="rel_integracion" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Se identifica fácilmente con los objetivos del equipo *</label>
                        <select name="rel_objetivos" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="obs_relaciones" class="form-control" rows="2" placeholder="Observaciones sobre relaciones interpersonales..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección 4: Iniciativa -->
        <div class="card mb-4 border-start border-4 border-warning">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-lightbulb"></i> 4. Iniciativa</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Muestra nuevas ideas para mejorar los procesos *</label>
                        <select name="ini_ideas" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Se muestra accesible al cambio *</label>
                        <select name="ini_cambio" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Se anticipa a las dificultades *</label>
                        <select name="ini_anticipacion" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Tiene gran capacidad para resolver problemas *</label>
                        <select name="ini_resolucion" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="1">1 - Muy Bajo</option>
                            <option value="2">2 - Bajo</option>
                            <option value="3">3 - Moderado</option>
                            <option value="4">4 - Alto</option>
                            <option value="5">5 - Muy Alto</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea name="obs_iniciativa" class="form-control" rows="2" placeholder="Observaciones sobre iniciativa..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Observaciones Generales -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-card-text"></i> Observaciones Generales</h5>
            </div>
            <div class="card-body">
                <textarea name="observaciones" class="form-control mb-3" rows="3" placeholder="Observaciones generales de la evaluación..."></textarea>
                <textarea name="comentarios_adicionales" class="form-control" rows="2" placeholder="Comentarios adicionales..."></textarea>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="<?= base_url('/evaluaciones/director') ?>" class="btn btn-secondary me-md-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Evaluación
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
