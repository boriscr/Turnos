//horarios de appointments formulario crear nuevo appointment
//input de tipo checkbox
//horarios de appointments formulario crear nuevo appointment
//input de tipo checkbox
if (window.location.pathname.includes('/appointments/create') ||window.location.pathname.includes('/appointments/edit')) {

    let sinHorario = document.getElementById("horario1");
    let horario2 = document.getElementById("horario2");
    //cantidad de appointments
    let cantidad = document.getElementById("cantidad");
    //box de horarios
    let horarioBox = document.getElementById("horario-box");
    //horario item
    let horarioItem = document.getElementById("horarios-item");
    //horarios
    let start_time = document.getElementById("start_time");
    let end_time = document.getElementById("end_time");

    // Función para generar horarios
    function generarHorarios() {
        if (horario2.checked && cantidad.value >= 1 && start_time.value !== "" && end_time.value !== "") {
            horarioBox.style.display = "flex";

            function convertirATiempoEnMinutos(inputTime) {
                let [horas, minutos] = inputTime.split(":").map(Number);
                return horas * 60 + minutos;
            }

            let minutosTotalesInicio = convertirATiempoEnMinutos(start_time.value);
            let minutosTotalesFin = convertirATiempoEnMinutos(end_time.value);
            let tiempoPorTurno = (minutosTotalesFin - minutosTotalesInicio) / cantidad.value;

            // Generar los horarios de appointments
            let horarios = [];

            for (let i = 0; i < cantidad.value; i++) {
                let minutosActuales = minutosTotalesInicio + i * tiempoPorTurno;
                let time = Math.floor(minutosActuales / 60);
                let minutos = Math.floor(minutosActuales % 60);

                // Formatear la salida
                let horaFormateada = time.toString().padStart(2, "0");
                let minutosFormateados = minutos.toString().padStart(2, "0");

                horarios.push(`${horaFormateada}:${minutosFormateados}`);
            }

            document.getElementById("available_time_slots").value = JSON.stringify(horarios);
            horarioItem.innerHTML = "<p>" + horarios.join("</p><p>") + "</p>";
        }
    }

    // Evento change para el checkbox horario2
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

    // Evento input para el campo cantidad (solo cuando horario2 está activado)
    cantidad.addEventListener("input", function () {
        if (horario2.checked && this.value >= 1) {
            generarHorarios();
        }
    });

    let horario1 = document.getElementById("horario1");
    horario1.addEventListener("change", function () {
        if (this.checked) {
            horarioBox.style.display = "none";
        }
    });
};