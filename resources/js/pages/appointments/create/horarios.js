//horarios de appointments formulario crear nuevo appointment
//input de tipo radio
//horarios de appointments formulario crear nuevo appointment
//input de tipo radio
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

        function generarHorarios() {
            if (multi_slot.checked && number_of_reservations.value >= 1 && start_time.value !== "" && end_time.value !== "") {
                horarioBox.style.display = "flex";

                let minutosTotalesInicio = convertirATiempoEnMinutos(start_time.value);
                let minutosTotalesFin = convertirATiempoEnMinutos(end_time.value);
                let tiempoPorTurno = (minutosTotalesFin - minutosTotalesInicio) / number_of_reservations.value;

                let horarios = [];

                for (let i = 0; i < number_of_reservations.value; i++) {
                    let minutosActuales = minutosTotalesInicio + i * tiempoPorTurno;
                    let time = Math.floor(minutosActuales / 60);
                    let minutos = Math.floor(minutosActuales % 60);

                    let horaFormateada = time.toString().padStart(2, "0");
                    let minutosFormateados = minutos.toString().padStart(2, "0");

                    horarios.push(`${horaFormateada}:${minutosFormateados}`);
                }

                document.getElementById("available_time_slots").value = JSON.stringify(horarios);
                horarioItem.innerHTML = "<p>" + horarios.join("</p><p>") + "</p>";
            }
        }

        // Disparar automáticamente al cargar la página si ya hay valores cargados
        if (multi_slot.checked) {
            if (number_of_reservations.value >= 1 && start_time.value !== "" && end_time.value !== "") {
                generarHorarios();
            }
        }

        // Controlar radios manualmente
        multi_slot.addEventListener("change", function () {
            if (this.checked) {
                if (number_of_reservations.value >= 1 && start_time.value !== "" && end_time.value !== "") {
                    generarHorarios();
                } else {
                    alert("Por favor, complete todos los campos con valores válidos antes de continuar.");
                    this.checked = false;
                }
            } else {
                horarioBox.style.display = "none";
                document.getElementById("available_time_slots").value = "";
            }
        });

        number_of_reservations.addEventListener("input", function () {
            if (multi_slot.checked && this.value >= 1) {
                generarHorarios();
            }
        });

        single_slot.addEventListener("change", function () {
            if (this.checked) {
                horarioBox.style.display = "none";
                document.getElementById("available_time_slots").value = "";
            }
        });
    }
});
