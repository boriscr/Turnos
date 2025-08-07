//horarios de appointments formulario crear nuevo appointment
//input de tipo radio
//horarios de appointments formulario crear nuevo appointment
//input de tipo radio
document.addEventListener("DOMContentLoaded", function () {
    if (window.location.pathname.includes('/appointments/create') || window.location.pathname.includes('/appointments/edit')) {

        let horario1 = document.getElementById("horario1");
        let horario2 = document.getElementById("horario2");
        let cantidad = document.getElementById("cantidad");
        let horarioBox = document.getElementById("horario-box");
        let horarioItem = document.getElementById("horarios-item");
        let start_time = document.getElementById("start_time");
        let end_time = document.getElementById("end_time");

        function convertirATiempoEnMinutos(inputTime) {
            let [horas, minutos] = inputTime.split(":").map(Number);
            return horas * 60 + minutos;
        }

        function generarHorarios() {
            if (horario2.checked && cantidad.value >= 1 && start_time.value !== "" && end_time.value !== "") {
                horarioBox.style.display = "flex";

                let minutosTotalesInicio = convertirATiempoEnMinutos(start_time.value);
                let minutosTotalesFin = convertirATiempoEnMinutos(end_time.value);
                let tiempoPorTurno = (minutosTotalesFin - minutosTotalesInicio) / cantidad.value;

                let horarios = [];

                for (let i = 0; i < cantidad.value; i++) {
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
        if (horario2.checked) {
            if (cantidad.value >= 1 && start_time.value !== "" && end_time.value !== "") {
                generarHorarios();
            }
        }

        // Controlar radios manualmente
        horario2.addEventListener("change", function () {
            if (this.checked) {
                if (cantidad.value >= 1 && start_time.value !== "" && end_time.value !== "") {
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

        cantidad.addEventListener("input", function () {
            if (horario2.checked && this.value >= 1) {
                generarHorarios();
            }
        });

        horario1.addEventListener("change", function () {
            if (this.checked) {
                horarioBox.style.display = "none";
                document.getElementById("available_time_slots").value = "";
            }
        });
    }
});
