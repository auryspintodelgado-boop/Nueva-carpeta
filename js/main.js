/**
 * Custom JavaScript
 * AURYS - Sistema de Gestión de Recursos Humanos
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Calculate age from birthdate
    var birthdateInput = document.getElementById('fecha_nacimiento');
    if (birthdateInput) {
        birthdateInput.addEventListener('change', function() {
            var birthdate = this.value;
            if (birthdate) {
                var birth = new Date(birthdate);
                var today = new Date();
                var age = Math.floor((today - birth) / (365.25 * 24 * 60 * 60 * 1000));
                var ageInput = document.getElementById('edad');
                if (ageInput) {
                    ageInput.value = age;
                }
            }
        });
    }
    
    // Characterisation form section toggles
    var sectionToggles = document.querySelectorAll('.char-section-toggle');
    sectionToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            var target = this.getAttribute('data-target');
            var section = document.querySelector(target);
            if (section) {
                section.classList.toggle('show');
            }
        });
    });
    
    // Profile photo preview
    var fotoInput = document.getElementById('foto_perfil');
    if (fotoInput) {
        fotoInput.addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById('foto_preview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Evaluation score calculation
    var scoreInputs = document.querySelectorAll('.score-input');
    scoreInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            calculateTotalScore();
        });
    });
    
    // Search functionality
    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var searchText = this.value.toLowerCase();
            var table = document.querySelector('.table');
            if (table) {
                var rows = table.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    var text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchText) ? '' : 'none';
                });
            }
        });
    }
    
    // Pagination
    var pageButtons = document.querySelectorAll('.page-btn');
    pageButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var page = this.getAttribute('data-page');
            loadPage(page);
        });
    });
    
    // Confirmation modals
    var confirmButtons = document.querySelectorAll('.confirm-action');
    confirmButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var message = this.getAttribute('data-message') || '¿Está seguro?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-save draft (characterization)
    var autoSaveInterval = setInterval(function() {
        saveDraft();
    }, 60000); // Save every minute
    
    function saveDraft() {
        var form = document.querySelector('.char-form');
        if (form) {
            localStorage.setItem('char_draft', JSON.stringify(new FormData(form)));
        }
    }
    
    // Restore draft on load
    var draft = localStorage.getItem('char_draft');
    if (draft && window.location.pathname.includes('characterization')) {
        try {
            var data = JSON.parse(draft);
            Object.keys(data).forEach(function(key) {
                var input = document.querySelector('[name="' + key + '"]');
                if (input) {
                    input.value = data[key];
                }
            });
        } catch (e) {
            console.log('Error restoring draft');
        }
    }
    
    // Clear draft after submission
    var charForm = document.querySelector('.char-form');
    if (charForm) {
        charForm.addEventListener('submit', function() {
            localStorage.removeItem('char_draft');
        });
    }
    
    // Table row selection
    var tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(function(row) {
        row.addEventListener('click', function() {
            this.classList.toggle('selected');
        });
    });
    
    // Department filter
    var deptFilter = document.getElementById('departmentFilter');
    if (deptFilter) {
        deptFilter.addEventListener('change', function() {
            var deptId = this.value;
            var rows = document.querySelectorAll('.employee-row');
            rows.forEach(function(row) {
                if (!deptId || row.getAttribute('data-dept') === deptId) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Export to CSV
    var exportBtn = document.getElementById('exportCSV');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            exportTableToCSV('table');
        });
    }
});

function calculateTotalScore() {
    var scores = document.querySelectorAll('.score-input:checked');
    var total = 0;
    scores.forEach(function(score) {
        total += parseInt(score.value);
    });
    
    var totalDisplay = document.getElementById('totalScore');
    if (totalDisplay) {
        totalDisplay.textContent = total;
        
        // Update color based on score
        if (total < 8) {
            totalDisplay.className = 'text-danger';
        } else if (total < 14) {
            totalDisplay.className = 'text-warning';
        } else {
            totalDisplay.className = 'text-success';
        }
    }
}

function loadPage(page) {
    // AJAX page loading implementation
    console.log('Loading page:', page);
}

function exportTableToCSV(tableId) {
    var table = document.getElementById(tableId);
    if (!table) return;
    
    var csv = [];
    var rows = table.querySelectorAll('tr');
    
    rows.forEach(function(row) {
        var cols = row.querySelectorAll('td, th');
        var rowData = [];
        cols.forEach(function(col) {
            rowData.push('"' + col.innerText + '"');
        });
        csv.push(rowData.join(','));
    });
    
    var csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
    var downloadLink = document.createElement('a');
    downloadLink.download = 'export.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Show/hide password toggle
function togglePassword() {
    var passwordInput = document.getElementById('password');
    var toggleBtn = document.getElementById('togglePassword');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.innerHTML = '<i class="bi bi-eye-slash"></i>';
    } else {
        passwordInput.type = 'password';
        toggleBtn.innerHTML = '<i class="bi bi-eye"></i>';
    }
}
