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
    // Función para formatear fechas de manera segura
    function formatearFecha(dateString) {
        try {
            // Extraer solo la parte de la fecha del formato ISO (YYYY-MM-DD)
            let fechaParte = dateString;

            // Si viene en formato ISO completo, extraer solo la fecha
            if (dateString.includes('T')) {
                fechaParte = dateString.split('T')[0];
            }

            const [year, month, day] = fechaParte.split('-').map(Number);

            // Validar que sea una fecha válida
            if (!year || !month || !day || isNaN(year) || isNaN(month) || isNaN(day)) {
                return fechaParte; // Devolver el string original si no se puede parsear
            }

            const fecha = new Date(year, month - 1, day);

            // Verificar si la fecha es válida
            if (isNaN(fecha.getTime())) {
                return fechaParte;
            }

            // ELIMINAR la capitalización extra - devolver el formato natural de toLocaleDateString
            return fecha.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });

        } catch (error) {
            console.error('Error al formatear fecha:', error, dateString);
            return dateString; // Devolver el string original en caso de error
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

            // Guardar configuración de previsualización
            if (data.preview_settings) {
                previewSettings = data.preview_settings;
            }

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
                option.value = date; // Guardamos solo la parte de la fecha (YYYY-MM-DD)
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

            // Filtrar los appointments disponibles para esa date (comparando solo la parte de la fecha)
            let availableAppointments = todosLosTurnos.filter(appointment => {
                const appointmentDate = extraerSoloFecha(appointment.date);
                return appointmentDate === fechaSeleccionada;
            });

            // Obtener fecha y hora actual EN ARGENTINA
            const ahoraArgentina = new Date().toLocaleString('en-CA', { timeZone: 'America/Argentina/Jujuy' });
            const [fechaActual, horaActualCompleta] = ahoraArgentina.split(',');
            const horaActual = horaActualCompleta ? horaActualCompleta.trim().substring(0, 5) : '00:00';

            // Filtrar horarios pasados si es el día actual
            if (fechaSeleccionada === fechaActual) {
                availableAppointments = availableAppointments.filter(appointment => {
                    return appointment.time >= horaActual;
                });
            }

            // Ordenar los horarios
            availableAppointments.sort((a, b) => a.time.localeCompare(b.time));

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
    // Función para resaltar el siguiente campo (animación)
    function resaltarSiguiente(actualId, siguienteId) {
        const actual = document.getElementById(actualId);
        const siguiente = document.getElementById(siguienteId);

        // Quitar animación del actual (ya fue completado)
        if (actual) actual.classList.remove('input-animado');

        // Agregar animación al siguiente campo
        if (siguiente) siguiente.classList.add('input-animado');
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
                break; // solo uno a la vez
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