if (window.location.pathname.includes('/reservations/create')) {
    // Variables globales
    let todosLosTurnos = [];

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
                option.textContent = appointment.name + (appointment.shift ? ` (${appointment.shift})` : '');
                selectTurno.appendChild(option);
            });

            selectTurno.disabled = false;
        } catch (error) {
            console.error("Error al cargar lista de appointments:", error);
            selectTurno.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // Función para formatear fechas de manera segura
    function formatearFecha(dateString) {
        try {
            let fechaParte = dateString;

            if (dateString.includes('T')) {
                fechaParte = dateString.split('T')[0];
            }

            const [year, month, day] = fechaParte.split('-').map(Number);

            if (!year || !month || !day || isNaN(year) || isNaN(month) || isNaN(day)) {
                return fechaParte;
            }

            const fecha = new Date(year, month - 1, day);

            if (isNaN(fecha.getTime())) {
                return fechaParte;
            }

            return fecha.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });

        } catch (error) {
            console.error('Error al formatear fecha:', error, dateString);
            return dateString;
        }
    }

    // Función para extraer solo la fecha del formato ISO
    function extraerSoloFecha(dateString) {
        if (dateString.includes('T')) {
            return dateString.split('T')[0];
        }
        return dateString;
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

            // Usar directamente los appointments que vienen del backend (ya filtrados)
            todosLosTurnos = data.appointments;

            if (todosLosTurnos.length === 0) {
                selectFecha.innerHTML = '<option value="">Sin fechas disponibles</option>';
                return;
            }

            // Extraer solo la parte de la fecha y obtener dates únicas
            const fechasUnicas = [...new Set(todosLosTurnos.map(t => extraerSoloFecha(t.date)))].sort();

            selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';
            fechasUnicas.forEach(date => {
                const option = document.createElement('option');
                option.value = date;
                option.textContent = formatearFecha(date);
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

            if (!Array.isArray(todosLosTurnos)) {
                throw new Error('La lista de turnos no es válida');
            }

            // Filtrar appointments para la fecha seleccionada
            let availableAppointments = todosLosTurnos.filter(appointment => {
                const appointmentDate = extraerSoloFecha(appointment.date);
                return appointmentDate === fechaSeleccionada;
            });

            // Obtener fecha y hora actual en Argentina
            const ahora = new Date();
            const opciones = { timeZone: 'America/Argentina/Jujuy' };
            const fechaActual = ahora.toLocaleDateString('en-CA', opciones);
            const horaActual = ahora.toLocaleTimeString('en-CA', {
                timeZone: 'America/Argentina/Jujuy',
                hour12: false,
                hour: '2-digit',
                minute: '2-digit'
            });

            // Filtrar horarios pasados solo si es el día actual
            if (fechaSeleccionada === fechaActual) {
                availableAppointments = availableAppointments.filter(appointment => {
                    return appointment.time >= horaActual;
                });
            }

            // Ordenar y mostrar resultados
            availableAppointments.sort((a, b) => a.time.localeCompare(b.time));

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

    function actualizarAnimaciones() {
        const campos = [
            'specialty_id',
            'doctor_id',
            'appointment_name_id',
            'appointment_date',
            'appointment_time'
        ];

        // Quitar animaciones de todos
        campos.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.remove('input-animado');
        });

        // Encontrar el primer campo vacío y resaltarlo
        for (let i = 0; i < campos.length; i++) {
            const el = document.getElementById(campos[i]);
            if (el && (!el.value || el.value === "")) {
                el.classList.add('input-animado');
                break;
            }
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
            document.getElementById('specialty_id')?.addEventListener('change', function () {
                cargarMedicos(this.value);
                actualizarAnimaciones();
            });

            document.getElementById('doctor_id')?.addEventListener('change', function () {
                cargarNombres(this.value);
                actualizarAnimaciones();
            });

            document.getElementById('appointment_name_id')?.addEventListener('change', function () {
                cargarTurnos(this.value);
                actualizarAnimaciones();
            });

            document.getElementById('appointment_date')?.addEventListener('change', function () {
                cargarHoras(this.value);
                actualizarAnimaciones();
            });

            document.getElementById('appointment_time')?.addEventListener('change', function () {
                document.getElementById('appointment_id').value = this.value;
                actualizarAnimaciones();
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
        actualizarAnimaciones();
    });
}