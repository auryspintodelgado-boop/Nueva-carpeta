<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3><i class="bi bi-shield-check"></i> Verificación en Dos Pasos</h3>
                        <p class="text-muted">Ingrese el código enviado</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('two_factor_code')): ?>
                        <div class="alert alert-info">
                            <strong>Código de prueba:</strong> 
                            <?= session()->getFlashdata('two_factor_code') ?>
                            <br><small>En producción, esto se enviaría por SMS o email</small>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/security/verify-2fa') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Código de Verificación</label>
                            <input type="text" name="code" id="code" 
                                   class="form-control text-center fs-4" 
                                   required maxlength="6" pattern="[0-9]{6}"
                                   placeholder="000000" autocomplete="off">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg"></i> Verificar
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('/logout') ?>">Cancelar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
