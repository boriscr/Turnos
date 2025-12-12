if (window.location.pathname.includes('/reservations/create')) {
    // Variables globales compartidas
    let todosLosTurnos = [];
    let appointmentsData = [];

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
            selectTurno.innerHTML = '<option value="">Cargando turnos...</option>';

            if (!medicoId) {
                selectTurno.innerHTML = '<option value="">Seleccione un doctor primero</option>';
                return;
            }

            const response = await fetch(`/getAvailableReservationByName/${medicoId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

            const data = await response.json();

            // Guardar los datos de appointments para usar en la confirmación
            appointmentsData = data.appointments;

            selectTurno.innerHTML = '<option value="">Seleccione un turno</option>';
            data.appointments.forEach(appointment => {
                const option = document.createElement('option');
                option.value = appointment.id;
                option.textContent = appointment.name + (appointment.shift ? ` (${appointment.shift})` : '');
                selectTurno.appendChild(option);
            });

            selectTurno.disabled = false;
        } catch (error) {
            console.error("Error al cargar lista de turnos:", error);
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
    // Función para cargar datos de confirmación en el paso 3
    function loadConfirmationData() {
        // Elementos básicos
        const confirmationDetails = document.getElementById('confirmation-details');
        if (!confirmationDetails) return;

        // Obtener datos comunes
        const commonData = getCommonConfirmationData();
        if (!commonData) return;

        // Obtener datos del paciente según tipo
        const patientData = getPatientData();
        if (!patientData) return;

        // Generar y mostrar HTML
        confirmationDetails.innerHTML = generateConfirmationHTML(commonData, patientData);
    }

    // Obtener datos comunes (doctor, especialidad, fecha, etc.)
    function getCommonConfirmationData() {
        const specialtySelect = document.getElementById('specialty_id');
        const doctorSelect = document.getElementById('doctor_id');
        const appointmentNameSelect = document.getElementById('appointment_name_id');
        const dateSelect = document.getElementById('appointment_date');
        const timeSelect = document.getElementById('appointment_time');

        if (!specialtySelect || !doctorSelect || !appointmentNameSelect || !dateSelect || !timeSelect) {
            return null;
        }

        // Obtener textos seleccionados
        const specialtyText = specialtySelect.options[specialtySelect.selectedIndex]?.text || 'No seleccionado';
        const doctorText = doctorSelect.options[doctorSelect.selectedIndex]?.text || 'No seleccionado';
        const appointmentNameText = appointmentNameSelect.options[appointmentNameSelect.selectedIndex]?.text || 'No seleccionado';
        const dateText = dateSelect.options[dateSelect.selectedIndex]?.text || 'No seleccionado';
        const timeText = timeSelect.options[timeSelect.selectedIndex]?.text || 'No seleccionado';

        // Obtener dirección
        let address = 'No especificada';
        const selectedAppointmentId = appointmentNameSelect.value;
        if (selectedAppointmentId && appointmentsData?.length > 0) {
            const selectedAppointment = appointmentsData.find(app => app.id == selectedAppointmentId);
            address = selectedAppointment?.address || address;
        }

        return {
            doctorText,
            specialtyText,
            appointmentNameText,
            dateText,
            timeText,
            address
        };
    }

    // Obtener datos del paciente según tipo seleccionado
    function getPatientData() {
        const patientTypeMyself = document.getElementById('patient_type_myself');
        const patientTypeOther = document.getElementById('patient_type_other');

        if (patientTypeMyself?.checked) {
            const userData = extractUserDataSimple();
            return {
                ...userData,
                isMyself: true,
                image: 'https://cdn-icons-png.flaticon.com/512/149/149071.png'
            };
        }

        if (patientTypeOther?.checked) {
            const getName = (id) => document.getElementById(id)?.value || '';
            return {
                name: getName('third_party_name'),
                surname: getName('third_party_surname'),
                dni: getName('third_party_idNumber'),
                email: getName('third_party_email'),
                isMyself: false,
                image: 'https://thumbs.dreamstime.com/b/ilustraci%C3%B3n-de-l%C3%ADnea-espera-concepto-y-paciencia-un-hombre-sentado-en-una-silla-con-tel%C3%A9fono-m%C3%B3vil-la-mano-esperando-algo-195256198.jpg'
            };
        }

        return null;
    }

    // Plantilla HTML para la sección del doctor (reutilizable)
    const doctorTemplate = (doctorText, specialtyText, appointmentNameText) => `
    <div class="profile-container profile-containe-position">
        <img src="https://www.nicepng.com/png/detail/867-8678512_doctor-icon-physician.png" alt="img-profile" class="profile-img">
        <div class="profile-id">
            <p class="profile-name">Dr: ${doctorText}</p>
            <small>${specialtyText} | ${appointmentNameText}</small>
        </div>
    </div>
`;

    // Plantilla HTML para la información de la cita (reutilizable)
    const appointmentInfoTemplate = (dateText, timeText, address) => `
    <div class="card timeline-card">
        <p><i class="bi bi-calendar-check-fill me-2"></i><b>${window.confirmationData?.dateText || 'Fecha'}:</b> ${dateText}</p>
        <p><i class="bi bi-clock-fill me-2"></i><b>${window.confirmationData?.timeText || 'Horario'}:</b> ${timeText} Hs</p>
        <p><i class="bi bi-geo-alt-fill me-2"></i><b>${window.confirmationData?.addressText || 'Dirección'}:</b> ${address}</p>
    </div>
`;

    // Plantilla HTML para la sección del paciente (reutilizable)
    const patientTemplate = (patientData) => {
        const patientName = patientData.isMyself
            ? patientData.name || 'Usuario'
            : `${patientData.name} ${patientData.surname}`.trim();

        return `
        <div class="profile-container profile-containe-position">
            <img src="${patientData.image}" alt="img-profile" class="profile-img">
            <div class="profile-id">
                <p class="profile-name">Paciente: ${patientName}</p>
                <small>DNI: ${patientData.dni || ''} ${patientData.email ? `- Email: ${patientData.email}` : ''}</small>
            </div>
        </div>
    `;
    };

    // Generar HTML completo de confirmación
    function generateConfirmationHTML(commonData, patientData) {
        return `
        <div class="content-date-profile width-profile-doctor">
            <div class="timeline-container">
                ${doctorTemplate(commonData.doctorText, commonData.specialtyText, commonData.appointmentNameText)}
                
                <div class="timeline-center-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                ${appointmentInfoTemplate(commonData.dateText, commonData.timeText, commonData.address)}
                
                ${patientTemplate(patientData)}
            </div>
        </div>
    `;
    }

    // Función para extraer datos del usuario actual
    function extractUserDataSimple() {
        const userSection = document.getElementById('for-self-section');
        if (!userSection) return { name: '', dni: '', address: '', phone: '', email: '' };

        const paragraphs = userSection.querySelectorAll('p');
        return {
            name: paragraphs[0]?.textContent.trim() || '',
            dni: paragraphs[1]?.textContent.trim() || '',
            address: paragraphs[2]?.textContent.trim() || '',
            phone: paragraphs[3]?.textContent.trim() || '',
            email: paragraphs[4]?.textContent.trim() || ''
        };
    }


    // Configuración del formulario multi-paso
    function setupMultiStepForm() {
        // Configuración dinámica según tema
        function getCurrentTheme() {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        const isDarkMode = getCurrentTheme() === 'dark';

        const form = document.getElementById('multiStepForm');
        const confirmBtn = document.querySelector('#multiStepForm [type="submit"]');

        // Cargar datos en el paso 3 cuando se avance desde el paso 2
        document.querySelectorAll('.next-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                if (this.closest('.form-step')?.dataset.step === "2") {
                    loadConfirmationData();
                }
            });
        });

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function (e) {
                e.preventDefault();

                // Validación básica
                const requiredFields = ['specialty_id', 'doctor_id', 'appointment_name_id',
                    'appointment_date', 'appointment_time'
                ];
                const isValid = requiredFields.every(field => {
                    const element = document.getElementById(field);
                    return element && element.value;
                });

                if (!isValid) {
                    Swal.fire({
                        title: 'Campos incompletos',
                        text: 'Por favor complete todos los campos del formulario',
                        icon: 'warning',
                    });
                    return;
                }

                // Mostrar confirmación
                Swal.fire({
                    title: '¿Confirmar turno?',
                    html: `<p>${window.confirmationData?.patientMessage || 'Por favor confirme su turno.'}</p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--primary_color_btn)',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    background: isDarkMode ? 'var(--dark_application_background)' :
                        'var(--light_application_background)',
                    color: isDarkMode ? 'var(--dark_text_color)' : 'var(--light_text_color)',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return new Promise((resolve) => {
                            // Simular procesamiento en frontend (3 segundos)
                            setTimeout(() => {
                                resolve();
                            }, 3000);
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Deshabilitar el botón para evitar múltiples clics
                        confirmBtn.disabled = true;
                        confirmBtn.classList.add('submitting');

                        // Mostrar loader personalizado adicional
                        if (typeof showLoader === 'function') {
                            showLoader('Procesando confirmación...');
                        }

                        // Crear campo hidden para indicar el retardo de testing
                        const testingField = document.createElement('input');
                        testingField.type = 'hidden';
                        testingField.name = 'testing_concurrency';
                        testingField.value = 'true';
                        form.appendChild(testingField);

                        // Enviar formulario
                        form.submit();
                    }
                });
            });
        }
    }

    // Event listeners principales
    document.addEventListener('DOMContentLoaded', function () {
        try {
            // Obtener valores iniciales si existen
            const especialidadInicial = document.getElementById('specialty_id')?.value;
            const medicoInicial = document.getElementById('doctor_id')?.value;
            const nombreInicial = document.getElementById('appointment_name_id')?.value;
            const fechaInicial = document.getElementById('appointment_date')?.value;
            const turnoIdInicial = document.getElementById('appointment_time')?.value;

            // Configurar event listeners para los selects
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

            // Configurar formulario multi-paso
            setupMultiStepForm();

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