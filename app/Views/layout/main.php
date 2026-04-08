<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema de Caracterización' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
        }
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
        }
        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #34495e;
        }
        .card-stat {
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <?php if (session()->get('isLoggedIn')): ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar py-3">
                <div class="text-center mb-4">
                    <h5 class="text-white">Sistema AuryS</h5>
                    <small class="text-muted">Caracterización</small>
                </div>
                <div class="px-3">
                    <a href="<?= base_url('/dashboard') ?>" class="<?= url_is('/dashboard') ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <?php if (session()->get('rol') === 'ADMIN'): ?>
                    <a href="<?= base_url('/admin') ?>" class="<?= url_is('/admin*') ? 'active' : '' ?>">
                        <i class="bi bi-gear me-2"></i> Administración
                    </a>
                    <?php endif; ?>
                    <a href="<?= base_url('/personas') ?>" class="<?= url_is('/personas*') ? 'active' : '' ?>">
                        <i class="bi bi-people me-2"></i> Personas
                    </a>
                    <a href="<?= base_url('/evaluaciones') ?>" class="<?= url_is('/evaluaciones*') ? 'active' : '' ?>">
                        <i class="bi bi-clipboard-check me-2"></i> Evaluaciones
                    </a>
                    <a href="<?= base_url('/evaluaciones/empleado-del-mes') ?>">
                        <i class="bi bi-trophy me-2"></i> Empleado del Mes
                    </a>
                    <a href="<?= base_url('/seguimientos') ?>" class="<?= url_is('/seguimientos*') ? 'active' : '' ?>">
                        <i class="bi bi-journal-text me-2"></i> Seguimientos
                    </a>
                    <hr class="text-muted">
                    <a href="<?= base_url('/seguimientos/pendientes') ?>">
                        <i class="bi bi-exclamation-circle me-2"></i> Pendientes
                    </a>
                    <a href="<?= base_url('/seguimientos/proximos') ?>">
                        <i class="bi bi-calendar-check me-2"></i> Próximos
                    </a>
                    <hr class="text-muted">
                    <a href="<?= base_url('/reportes') ?>">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i> Reportes
                    </a>
                    <hr class="text-muted">
                    <a href="<?= base_url('/logout') ?>">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                    </a>
                </div>
            </nav>

            <!-- Contenido principal -->
            <main class="col-md-10 ms-sm-auto px-4 py-3">
                <!-- Mensajes flash -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>
    <?php else: ?>
        <!-- Vista sin login -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
