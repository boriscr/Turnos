document.addEventListener('DOMContentLoaded', function() {
    const filterContainer = document.querySelector('.date-filter-container');
    const quickFilter = document.querySelector('.quick-filter');
    const rangeFilter = document.querySelector('.range-filter');
    const toggleBtn = document.querySelector('.date-filter-toggle');
    const switchBtns = document.querySelectorAll('.filter-switch-btn');
    const applyRangeBtn = document.querySelector('.apply-range-btn');
    
    if (!filterContainer) return;
    
    // Función para cambiar entre filtros
    function switchFilter(toQuick) {
        if (toQuick) {
            quickFilter.classList.add('active');
            rangeFilter.classList.remove('active');
        } else {
            quickFilter.classList.remove('active');
            rangeFilter.classList.add('active');
        }
    }
    
    // Eventos para botones de cambio (desktop)
    switchBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const isQuickActive = quickFilter.classList.contains('active');
            switchFilter(!isQuickActive);
        });
    });
    
    // Evento para toggle (móvil)
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const isQuickActive = quickFilter.classList.contains('active');
            switchFilter(!isQuickActive);
            
            // Cambiar icono y texto del toggle
            const icon = this.querySelector('i');
            const isNowQuick = quickFilter.classList.contains('active');
            
            if (isNowQuick) {
                icon.className = 'bi bi-calendar-range';
                this.innerHTML = '<i class="bi bi-calendar-range"></i> Cambiar a rango de fechas';
            } else {
                icon.className = 'bi bi-lightning';
                this.innerHTML = '<i class="bi bi-lightning"></i> Cambiar a filtro rápido';
            }
        });
    }
    
    // Aplicar rango de fechas
    if (applyRangeBtn) {
        applyRangeBtn.addEventListener('click', function() {
            document.getElementById('filterForm').submit();
        });
    }
    
    // Auto-switch si hay fechas en el rango
    const startDate = document.querySelector('input[name="start_date"]').value;
    const endDate = document.querySelector('input[name="end_date"]').value;
    
    if (startDate || endDate) {
        switchFilter(false);
    }
});