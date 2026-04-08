<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3><i class="bi bi-key"></i> Nueva Contraseña</h3>
                        <p class="text-muted">Ingrese su nueva contraseña</p>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('reset_token')): ?>
                        <div class="alert alert-info">
                            <strong>Token de recuperación:</strong> 
                            <?= session()->getFlashdata('reset_token') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/security/update-password') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <input type="hidden" name="token" value="<?= $token ?>">
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control" required minlength="6">
                            <div class="form-text">Mínimo 6 caracteres</div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg"></i> Cambiar Contraseña
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('/login') ?>">Volver a Iniciar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
