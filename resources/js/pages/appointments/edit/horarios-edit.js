if (window.location.pathname.includes('/appointments/edit')) {

    document.addEventListener('DOMContentLoaded', function () {
        // Elementos del formulario
        const horario1 = document.getElementById("horario1");
        const horario2 = document.getElementById("horario2");
        const cantidad = document.getElementById("cantidad");
        const horarioBox = document.getElementById("horario-box");
        const horarioItem = document.getElementById("horarios-item");
        const start_time = document.getElementById("start_time");
        const end_time = document.getElementById("end_time");
        const horariosDisponiblesInput = document.getElementById("available_time_slots");

        // Cargar horarios existentes si hay en la edición
        function cargarHorariosExistentes() {
            try {
                const horariosGuardados = JSON.parse(horariosDisponiblesInput.value);
                if (Array.isArray(horariosGuardados)) {
                    horarioItem.innerHTML = horariosGuardados.map(h => `<p>${h}</p>`).join('');
                }
            } catch (e) {
                console.log("No hay horarios guardados o formato inválido");
            }
        }

        // Función para generar horarios
        function generarHorarios() {
            if (horario2.checked && cantidad.value >= 1 && start_time.value && end_time.value) {
                function convertirATiempoEnMinutos(inputTime) {
                    let [horas, minutos] = inputTime.split(":").map(Number);
                    return horas * 60 + minutos;
                }

                const minutosTotalesInicio = convertirATiempoEnMinutos(start_time.value);
                const minutosTotalesFin = convertirATiempoEnMinutos(end_time.value);
                const tiempoPorTurno = (minutosTotalesFin - minutosTotalesInicio) / cantidad.value;

                // Generar los horarios de appointments
                const horarios = [];

                for (let i = 0; i < cantidad.value; i++) {
                    const minutosActuales = minutosTotalesInicio + i * tiempoPorTurno;
                    const time = Math.floor(minutosActuales / 60);
                    const minutos = Math.floor(minutosActuales % 60);

                    // Formatear la salida
                    const horaFormateada = time.toString().padStart(2, "0");
                    const minutosFormateados = minutos.toString().padStart(2, "0");

                    horarios.push(`${horaFormateada}:${minutosFormateados}`);
                }

                horariosDisponiblesInput.value = JSON.stringify(horarios);
                horarioItem.innerHTML = horarios.map(h => `<p>${h}</p>`).join('');
                horarioBox.style.display = "flex";
            }
        }

        // Eventos
        horario2.addEventListener("change", function () {
            if (this.checked) {
                horario1.checked = false;
                if (cantidad.value >= 1 && start_time.value && end_time.value) {
                    generarHorarios();
                } else {
                    alert("Complete cantidad, time inicio y fin primero");
                    this.checked = false;
                }
            } else {
                horarioBox.style.display = "none";
                horariosDisponiblesInput.value = "";
                horarioItem.innerHTML = "";
            }
        });

        horario1.addEventListener("change", function () {
            if (this.checked) {
                horario2.checked = false;
                horarioBox.style.display = "none";
                horariosDisponiblesInput.value = "horario1";
            }
        });

        // Eventos para regenerar horarios al cambiar valores
        cantidad.addEventListener("input", generarHorarios);
        start_time.addEventListener("change", generarHorarios);
        end_time.addEventListener("change", generarHorarios);

        // Inicialización
        if (horariosDisponiblesInput.value.includes("horario2")) {
            generarHorarios();
        } else if (horariosDisponiblesInput.value.includes("horario1")) {
            horario1.checked = true;
        }

        cargarHorariosExistentes();
    });
}