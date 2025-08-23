document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
    
    if (!filterForm) return;

    // Función para manejar clicks en botones de filtro
    function setupFilterButtons(buttonSelector, inputId, clearOtherInputs = []) {
        document.querySelectorAll(buttonSelector).forEach(button => {
            button.addEventListener('click', function () {
                // Actualizar el input hidden correspondiente
                document.getElementById(inputId).value = this.dataset.value;
                
                // Limpiar otros inputs si es necesario
                clearOtherInputs.forEach(inputName => {
                    const input = document.querySelector(`[name="${inputName}"]`);
                    if (input) input.value = '';
                });
                
                // Enviar formulario
                filterForm.submit();
            });
        });
    }

    // Configurar botones de reservación
    setupFilterButtons('.reservation-btn', 'reservaInput', ['start_date', 'end_date']);
    
    // Configurar botones de fecha rápida
    setupFilterButtons('.date-btn', 'fechaInput', ['start_date', 'end_date']);

    // Validación de fechas
    filterForm.addEventListener('submit', function (e) {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;

        if (startDate && endDate && startDate > endDate) {
            e.preventDefault();
            alert('La fecha de inicio no puede ser mayor a la fecha final');
            return;
        }

        // Si se usan fechas personalizadas, resetear el filtro rápido
        if (startDate || endDate) {
            document.getElementById('fechaInput').value = 'today';
        }
    });
});