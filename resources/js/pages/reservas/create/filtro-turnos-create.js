if (window.location.pathname.includes('/disponibles/create')) {

    // Variables globales para la edición
    let todosLosTurnos = [];
    let medicoInicial = document.getElementById('medico_id').value;
    let especialidadInicial = document.getElementById('especialidad_id').value;

    // Función para cargar médicos con selección inicial
    async function cargarMedicos(especialidadId, medicoSeleccionado = null) {
        const selectMedico = document.getElementById('medico_id');

        if (!especialidadId) {
            selectMedico.innerHTML = '<option value="">Seleccione un médico</option>';
            return;
        }

        selectMedico.disabled = true;
        selectMedico.innerHTML = '<option value="">Cargando médicos...</option>';

        try {
            const response = await fetch(`/getMedicosPorEspecialidad/${especialidadId}`);
            const data = await response.json();

            selectMedico.innerHTML = '<option value="">Seleccione un médico</option>';
            data.medicos.forEach(medico => {
                const option = new Option(medico.nombre, medico.id);
                if (medicoSeleccionado && medico.id == medicoSeleccionado) {
                    option.selected = true;
                }
                selectMedico.add(option);
            });

            selectMedico.disabled = false;

            // Si hay medico seleccionado, cargar sus turnos
            if (medicoSeleccionado) {
                await cargarTurnos(medicoSeleccionado);
            }
        } catch (error) {
            console.error("Error al cargar lista de médicos:", error);
            selectMedico.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Función para cargar turnos con selección inicial
    async function cargarTurnos(medicoId) {
        const selectFecha = document.getElementById('fecha_turno');
        const selectHora = document.getElementById('hora_turno');

        selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';
        selectHora.innerHTML = '<option value="">Seleccione una hora</option>';

        if (!medicoId) return;

        selectFecha.disabled = true;
        selectFecha.innerHTML = '<option value="">Cargando fechas...</option>';

        try {
            const response = await fetch(`/getTurnosPorEquipo/${medicoId}`);
            const data = await response.json();

            todosLosTurnos = data.turnos;

            if (todosLosTurnos.length === 0) {
                selectFecha.innerHTML = '<option value="">Sin fechas disponibles</option>';
                return;
            }

            // Mostrar fechas únicas
            const fechasUnicas = [...new Set(todosLosTurnos.map(t => t.fecha))];
            selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';

            fechasUnicas.forEach(fecha => {
                const fechaObj = new Date(fecha);
                const option = new Option(
                    fechaObj.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    }),
                    fecha
                );
                selectFecha.add(option);
            });

            selectFecha.disabled = false;
        } catch (error) {
            console.error("Error al cargar turnos:", error);
            selectFecha.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Inicialización al cargar la página
    document.addEventListener('DOMContentLoaded', async function () {
        // Cargar médicos si hay especialidad seleccionada
        if (especialidadInicial) {
            await cargarMedicos(especialidadInicial, medicoInicial);
        }

        // Event listeners
        document.getElementById('especialidad_id').addEventListener('change', function () {
            cargarMedicos(this.value);
        });

        document.getElementById('medico_id').addEventListener('change', function () {
            cargarTurnos(this.value);
        });

        document.getElementById('fecha_turno').addEventListener('change', function () {
            const turnosDisponibles = todosLosTurnos.filter(t => t.fecha === this.value);
            const selectHora = document.getElementById('hora_turno');

            selectHora.innerHTML = '<option value="">Seleccione una hora</option>';
            turnosDisponibles.forEach(turno => {
                selectHora.add(new Option(turno.hora, turno.id));
            });
        });
    });
}