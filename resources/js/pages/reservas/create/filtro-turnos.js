
    // Variables globales
    let todosLosTurnos = [];

    // Función para obtener fecha actual en formato ISO (YYYY-MM-DD)
    function obtenerFechaActual() {
        const ahora = new Date().toLocaleString('en-CA', { timeZone: 'America/Argentina/Jujuy' });
        return ahora.split(',')[0];
    }


    // Función para cargar medicos basados en especialidad seleccionada
    function cargarMedicos(especialidadId, medicoIdSeleccionado = null) {
        selectMedico = document.getElementById('medico_id');
        selectMedico.innerHTML = '<option value="">Seleccione un médico</option>';
        document.getElementById('fecha_turno').innerHTML = '<option value="">Seleccione una fecha</option>';
        document.getElementById('hora_turno').innerHTML = '<option value="">Seleccione una hora</option>';

        if (!especialidadId) return;

        selectMedico.disabled = true;
        selectMedico.innerHTML = '<option value="">Cargando médicos...</option>';

        fetch(`/getMedicosPorEspecialidad/${especialidadId}`)
            .then(response => response.json())
            .then(data => {
                selectMedico.innerHTML = '<option value="">Seleccione un profesional</option>';
                data.medicos.forEach(medico => {
                    const option = document.createElement('option');
                    option.value = medico.id;
                    option.textContent = medico.nombre + ' ' + medico.apellido;
                    if (medicoIdSeleccionado && medico.id == medicoIdSeleccionado) {
                        option.selected = true;
                    }
                    selectMedico.appendChild(option);
                });
                selectMedico.disabled = false;

                // Si hay un médico seleccionado, cargar sus turnos
                if (medicoIdSeleccionado) {
                    cargarTurnos(medicoIdSeleccionado);
                }
            })
            .catch(error => {
                console.error("Error al cargar lista de médicos:", error);
                selectMedico.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    // Función para cargar turnos basados en el médico seleccionado
    function cargarTurnos(medicoId, fechaSeleccionada = null, turnoIdSeleccionado = null) {
        const selectFecha = document.getElementById('fecha_turno');
        const selectHora = document.getElementById('hora_turno');

        selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';
        selectHora.innerHTML = '<option value="">Seleccione un horario</option>';

        if (!medicoId) return;

        selectFecha.disabled = true;
        selectFecha.innerHTML = '<option value="">Cargando fechas...</option>';

        fetch(`/getTurnosPorEquipo/${medicoId}`)
            .then(response => response.json())
            .then(data => {
                todosLosTurnos = data.turnos;

                if (todosLosTurnos.length === 0) {
                    selectFecha.innerHTML = '<option value="">Sin fechas disponibles</option>';
                    selectFecha.disabled = true;
                    return;
                }

                // Obtener fechas únicas de los turnos
                const fechasUnicas = [...new Set(todosLosTurnos.map(t => t.fecha))];

                selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';
                fechasUnicas.forEach(fecha => {
                    const fechaObj = new Date(fecha);
                    const option = document.createElement('option');
                    option.value = fecha;
                    option.textContent = fechaObj.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                    if (fechaSeleccionada && fecha === fechaSeleccionada) {
                        option.selected = true;
                    }
                    selectFecha.appendChild(option);
                });

                selectFecha.disabled = false;

                // Si hay una fecha seleccionada, cargar sus horas
                if (fechaSeleccionada) {
                    cargarHoras(fechaSeleccionada, turnoIdSeleccionado);
                }
            })
            .catch(error => {
                console.error("Error al cargar turnos:", error);
                selectFecha.innerHTML = '<option value="">Error al cargar</option>';
            });
    }

    // Función para cargar horas basadas en fecha seleccionada
    function cargarHoras(fechaSeleccionada, turnoIdSeleccionado = null) {
        const selectHora = document.getElementById('hora_turno');
        selectHora.innerHTML = '<option value="">Seleccione un horario</option>';

        if (!fechaSeleccionada) return;

        // Filtrar los turnos disponibles para esa fecha
        let turnosDisponibles = todosLosTurnos.filter(turno => turno.fecha === fechaSeleccionada);

        // Obtener fecha y hora actual en zona horaria de Jujuy
        const ahora = new Date().toLocaleString('en-CA', { timeZone: 'America/Argentina/Jujuy' });
        const fechaActual = ahora.split(',')[0];
        const horaActual = ahora.split(',')[1] ? ahora.split(',')[1].trim() : '00:00';

        if (fechaSeleccionada === fechaActual) {
            turnosDisponibles = turnosDisponibles.filter(turno => {
                const turnoDate = new Date(`${fechaSeleccionada}T${turno.hora}:00`);
                const ahoraDate = new Date(`${fechaActual}T${horaActual}:00`);
                return turnoDate.getTime() >= ahoraDate.getTime();
            });
        }

        if (turnosDisponibles.length === 0) {
            selectHora.innerHTML = '<option value="">Sin horario disponible</option>';
        } else {
            turnosDisponibles.forEach(turno => {
                const option = document.createElement('option');
                option.value = turno.id; // Ahora guarda el id del turno
                option.textContent = turno.hora;
                if (turnoIdSeleccionado && turno.id == turnoIdSeleccionado) {
                    option.selected = true;
                }
                selectHora.appendChild(option);
            });
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function () {
        // Obtener valores iniciales si estamos en edición
        const especialidadSelect = document.getElementById('especialidad_id');
        const medicoSelect = document.getElementById('medico_id');
        const especialidadIdInicial = especialidadSelect.value;
        const medicoIdInicial = medicoSelect ? medicoSelect.value : null;

        // Si hay una especialidad seleccionada, cargar los médicos
        if (especialidadIdInicial) {
            cargarMedicos(especialidadIdInicial, medicoIdInicial);
        }

        // Event listener para cambio de especialidad
        document.getElementById('especialidad_id').addEventListener('change', function () {
            cargarMedicos(this.value);
        });

        // Event listener para cambio de médico
        document.getElementById('medico_id').addEventListener('change', function () {
            cargarTurnos(this.value);
        });

        // Event listener para cambio de fecha
        document.getElementById('fecha_turno').addEventListener('change', function () {
            cargarHoras(this.value);
        });

        // Event listener para cambio de hora
        document.getElementById('hora_turno').addEventListener('change', function () {
            document.getElementById('turno_id').value = this.value;
        });
    });
