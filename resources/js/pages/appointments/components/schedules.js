//horarios de appointments formulario crear nuevo appointment
document.addEventListener("DOMContentLoaded", function () {
    if (window.location.pathname.includes('/appointments/create') || window.location.pathname.includes('/appointments/edit')) {

        let single_slot = document.getElementById("single_slot");
        let multi_slot = document.getElementById("multi_slot");
        let number_of_reservations = document.getElementById("number_of_reservations");
        let horarioBox = document.getElementById("horario-box");
        let horarioItem = document.getElementById("horarios-item");
        let start_time = document.getElementById("start_time");
        let end_time = document.getElementById("end_time");

        function convertirATiempoEnMinutos(inputTime) {
            let [horas, minutos] = inputTime.split(":").map(Number);
            return horas * 60 + minutos;
        }

        function calcularMinutosTotales(startTime, endTime) {
            let minutosInicio = convertirATiempoEnMinutos(startTime);
            let minutosFin = convertirATiempoEnMinutos(endTime);

            // Si el horario final es menor que el inicial, asumimos que cruza la medianoche
            if (minutosFin <= minutosInicio) {
                minutosFin += 24 * 60; // Agregamos 24 horas (1440 minutos)
            }

            return minutosFin - minutosInicio;
        }

        function validarCampos() {
            return start_time.value !== "" && 
                   end_time.value !== "" &&
                   start_time.value !== end_time.value;
        }

        function validarCamposMultiSlot() {
            return validarCampos() && number_of_reservations.value >= 1;
        }

        function generarHorarioSingleSlot() {
            if (single_slot.checked && start_time.value !== "") {
                horarioBox.style.display = "flex";
                
                // Para single slot, solo usamos el horario de inicio
                let horarioUnico = [start_time.value];
                
                document.getElementById("available_time_slots").value = JSON.stringify(horarioUnico);
                horarioItem.innerHTML = "<p><strong>Horario único:</strong></p><p>" + start_time.value + "</p>";
            }
        }

        function generarHorariosMultiSlot() {
            if (multi_slot.checked && validarCamposMultiSlot()) {
                horarioBox.style.display = "flex";

                let minutosTotalesInicio = convertirATiempoEnMinutos(start_time.value);
                let minutosTotalesFin = convertirATiempoEnMinutos(end_time.value);
                let minutosTotales = calcularMinutosTotales(start_time.value, end_time.value);

                // Validar que haya tiempo suficiente para al menos un turno
                if (minutosTotales < 1) {
                    horarioBox.style.display = "none";
                    document.getElementById("available_time_slots").value = "";
                    horarioItem.innerHTML = "<p class='error'>El rango horario debe ser de al menos 1 minuto</p>";
                    return;
                }

                let tiempoPorTurno = minutosTotales / number_of_reservations.value;

                // Validar que cada turno tenga al menos 1 minuto
                if (tiempoPorTurno < 1) {
                    horarioBox.style.display = "none";
                    document.getElementById("available_time_slots").value = "";
                    horarioItem.innerHTML = "<p class='error'>Demasiadas reservaciones para el rango horario. Cada turno debe tener al menos 1 minuto.</p>";
                    return;
                }

                let horarios = [];

                for (let i = 0; i < number_of_reservations.value; i++) {
                    let minutosActuales = minutosTotalesInicio + i * tiempoPorTurno;

                    // Ajustar si cruza la medianoche
                    let minutosEnDia = minutosActuales % (24 * 60);

                    let time = Math.floor(minutosEnDia / 60);
                    let minutos = Math.floor(minutosEnDia % 60);

                    let horaFormateada = time.toString().padStart(2, "0");
                    let minutosFormateados = minutos.toString().padStart(2, "0");

                    horarios.push(`${horaFormateada}:${minutosFormateados}`);
                }

                document.getElementById("available_time_slots").value = JSON.stringify(horarios);
                horarioItem.innerHTML = "<p><strong>Horarios generados:</strong></p><p>" + horarios.join("</p><p>") + "</p>";
            }
        }

        function actualizarHorarios() {
            if (single_slot.checked) {
                generarHorarioSingleSlot();
            } else if (multi_slot.checked) {
                generarHorariosMultiSlot();
            } else {
                horarioBox.style.display = "none";
                document.getElementById("available_time_slots").value = "";
                horarioItem.innerHTML = "";
            }
        }

        // Función para manejar cambios en los inputs de tiempo
        function manejarCambioTiempo() {
            if (single_slot.checked) {
                generarHorarioSingleSlot();
            } else if (multi_slot.checked) {
                if (validarCamposMultiSlot()) {
                    generarHorariosMultiSlot();
                } else {
                    horarioBox.style.display = "none";
                    document.getElementById("available_time_slots").value = "";
                    horarioItem.innerHTML = "";
                }
            }
        }

        // Función para manejar cambios en el número de reservaciones
        function manejarCambioReservaciones() {
            if (this.value < 1) {
                this.value = 1;
            }

            if (multi_slot.checked) {
                if (validarCamposMultiSlot()) {
                    generarHorariosMultiSlot();
                } else {
                    horarioBox.style.display = "none";
                    document.getElementById("available_time_slots").value = "";
                    horarioItem.innerHTML = "";
                }
            }
        }

        // Disparar automáticamente al cargar la página si ya hay valores cargados
        if (single_slot.checked && start_time.value !== "") {
            generarHorarioSingleSlot();
        } else if (multi_slot.checked && validarCamposMultiSlot()) {
            generarHorariosMultiSlot();
        }

        // Event listeners para los inputs de tiempo
        start_time.addEventListener("change", manejarCambioTiempo);
        start_time.addEventListener("input", manejarCambioTiempo);
        end_time.addEventListener("change", manejarCambioTiempo);
        end_time.addEventListener("input", manejarCambioTiempo);

        // Event listeners para el número de reservaciones
        number_of_reservations.addEventListener("input", manejarCambioReservaciones);
        number_of_reservations.addEventListener("change", manejarCambioReservaciones);

        // Control de botones de incremento/decremento
        let inputCantidad = document.getElementById('number_of_reservations');
        let decrement = document.getElementById('decrement-btn');
        let increment = document.getElementById('increment-btn');

        decrement.addEventListener('click', function () {
            if (inputCantidad.value > 1) {
                inputCantidad.value = parseInt(inputCantidad.value) - 1;
                inputCantidad.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });

        increment.addEventListener('click', function () {
            inputCantidad.value = parseInt(inputCantidad.value) + 1;
            inputCantidad.dispatchEvent(new Event('change', { bubbles: true }));
        });

        // Controlar radios
        single_slot.addEventListener("change", function () {
            if (this.checked) {
                if (start_time.value !== "") {
                    generarHorarioSingleSlot();
                } else {
                    horarioBox.style.display = "none";
                    document.getElementById("available_time_slots").value = "";
                    horarioItem.innerHTML = "";
                }
            }
        });

        multi_slot.addEventListener("change", function () {
            if (this.checked) {
                if (validarCamposMultiSlot()) {
                    generarHorariosMultiSlot();
                } else {
                    alert("Por favor, complete todos los campos con valores válidos antes de continuar.");
                    this.checked = false;
                    single_slot.checked = true;
                    // Si cambiamos a single slot, generar el horario único
                    if (start_time.value !== "") {
                        generarHorarioSingleSlot();
                    }
                }
            }
        });
    }
});