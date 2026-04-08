<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Nueva Persona - Caracterización</h2>
        <a href="<?= base_url('/personas') ?>" class="btn btn-secondary">
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

    <form action="<?= base_url('/personas') ?>" method="post">
        <?= csrf_field() ?>
        
        <?php if (session()->get('rol') === 'ADMIN'): ?>
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-building"></i> Departamento</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Departamento</label>
                        <select name="departamento_id" class="form-select">
                            <option value="">Seleccionar departamento</option>
                            <?php foreach ($departamentos as $dep): ?>
                                <option value="<?= $dep['id'] ?>"><?= $dep['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Datos Personales -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Datos Personales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Número</label>
                        <input type="text" name="numero" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Nacionalidad *</label>
                        <input type="text" name="nacionalidad" class="form-control" value="VENEZUELA">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Cédula *</label>
                        <input type="text" name="cedula" class="form-control" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Sexo</label>
                        <select name="sexo" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" class="form-control">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Primer Nombre *</label>
                        <input type="text" name="primer_nombre" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Primer Apellido *</label>
                        <input type="text" name="primer_apellido" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Teléfono 1</label>
                        <input type="text" name="telefono1" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Teléfono 2</label>
                        <input type="text" name="telefono2" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="correo_electronico" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Estado Civil</label>
                        <select name="estado_civil" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="SOLTERO">Soltero/a</option>
                            <option value="CASADO">Casado/a</option>
                            <option value="DIVORCIADO">Divorciado/a</option>
                            <option value="VIUDO">Viudo/a</option>
                            <option value="UNION_LIBRE">Unión Libre</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">¿Tiene Hijos?</label>
                        <select name="tiene_hijos" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="S">Sí</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Cantidad de Hijos</label>
                        <input type="number" name="cantidad_hijos" class="form-control" min="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Carga Familiar</label>
                        <input type="number" name="carga_familiar" class="form-control" min="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos Académicos -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-book"></i> Datos Académicos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Universidad</label>
                        <input type="text" name="siglas_universidad" class="form-control" placeholder="Siglas">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de IEU</label>
                        <select name="tipo_ieu" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="PUBLICA">Pública</option>
                            <option value="PRIVADA">Privada</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nivel Académico</label>
                        <select name="nivel_academico" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="PREGRADO">Pregrado</option>
                            <option value="POSTGRADO">Postgrado</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Carrera</label>
                        <input type="text" name="carrera" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Año / Semestre / Trimestre</label>
                        <input type="text" name="ano_semestre" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sede</label>
                        <input type="text" name="sede" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">¿Posee Beca?</label>
                        <select name="posee_beca" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="S">Sí</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Estado</label>
                        <input type="text" name="estado" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Ubicación -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Ubicación</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Urbanismo</label>
                        <input type="text" name="urbanismo" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Municipio</label>
                        <input type="text" name="municipio" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Parroquia</label>
                        <input type="text" name="parroquia" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Comuna</label>
                        <input type="text" name="comuna" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos de Salud -->
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-heart-pulse"></i> Datos de Salud</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">¿Posee Discapacidad?</label>
                        <select name="posee_discapacidad" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="S">Sí</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-md-9 mb-3">
                        <label class="form-label">Descripción de Discapacidad</label>
                        <textarea name="descripcion_discapacidad" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">¿Presenta Enfermedad?</label>
                        <select name="presenta_enfermedad" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="S">Sí</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Condición Médica</label>
                        <textarea name="condicion_medica" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Medicamentos que Consume</label>
                        <textarea name="medicamentos" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos Laborales -->
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-briefcase"></i> Datos Laborales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">¿Trabaja?</label>
                        <select name="trabaja" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="S">Sí</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tipo de Empleo</label>
                        <input type="text" name="tipo_empleo" class="form-control">
                    </div>
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Medio de Transporte</label>
                        <input type="text" name="medio_transporte" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos Electorales -->
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Datos Electorales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">¿Inscrito en CNE?</label>
                        <select name="inscrito_cne" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="S">Sí</option>
                            <option value="N">No</option>
                        </select>
                    </div>
                    <div class="col-md-9 mb-3">
                        <label class="form-label">Centro Electoral</label>
                        <input type="text" name="centro_electoral" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <!-- Datos Físicos -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-rulers"></i> Datos Físicos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Talla Camisa</label>
                        <input type="text" name="talla_camisa" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Talla Zapatos</label>
                        <input type="text" name="talla_zapatos" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Talla Pantalón</label>
                        <input type="text" name="talla_pantalon" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Altura</label>
                        <input type="text" name="altura" class="form-control" placeholder="cm">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Peso</label>
                        <input type="text" name="peso" class="form-control" placeholder="kg">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Tipo de Sangre</label>
                        <input type="text" name="tipo_sangre" class="form-control" placeholder="ej: O+">
                    </div>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Observaciones</h5>
            </div>
            <div class="card-body">
                <textarea name="observaciones" class="form-control" rows="3" placeholder="Observaciones adicionales..."></textarea>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
            <a href="<?= base_url('/personas') ?>" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar Persona
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
