if (window.location.pathname.includes('/availableAppointments/create')) {
    // Variables globales
    let todosLosTurnos = [];
    let previewSettings = { amount: 30, unit: 'dia' }; // Valores por defecto

    // Función para obtener fecha actual en formato ISO (YYYY-MM-DD)
    function obtenerFechaActual() {
        try {
            const ahora = new Date().toLocaleString('en-CA', { timeZone: 'America/Argentina/Jujuy' });
            return ahora.split(',')[0];
        } catch (error) {
            console.error("Error al obtener fecha actual:", error);
            return new Date().toISOString().split('T')[0]; // Fallback
        }
    }

    // Función para calcular fecha límite según configuración
    function calcularFechaLimite() {
        try {
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
        } catch (error) {
            console.error("Error al calcular fecha límite:", error);
            // Fallback: 30 días en el futuro
            const fallback = new Date();
            fallback.setDate(fallback.getDate() + 30);
            return fallback.toISOString().split('T')[0];
        }
    }

    // Función para filtrar turnos según configuración
    function filtrarTurnos(turnos) {
        try {
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
        } catch (error) {
            console.error("Error al filtrar turnos:", error);
            return []; // Retornar array vacío en caso de error
        }
    }

    // Función para limpiar selects dependientes
    function limpiarSelectsDependientes(selectInicial) {
        const selects = ['specialty_id', 'doctor_id', 'turno_nombre_id', 'fecha_turno', 'hora_turno'];
        const indexInicial = selects.indexOf(selectInicial);
        
        if (indexInicial === -1) return;
        
        // Limpiar todos los selects posteriores al que cambió
        for (let i = indexInicial + 1; i < selects.length; i++) {
            const select = document.getElementById(selects[i]);
            if (select) {
                select.innerHTML = `<option value="">Seleccione una opción</option>`;
                select.disabled = true;
            }
        }
    }

    // Función para cargar médicos basados en specialty seleccionada
    async function cargarMedicos(especialidadId) {
        const selectMedico = document.getElementById('doctor_id');
        if (!selectMedico) return;

        try {
            limpiarSelectsDependientes('specialty_id');
            selectMedico.disabled = true;
            selectMedico.innerHTML = '<option value="">Cargando médicos...</option>';

            if (!especialidadId) {
                selectMedico.innerHTML = '<option value="">Seleccione un médico</option>';
                return;
            }

            const response = await fetch(`/getDoctorsBySpecialty/${especialidadId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();

            selectMedico.innerHTML = '<option value="">Seleccione un profesional</option>';
            data.doctors.forEach(doctor => {
                const option = document.createElement('option');
                option.value = doctor.id;
                option.textContent = `${doctor.name} ${doctor.surname}`;
                selectMedico.appendChild(option);
            });

            selectMedico.disabled = false;
        } catch (error) {
            console.error("Error al cargar lista de médicos:", error);
            selectMedico.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Función para cargar el name de los turnos basados en el médico seleccionado
    async function cargarNombres(medicoId) {
        const selectTurno = document.getElementById('turno_nombre_id');
        if (!selectTurno) return;

        try {
            limpiarSelectsDependientes('doctor_id');
            selectTurno.disabled = true;
            selectTurno.innerHTML = '<option value="">Cargando turnos...</option>';

            if (!medicoId) {
                selectTurno.innerHTML = '<option value="">Seleccione un médico primero</option>';
                return;
            }

            const response = await fetch(`/getAvailableAppointmentsByName/${medicoId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();

            selectTurno.innerHTML = '<option value="">Seleccione un turno</option>';
            data.turnos.forEach(turno => {
                const option = document.createElement('option');
                option.value = turno.id;
                option.textContent = turno.name;
                selectTurno.appendChild(option);
            });

            selectTurno.disabled = false;
        } catch (error) {
            console.error("Error al cargar lista de turnos:", error);
            selectTurno.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Función para cargar turnos basados en el name de turno seleccionado
    async function cargarTurnos(turnoNombreId) {
        const selectFecha = document.getElementById('fecha_turno');
        if (!selectFecha) return;

        try {
            limpiarSelectsDependientes('turno_nombre_id');
            selectFecha.disabled = true;
            selectFecha.innerHTML = '<option value="">Cargando fechas...</option>';

            if (!turnoNombreId) {
                selectFecha.innerHTML = '<option value="">Seleccione un turno primero</option>';
                return;
            }

            const response = await fetch(`/getAvailableAppointmentsByDoctor/${turnoNombreId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();

            // Guardar configuración de previsualización
            if (data.preview_settings) {
                previewSettings = data.preview_settings;
            }

            // Filtrar turnos en el frontend como capa adicional
            todosLosTurnos = filtrarTurnos(data.turnos);

            if (todosLosTurnos.length === 0) {
                selectFecha.innerHTML = '<option value="">Sin fechas disponibles</option>';
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
                selectFecha.appendChild(option);
            });

            selectFecha.disabled = false;
        } catch (error) {
            console.error("Error al cargar turnos:", error);
            selectFecha.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Función para cargar horas basadas en fecha seleccionada
    async function cargarHoras(fechaSeleccionada) {
        const selectHora = document.getElementById('hora_turno');
        if (!selectHora) return;

        try {
            limpiarSelectsDependientes('fecha_turno');
            selectHora.disabled = true;
            selectHora.innerHTML = '<option value="">Cargando horarios...</option>';

            if (!fechaSeleccionada) {
                selectHora.innerHTML = '<option value="">Seleccione una fecha primero</option>';
                return;
            }

            // Validación de todosLosTurnos
            if (!Array.isArray(todosLosTurnos)) {
                throw new Error('La lista de turnos no es válida');
            }

            // Filtrar los turnos disponibles para esa fecha
            let availableAppointments = todosLosTurnos.filter(turno => turno.fecha === fechaSeleccionada);

            // Obtener fecha y hora actual en zona horaria de Jujuy
            const fechaActual = obtenerFechaActual();
            let horaActual = '00:00';
            try {
                const ahora = new Date().toLocaleString('en-CA', { timeZone: 'America/Argentina/Jujuy' });
                horaActual = ahora.split(',')[1] ? ahora.split(',')[1].trim() : '00:00';
            } catch (error) {
                console.error('Error al obtener hora actual:', error);
            }

            // Filtrar horarios pasados si es el día actual
            if (fechaSeleccionada === fechaActual) {
                availableAppointments = availableAppointments.filter(turno => {
                    try {
                        const turnoDate = new Date(`${fechaSeleccionada}T${turno.hora}:00`);
                        const ahoraDate = new Date(`${fechaActual}T${horaActual}:00`);
                        return turnoDate.getTime() >= ahoraDate.getTime();
                    } catch (error) {
                        console.error('Error al comparar fechas:', error);
                        return false;
                    }
                });
            }

            // Mostrar resultados
            if (availableAppointments.length === 0) {
                selectHora.innerHTML = '<option value="">Sin horario disponible</option>';
            } else {
                selectHora.innerHTML = '<option value="">Seleccione un horario</option>';
                availableAppointments.forEach(turno => {
                    const option = document.createElement('option');
                    option.value = turno.id;
                    option.textContent = turno.hora;
                    selectHora.appendChild(option);
                });
                selectHora.disabled = false;
            }
        } catch (error) {
            console.error('Error en cargarHoras:', error);
            selectHora.innerHTML = '<option value="">Error al cargar horarios</option>';
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function () {
        try {
            // Obtener valores iniciales si existen
            const especialidadInicial = document.getElementById('specialty_id')?.value;
            const medicoInicial = document.getElementById('doctor_id')?.value;
            const nombreInicial = document.getElementById('turno_nombre_id')?.value;
            const fechaInicial = document.getElementById('fecha_turno')?.value;
            const turnoIdInicial = document.getElementById('hora_turno')?.value;

            // Configurar event listeners
            document.getElementById('specialty_id')?.addEventListener('change', function() {
                cargarMedicos(this.value);
            });

            document.getElementById('doctor_id')?.addEventListener('change', function() {
                cargarNombres(this.value);
            });

            document.getElementById('turno_nombre_id')?.addEventListener('change', function() {
                cargarTurnos(this.value);
            });

            document.getElementById('fecha_turno')?.addEventListener('change', function() {
                cargarHoras(this.value);
            });

            document.getElementById('hora_turno')?.addEventListener('change', function() {
                document.getElementById('turno_id').value = this.value;
            });

            // Cargar datos iniciales si existen
            if (especialidadInicial) {
                cargarMedicos(especialidadInicial).then(() => {
                    if (medicoInicial) {
                        document.getElementById('doctor_id').value = medicoInicial;
                        cargarNombres(medicoInicial).then(() => {
                            if (nombreInicial) {
                                document.getElementById('turno_nombre_id').value = nombreInicial;
                                cargarTurnos(nombreInicial).then(() => {
                                    if (fechaInicial) {
                                        document.getElementById('fecha_turno').value = fechaInicial;
                                        cargarHoras(fechaInicial).then(() => {
                                            if (turnoIdInicial) {
                                                document.getElementById('hora_turno').value = turnoIdInicial;
                                                document.getElementById('turno_id').value = turnoIdInicial;
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            }
        } catch (error) {
            console.error("Error en inicialización:", error);
        }
    });
}