//Checkboxs para turnos -crear nuevo turno seccion horarios y turnos
if (window.location.pathname.includes('/turnos/create') || window.location.pathname.includes('/turnos/edit/')) {

    document.addEventListener('DOMContentLoaded', function () {
        // Grupo 1: Turnos (mañana, tarde, noche, no aplica)
        const turnoManana = document.getElementById('manana');
        const turnoTarde = document.getElementById('tarde');
        const turnoNoche = document.getElementById('noche');
        const grupoTurnos = [turnoManana, turnoTarde, turnoNoche];

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
            checkbox.addEventListener('change', function () {
                manejarSeleccionExclusiva(this, grupoTurnos);

                // Si se selecciona "No aplica", desmarcar los horarios
                if (this.checked) {
                    grupoHorarios.forEach(h => h.checked = false);
                }
            });
        });

        // Agregar event listeners para el grupo de horarios
        grupoHorarios.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                manejarSeleccionExclusiva(this, grupoHorarios);

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

        }

        validarEstadoInicial();
    });
}