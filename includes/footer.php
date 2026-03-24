<?php
/**
 * Common Footer
 * AURYS - Sistema de Gestión de Recursos Humanos
 */
?>
            <?php if (isLoggedIn()): ?>
            </main>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url() ?>/js/main.js"></script>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Confirm delete actions
        document.addEventListener('DOMContentLoaded', function() {
            var deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(e) {
                    if (!confirm('¿Está seguro que desea eliminar este registro?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
