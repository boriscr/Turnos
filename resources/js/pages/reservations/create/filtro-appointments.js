if (window.location.pathname.includes('/reservations/create')) {
    // Variables globales
    let todosLosTurnos = [];
    let previewSettings = { amount: 30, unit: 'day' }; // Valores por defecto

    // Función para obtener date actual en formato ISO (YYYY-MM-DD)
    function obtenerFechaActual() {
        try {
            const ahora = new Date().toLocaleString('en-CA', { timeZone: 'America/Argentina/Jujuy' });
            return ahora.split(',')[0];
        } catch (error) {
            console.error("Error al obtener date actual:", error);
            return new Date().toISOString().split('T')[0]; // Fallback
        }
    }

    // Función para calcular date límite según configuración
    function calcularFechaLimite() {
        try {
            const ahora = new Date();
            switch (previewSettings.unit) {
                case 'time':
                    ahora.setHours(ahora.getHours() + previewSettings.amount);
                    break;
                case 'month':
                    ahora.setMonth(ahora.getMonth() + previewSettings.amount);
                    break;
                case 'day':
                default:
                    ahora.setDate(ahora.getDate() + previewSettings.amount);
                    break;
            }
            return ahora.toISOString().split('T')[0];
        } catch (error) {
            console.error("Error al calcular date límite:", error);
            // Fallback: 30 días en el futuro
            const fallback = new Date();
            fallback.setDate(fallback.getDate() + 30);
            return fallback.toISOString().split('T')[0];
        }
    }

    // Función para filtrar appointments según configuración
    function filtrarTurnos(appointments) {
        try {
            const fechaLimite = calcularFechaLimite();
            const fechaActual = obtenerFechaActual();
            const ahora = new Date().toLocaleString('en-CA', { timeZone: 'America/Argentina/Jujuy' });
            const horaActual = ahora.split(',')[1] ? ahora.split(',')[1].trim() : '00:00';

            return appointments.filter(appointment => {
                // Filtro por date límite
                if (appointment.date > fechaLimite) return false;

                // Filtro para appointments del día actual
                if (appointment.date === fechaActual) {
                    const turnoDate = new Date(`${appointment.date}T${appointment.time}:00`);
                    const ahoraDate = new Date(`${fechaActual}T${horaActual}:00`);
                    return turnoDate.getTime() >= ahoraDate.getTime();
                }

                return true;
            });
        } catch (error) {
            console.error("Error al filtrar appointments:", error);
            return []; // Retornar array vacío en caso de error
        }
    }

    // Función para limpiar selects dependientes
    function limpiarSelectsDependientes(selectInicial) {
        const selects = ['specialty_id', 'doctor_id', 'appointment_name_id', 'appointment_date', 'appointment_time'];
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

    // Función para cargar doctores basados en specialty seleccionada
    async function cargarMedicos(especialidadId) {
        const selectMedico = document.getElementById('doctor_id');
        if (!selectMedico) return;

        try {
            limpiarSelectsDependientes('specialty_id');
            selectMedico.disabled = true;
            selectMedico.innerHTML = '<option value="">Cargando doctores...</option>';

            if (!especialidadId) {
                selectMedico.innerHTML = '<option value="">Seleccione un doctor</option>';
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
            console.error("Error al cargar lista de doctores:", error);
            selectMedico.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Función para cargar el name de los appointments basados en el doctor seleccionado
    async function cargarNombres(medicoId) {
        const selectTurno = document.getElementById('appointment_name_id');
        if (!selectTurno) return;

        try {
            limpiarSelectsDependientes('doctor_id');
            selectTurno.disabled = true;
            selectTurno.innerHTML = '<option value="">Cargando appointments...</option>';

            if (!medicoId) {
                selectTurno.innerHTML = '<option value="">Seleccione un doctor primero</option>';
                return;
            }

            const response = await fetch(`/getAvailableReservationByName/${medicoId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();

            selectTurno.innerHTML = '<option value="">Seleccione un appointment</option>';
            data.appointments.forEach(appointment => {
                const option = document.createElement('option');
                option.value = appointment.id;
                option.textContent = appointment.name;
                selectTurno.appendChild(option);
            });

            selectTurno.disabled = false;
        } catch (error) {
            console.error("Error al cargar lista de appointments:", error);
            selectTurno.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Función para cargar appointments basados en el name de appointment seleccionado
    async function cargarTurnos(turnoNombreId) {
        const selectFecha = document.getElementById('appointment_date');
        if (!selectFecha) return;

        try {
            limpiarSelectsDependientes('appointment_name_id');
            selectFecha.disabled = true;
            selectFecha.innerHTML = '<option value="">Cargando fechas disponibles...</option>';

            if (!turnoNombreId) {
                selectFecha.innerHTML = '<option value="">Seleccione un turno primero</option>';
                return;
            }

            const response = await fetch(`/getAvailableReservationByDoctor/${turnoNombreId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const data = await response.json();

            // Guardar configuración de previsualización
            if (data.preview_settings) {
                previewSettings = data.preview_settings;
            }

            // Filtrar appointments en el frontend como capa adicional
            todosLosTurnos = filtrarTurnos(data.appointments);

            if (todosLosTurnos.length === 0) {
                selectFecha.innerHTML = '<option value="">Sin fechas disponibles</option>';
                return;
            }

            // Obtener available_dates únicas de los appointments
            const fechasUnicas = [...new Set(todosLosTurnos.map(t => t.date))];

            selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';
            fechasUnicas.forEach(date => {
                const fechaObj = new Date(date);
                const option = document.createElement('option');
                option.value = date;
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

    // Función para cargar horas basadas en date seleccionada
    async function cargarHoras(fechaSeleccionada) {
        const selectHora = document.getElementById('appointment_time');
        if (!selectHora) return;

        try {
            limpiarSelectsDependientes('appointment_date');
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

            // Filtrar los appointments disponibles para esa date
            let availableAppointments = todosLosTurnos.filter(appointment => appointment.date === fechaSeleccionada);

            // Obtener date y time actual en zona horaria de Jujuy
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
                availableAppointments = availableAppointments.filter(appointment => {
                    try {
                        const turnoDate = new Date(`${fechaSeleccionada}T${appointment.time}:00`);
                        const ahoraDate = new Date(`${fechaActual}T${horaActual}:00`);
                        return turnoDate.getTime() >= ahoraDate.getTime();
                    } catch (error) {
                        console.error('Error al comparar fechas disponibles:', error);
                        return false;
                    }
                });
            }

            // Mostrar resultados
            if (availableAppointments.length === 0) {
                selectHora.innerHTML = '<option value="">Sin horario disponible</option>';
            } else {
                selectHora.innerHTML = '<option value="">Seleccione un horario</option>';
                availableAppointments.forEach(appointment => {
                    const option = document.createElement('option');
                    option.value = appointment.id;
                    option.textContent = appointment.time;
                    selectHora.appendChild(option);
                });
                selectHora.disabled = false;
            }
        } catch (error) {
            console.error('Error en cargar Horas:', error);
            selectHora.innerHTML = '<option value="">Error al cargar horarios</option>';
        }
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function () {
        try {
            // Obtener valores iniciales si existen
            const especialidadInicial = document.getElementById('specialty_id')?.value;
            const medicoInicial = document.getElementById('doctor_id')?.value;
            const nombreInicial = document.getElementById('appointment_name_id')?.value;
            const fechaInicial = document.getElementById('appointment_date')?.value;
            const turnoIdInicial = document.getElementById('appointment_time')?.value;

            // Configurar event listeners
            document.getElementById('specialty_id')?.addEventListener('change', function() {
                cargarMedicos(this.value);
            });

            document.getElementById('doctor_id')?.addEventListener('change', function() {
                cargarNombres(this.value);
            });

            document.getElementById('appointment_name_id')?.addEventListener('change', function() {
                cargarTurnos(this.value);
            });

            document.getElementById('appointment_date')?.addEventListener('change', function() {
                cargarHoras(this.value);
            });

            document.getElementById('appointment_time')?.addEventListener('change', function() {
                document.getElementById('appointment_id').value = this.value;
            });

            // Cargar datos iniciales si existen
            if (especialidadInicial) {
                cargarMedicos(especialidadInicial).then(() => {
                    if (medicoInicial) {
                        document.getElementById('doctor_id').value = medicoInicial;
                        cargarNombres(medicoInicial).then(() => {
                            if (nombreInicial) {
                                document.getElementById('appointment_name_id').value = nombreInicial;
                                cargarTurnos(nombreInicial).then(() => {
                                    if (fechaInicial) {
                                        document.getElementById('appointment_date').value = fechaInicial;
                                        cargarHoras(fechaInicial).then(() => {
                                            if (turnoIdInicial) {
                                                document.getElementById('appointment_time').value = turnoIdInicial;
                                                document.getElementById('appointment_id').value = turnoIdInicial;
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