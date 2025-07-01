if (window.location.pathname.includes('/disponibles/create')) {
    // Variables globales
    let todosLosTurnos = [];
    let medicoInicial = document.getElementById('medico_id')?.value;
    let especialidadInicial = document.getElementById('especialidad_id')?.value;
    let fechaInicial = document.getElementById('fecha_turno')?.value;
    let turnoIdInicial = document.getElementById('hora_turno')?.value;
    let previewSettings = { amount: 30, unit: 'dia' }; // Valores por defecto

    // Función para obtener fecha actual en formato ISO (YYYY-MM-DD)
    function obtenerFechaActual() {
        const ahora = new Date().toLocaleString('en-CA', { timeZone: 'America/Argentina/Jujuy' });
        return ahora.split(',')[0];
    }

    // Función para calcular fecha límite según configuración
    function calcularFechaLimite() {
        const ahora = new Date();
        switch (previewSettings.unit) {
            case 'hora':
                ahora.setHours(ahora.getHours() + previewSettings.amount);
                break;
            case 'mes':
                ahora.setMonth(ahora.getMonth() + previewSettings.amount);
                break;
            case 'dia':
            default:
                ahora.setDate(ahora.getDate() + previewSettings.amount);
                break;
        }
        return ahora.toISOString().split('T')[0];
    }

    // Función para filtrar turnos según configuración
    function filtrarTurnos(turnos) {
        const fechaLimite = calcularFechaLimite();
        const fechaActual = obtenerFechaActual();
        const ahora = new Date().toLocaleString('en-CA', { timeZone: 'America/Argentina/Jujuy' });
        const horaActual = ahora.split(',')[1] ? ahora.split(',')[1].trim() : '00:00';

        return turnos.filter(turno => {
            // Filtro por fecha límite
            if (turno.fecha > fechaLimite) return false;
            
            // Filtro para turnos del día actual
            if (turno.fecha === fechaActual) {
                const turnoDate = new Date(`${turno.fecha}T${turno.hora}:00`);
                const ahoraDate = new Date(`${fechaActual}T${horaActual}:00`);
                return turnoDate.getTime() >= ahoraDate.getTime();
            }
            
            return true;
        });
    }

    // Función para cargar médicos basados en especialidad seleccionada
    async function cargarMedicos(especialidadId, medicoSeleccionado = null) {
        const selectMedico = document.getElementById('medico_id');
        const selectFecha = document.getElementById('fecha_turno');
        const selectHora = document.getElementById('hora_turno');

        selectMedico.innerHTML = '<option value="">Seleccione un médico</option>';
        selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';
        selectHora.innerHTML = '<option value="">Seleccione una hora</option>';

        if (!especialidadId) return;

        selectMedico.disabled = true;
        selectMedico.innerHTML = '<option value="">Cargando médicos...</option>';

        try {
            const response = await fetch(`/getMedicosPorEspecialidad/${especialidadId}`);
            const data = await response.json();

            selectMedico.innerHTML = '<option value="">Seleccione un profesional</option>';
            data.medicos.forEach(medico => {
                const option = document.createElement('option');
                option.value = medico.id;
                option.textContent = `${medico.nombre} ${medico.apellido}`;
                if (medicoSeleccionado && medico.id == medicoSeleccionado) {
                    option.selected = true;
                }
                selectMedico.appendChild(option);
            });

            selectMedico.disabled = false;

            // Si hay un médico seleccionado, cargar sus turnos
            if (medicoSeleccionado) {
                await cargarTurnos(medicoSeleccionado, fechaInicial, turnoIdInicial);
            }
        } catch (error) {
            console.error("Error al cargar lista de médicos:", error);
            selectMedico.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Función para cargar turnos basados en el médico seleccionado
    async function cargarTurnos(medicoId, fechaSeleccionada = null, turnoIdSeleccionado = null) {
        const selectFecha = document.getElementById('fecha_turno');
        const selectHora = document.getElementById('hora_turno');

        selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';
        selectHora.innerHTML = '<option value="">Seleccione un horario</option>';

        if (!medicoId) return;

        selectFecha.disabled = true;
        selectFecha.innerHTML = '<option value="">Cargando fechas...</option>';

        try {
            const response = await fetch(`/getTurnosPorEquipo/${medicoId}`);
            const data = await response.json();

            // Guardar configuración de previsualización
            if (data.preview_settings) {
                previewSettings = data.preview_settings;
            }

            // Filtrar turnos en el frontend como capa adicional
            todosLosTurnos = filtrarTurnos(data.turnos);

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
                await cargarHoras(fechaSeleccionada, turnoIdSeleccionado);
            }
        } catch (error) {
            console.error("Error al cargar turnos:", error);
            selectFecha.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Resto del código (cargarHoras y event listeners) permanece igual...
    // [Mantener las funciones cargarHoras y los event listeners del script anterior]


    // Función para cargar horas basadas en fecha seleccionada
    async function cargarHoras(fechaSeleccionada, turnoIdSeleccionado = null) {
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
                option.value = turno.id;
                option.textContent = turno.hora;
                if (turnoIdSeleccionado && turno.id == turnoIdSeleccionado) {
                    option.selected = true;
                }
                selectHora.appendChild(option);
            });
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', async function () {
        // Si hay una especialidad seleccionada, cargar los médicos
        if (especialidadInicial) {
            await cargarMedicos(especialidadInicial, medicoInicial);
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
}