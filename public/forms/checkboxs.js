//Checkboxs para turnos -crear nuevo turno seccion horarios y turnos
document.addEventListener('DOMContentLoaded', function() {
    // Grupo 1: Turnos (mañana, tarde, noche, no aplica)
    const turnoManana = document.getElementById('manana');
    const turnoTarde = document.getElementById('tarde');
    const turnoNoche = document.getElementById('noche');
    const sinTurno = document.getElementById('sin-turno');
    const grupoTurnos = [turnoManana, turnoTarde, turnoNoche, sinTurno];
    
    // Grupo 2: Distribución de horarios
    const horario1 = document.getElementById('horario1');
    const horario2 = document.getElementById('horario2');
    const grupoHorarios = [horario1, horario2];
    
    // Función para manejar selección exclusiva en un grupo
    function manejarSeleccionExclusiva(checkboxClickeado, grupo) {
        if (checkboxClickeado.checked) {
            // Desmarcar todos los demás checkboxes del grupo
            grupo.forEach(checkbox => {
                if (checkbox !== checkboxClickeado) {
                    checkbox.checked = false;
                }
            });
        }
    }
    
    // Agregar event listeners para el grupo de turnos
    grupoTurnos.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            manejarSeleccionExclusiva(this, grupoTurnos);
            
            // Si se selecciona "No aplica", desmarcar los horarios
            if (this === sinTurno && this.checked) {
                grupoHorarios.forEach(h => h.checked = false);
            }
        });
    });
    
    // Agregar event listeners para el grupo de horarios
    grupoHorarios.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            manejarSeleccionExclusiva(this, grupoHorarios);
            
            // Si se selecciona un horario, desmarcar "No aplica"
            if ((this === horario1 || this === horario2) && this.checked) {
                sinTurno.checked = false;
            }
        });
    });
    
    // Validación inicial al cargar la página
    function validarEstadoInicial() {
        // Verificar si hay más de un checkbox marcado en algún grupo
        const turnosMarcados = grupoTurnos.filter(cb => cb.checked).length;
        const horariosMarcados = grupoHorarios.filter(cb => cb.checked).length;
        
        if (turnosMarcados > 1) {
            // Dejar solo el último marcado
            grupoTurnos.forEach((cb, index) => {
                if (cb.checked && index !== grupoTurnos.findIndex(c => c.checked && c === [...grupoTurnos].reverse().find(x => x.checked))) {
                    cb.checked = false;
                }
            });
        }
        
        if (horariosMarcados > 1) {
            // Dejar solo el último marcado
            grupoHorarios.forEach((cb, index) => {
                if (cb.checked && index !== grupoHorarios.findIndex(c => c.checked && c === [...grupoHorarios].reverse().find(x => x.checked))) {
                    cb.checked = false;
                }
            });
        }
        
        // Si "No aplica" está marcado, desmarcar horarios
        if (sinTurno.checked) {
            grupoHorarios.forEach(h => h.checked = false);
        }
        
        // Si hay horarios marcados, desmarcar "No aplica"
        if (horario1.checked || horario2.checked) {
            sinTurno.checked = false;
        }
    }
    
    validarEstadoInicial();
});