//horarios de turnos formulario crear nuevo turno
//input de tipo checkbox
//horarios de turnos formulario crear nuevo turno
//input de tipo checkbox
if (window.location.pathname.includes('/turnos/create') ||window.location.pathname.includes('/turnos/edit')) {

    let sinHorario = document.getElementById("horario1");
    let horario2 = document.getElementById("horario2");
    //cantidad de turnos
    let cantidad = document.getElementById("cantidad");
    //box de horarios
    let horarioBox = document.getElementById("horario-box");
    //horario item
    let horarioItem = document.getElementById("horarios-item");
    //horarios
    let hora_inicio = document.getElementById("hora_inicio");
    let hora_fin = document.getElementById("hora_fin");

    // Función para generar horarios
    function generarHorarios() {
        if (horario2.checked && cantidad.value >= 1 && hora_inicio.value !== "" && hora_fin.value !== "") {
            horarioBox.style.display = "flex";

            function convertirATiempoEnMinutos(inputTime) {
                let [horas, minutos] = inputTime.split(":").map(Number);
                return horas * 60 + minutos;
            }

            let minutosTotalesInicio = convertirATiempoEnMinutos(hora_inicio.value);
            let minutosTotalesFin = convertirATiempoEnMinutos(hora_fin.value);
            let tiempoPorTurno = (minutosTotalesFin - minutosTotalesInicio) / cantidad.value;

            // Generar los horarios de turnos
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

            document.getElementById("horarios_disponibles").value = JSON.stringify(horarios);
            horarioItem.innerHTML = "<p>" + horarios.join("</p><p>") + "</p>";
        }
    }

    // Evento change para el checkbox horario2
    horario2.addEventListener("change", function () {
        if (this.checked) {
            if (cantidad.value >= 1 && hora_inicio.value !== "" && hora_fin.value !== "") {
                generarHorarios();
            } else {
                alert("Por favor, complete todos los campos con valores válidos antes de continuar.");
                this.checked = false;
            }
        } else {
            horarioBox.style.display = "none";
            document.getElementById("horarios_disponibles").value = "";
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