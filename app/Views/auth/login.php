<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h3><i class="bi bi-person-badge"></i> Sistema de Caracterización</h3>
                        <p class="text-muted">Ingrese sus credenciales</p>
                    </div>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <p class="mb-0"><?= $error ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/login') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario o Email</label>
                            <input type="text" name="username" id="username" 
                                   class="form-control" required
                                   value="<?= old('username') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('/security/forgot-password') ?>">¿Olvidó su contraseña?</a>
                    </div>
                    <div class="text-center mt-1">
                        <a href="<?= base_url('/register') ?>">¿No tiene cuenta? Regístrese</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
