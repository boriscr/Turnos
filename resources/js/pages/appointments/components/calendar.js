if (window.location.pathname.includes('/appointments/create') || window.location.pathname.includes('/appointments/edit')) {

    document.addEventListener('alpine:init', () => {
        Alpine.data('iosCalendar', () => ({
            currentDate: new Date(),
            selectedDates: [],
            mode: 'single',
            skipWeekends: true,
            monthNames: [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ],
            MAX_COMBINATIONS: 10000, // Límite máximo
            currentCombinations: 0,
            exceededDates: new Set(), // Fechas que exceden el límite

            get selectedDatesJSON() {
                return JSON.stringify(this.selectedDates.map(date => this.formatDate(date)));
            },

            get currentYear() {
                return this.currentDate.getFullYear();
            },

            get currentMonth() {
                return this.currentDate.getMonth();
            },

            get days() {
                const year = this.currentYear;
                const month = this.currentMonth;

                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);

                const daysInMonth = lastDay.getDate();
                const startingDay = firstDay.getDay();

                const days = [];

                for (let i = 0; i < startingDay; i++) {
                    days.push(null);
                }

                for (let i = 1; i <= daysInMonth; i++) {
                    days.push(new Date(year, month, i));
                }

                const totalCells = Math.ceil(days.length / 7) * 7;
                while (days.length < totalCells) {
                    days.push(null);
                }

                return days;
            },

            init(preselected = []) {
                this.currentDate = new Date();
                this.selectedDates = preselected.map(dateStr => {
                    const date = new Date(dateStr + 'T00:00:00Z');
                    return new Date(date.getTime() + date.getTimezoneOffset() * 60000);
                });
                this.calcularCombinacionesActuales();
            },

            // Calcular combinaciones actuales
            calcularCombinacionesActuales() {
                const timeSlotsInput = document.getElementById('available_time_slots');
                const appointmentType = document.querySelector('input[name="appointment_type"]:checked')?.value;

                let timeSlotsCount = 1;
                if (appointmentType === 'multi_slot' && timeSlotsInput && timeSlotsInput.value) {
                    try {
                        const slots = JSON.parse(timeSlotsInput.value);
                        timeSlotsCount = slots.length;
                    } catch (e) {
                        timeSlotsCount = 1;
                    }
                }

                this.currentCombinations = this.selectedDates.length * timeSlotsCount;
                this.actualizarEstadoExcedido();
            },

            // Actualizar estado de fechas excedidas
            actualizarEstadoExcedido() {
                this.exceededDates.clear();

                if (this.currentCombinations <= this.MAX_COMBINATIONS) {
                    return;
                }

                const timeSlotsInput = document.getElementById('available_time_slots');
                const appointmentType = document.querySelector('input[name="appointment_type"]:checked')?.value;
                let timeSlotsCount = 1;

                if (appointmentType === 'multi_slot' && timeSlotsInput && timeSlotsInput.value) {
                    try {
                        const slots = JSON.parse(timeSlotsInput.value);
                        timeSlotsCount = slots.length;
                    } catch (e) {
                        timeSlotsCount = 1;
                    }
                }

                // Calcular cuántas fechas podemos mantener
                const fechasPermitidas = Math.floor(this.MAX_COMBINATIONS / timeSlotsCount);
                const fechasExcedidas = this.selectedDates.slice(fechasPermitidas);

                // Marcar fechas excedidas
                fechasExcedidas.forEach(date => {
                    this.exceededDates.add(date.toDateString());
                });
            },

            // Verificar si una fecha excede el límite
            isExceeded(date) {
                if (!date) return false;
                return this.exceededDates.has(date.toDateString());
            },

            previousMonth() {
                this.currentDate = new Date(this.currentYear, this.currentMonth - 1, 1);
            },

            nextMonth() {
                this.currentDate = new Date(this.currentYear, this.currentMonth + 1, 1);
            },

            formatDate(date) {
                if (!date) return null;
                return date.toISOString().split('T')[0];
            },

            isToday(date) {
                if (!date) return false;
                const today = new Date();
                return date.getDate() === today.getDate() &&
                    date.getMonth() === today.getMonth() &&
                    date.getFullYear() === today.getFullYear();
            },

            isWeekend(date) {
                if (!date) return false;
                return date.getDay() === 0 || date.getDay() === 6;
            },

            isSelected(date) {
                if (!date) return false;
                return this.selectedDates.some(d => d.toDateString() === date.toDateString());
            },

            isInRange(date) {
                if (this.mode !== 'range' || this.selectedDates.length < 2) return false;

                const start = this.selectedDates[0];
                const end = this.selectedDates[this.selectedDates.length - 1];

                return date >= start && date <= end;
            },

            toggleWeekends() {
                this.skipWeekends = !this.skipWeekends;
                if (this.skipWeekends) {
                    this.selectedDates = this.selectedDates.filter(date => !this.isWeekend(date));
                    this.calcularCombinacionesActuales();
                }
            },

            updateSelectedDatesInput() {
                this.$refs.selectedDatesInput.value = this.selectedDatesJSON;
            },

            selectDate(date) {
                if (!date || (this.skipWeekends && this.isWeekend(date))) return;

                const index = this.selectedDates.findIndex(d => d.toDateString() === date.toDateString());

                if (index >= 0) {
                    // Fecha ya seleccionada - deseleccionar
                    this.selectedDates.splice(index, 1);
                } else {
                    if (this.mode === 'single') {
                        // Modo día único: agregar a la selección
                        this.selectedDates.push(date);
                    } else {
                        // Modo rango
                        if (this.selectedDates.length === 0 || this.selectedDates.length > 1) {
                            this.selectedDates = [date];
                        } else {
                            const start = this.selectedDates[0];
                            const end = date;

                            const [startDate, endDate] = start < end ? [start, end] : [end, start];

                            const allDates = [];
                            let currentDate = new Date(startDate);

                            while (currentDate <= endDate) {
                                if (!this.skipWeekends || !this.isWeekend(currentDate)) {
                                    allDates.push(new Date(currentDate));
                                }
                                currentDate.setDate(currentDate.getDate() + 1);
                            }

                            this.selectedDates = allDates;
                        }
                    }
                }

                this.calcularCombinacionesActuales();

                // Mostrar alerta si se excede el límite
                if (this.currentCombinations > this.MAX_COMBINATIONS) {
                    this.mostrarAlertaLimite();
                }
            },

            // En el método mostrarAlertaLimite del calendario, actualizar el mensaje:
            mostrarAlertaLimite() {
                const timeSlotsInput = document.getElementById('available_time_slots');
                const appointmentType = document.querySelector('input[name="appointment_type"]:checked')?.value;

                let timeSlotsCount = 1;
                let timeSlotsText = '1 horario';

                if (appointmentType === 'multi_slot' && timeSlotsInput && timeSlotsInput.value) {
                    try {
                        const slots = JSON.parse(timeSlotsInput.value);
                        timeSlotsCount = slots.length;
                        timeSlotsText = slots.length + ' horarios';
                    } catch (e) {
                        timeSlotsCount = 1;
                    }
                }

                const fechasPermitidas = Math.floor(this.MAX_COMBINATIONS / timeSlotsCount);
                const fechasExcedidas = this.selectedDates.length - fechasPermitidas;

                Swal.fire({
                    icon: 'warning',
                    title: 'Límite excedido',
                    html: `
            <div style="text-align: left;">
            <ul>
                    <li>Has excedido el límite máximo de <strong>${this.MAX_COMBINATIONS.toLocaleString()} combinaciones</strong>.</li>
                    <li><strong>Se crearán:</strong></li>
                    <li>Fechas: <strong style="color: green;">${fechasPermitidas}</strong> (primeras seleccionadas)</li>
                    <li>Horarios por fecha: ${timeSlotsText}</li>
                    <li>Total a crear: <strong style="color: green;">${(fechasPermitidas * timeSlotsCount).toLocaleString()}</strong> combinaciones</li>
                    <li><strong>No se crearán:</strong></li>
                </ul>
                <ul>
                    <li>Fechas: <strong style="color: red;">${fechasExcedidas}</strong> (las últimas seleccionadas)</li>
                    <li>Las fechas en <strong style="color: red;">rojo</strong> no se guardarán.</li>
                </ul>
                <h5 class="text-sm">Puedes continuar y el sistema creará automáticamente las primeras ${fechasPermitidas} fechas.</h5>
            </div>
        `,
                    confirmButtonText: 'Entendido, continuar',
                    confirmButtonColor: '#3085d6',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    cancelButtonColor: '#d33'
                });
            },

            // Escuchar cambios en los horarios
            initEventListeners() {
                // Escuchar cambios en los radios de tipo de appointment
                document.querySelectorAll('input[name="appointment_type"]').forEach(radio => {
                    radio.addEventListener('change', () => {
                        setTimeout(() => this.calcularCombinacionesActuales(), 100);
                    });
                });

                // Escuchar cambios en los horarios (usando MutationObserver para cambios dinámicos)
                const observer = new MutationObserver(() => {
                    this.calcularCombinacionesActuales();
                });

                const timeSlotsInput = document.getElementById('available_time_slots');
                if (timeSlotsInput) {
                    observer.observe(timeSlotsInput, {
                        attributes: true,
                        attributeFilter: ['value']
                    });
                }
            }
        }));
    });

    // Inicializar event listeners después de que Alpine cargue
    document.addEventListener('alpine:initialized', () => {
        setTimeout(() => {
            const alpineComponent = document.querySelector('[x-data="iosCalendar()"]');
            if (alpineComponent && alpineComponent.__x) {
                alpineComponent.__x.$data.initEventListeners();
            }
        }, 1000);
    });
}