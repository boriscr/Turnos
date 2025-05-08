document.addEventListener('DOMContentLoaded', function() {
    // Elementos del formulario
    const horario1 = document.getElementById("horario1");
    const horario2 = document.getElementById("horario2");
    const cantidad = document.getElementById("cantidad");
    const horarioBox = document.getElementById("horario-box");
    const horarioItem = document.getElementById("horarios-item");
    const hora_inicio = document.getElementById("hora_inicio");
    const hora_fin = document.getElementById("hora_fin");
    const horariosDisponiblesInput = document.getElementById("horarios_disponibles");

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
        if (horario2.checked && cantidad.value >= 1 && hora_inicio.value && hora_fin.value) {
            function convertirATiempoEnMinutos(inputTime) {
                let [horas, minutos] = inputTime.split(":").map(Number);
                return horas * 60 + minutos;
            }

            const minutosTotalesInicio = convertirATiempoEnMinutos(hora_inicio.value);
            const minutosTotalesFin = convertirATiempoEnMinutos(hora_fin.value);
            const tiempoPorTurno = (minutosTotalesFin - minutosTotalesInicio) / cantidad.value;

            // Generar los horarios de turnos
            const horarios = [];

            for (let i = 0; i < cantidad.value; i++) {
                const minutosActuales = minutosTotalesInicio + i * tiempoPorTurno;
                const hora = Math.floor(minutosActuales / 60);
                const minutos = Math.floor(minutosActuales % 60);

                // Formatear la salida
                const horaFormateada = hora.toString().padStart(2, "0");
                const minutosFormateados = minutos.toString().padStart(2, "0");

                horarios.push(`${horaFormateada}:${minutosFormateados}`);
            }

            horariosDisponiblesInput.value = JSON.stringify(horarios);
            horarioItem.innerHTML = horarios.map(h => `<p>${h}</p>`).join('');
            horarioBox.style.display = "flex";
        }
    }

    // Eventos
    horario2.addEventListener("change", function() {
        if (this.checked) {
            horario1.checked = false;
            if (cantidad.value >= 1 && hora_inicio.value && hora_fin.value) {
                generarHorarios();
            } else {
                alert("Complete cantidad, hora inicio y fin primero");
                this.checked = false;
            }
        } else {
            horarioBox.style.display = "none";
            horariosDisponiblesInput.value = "";
            horarioItem.innerHTML = "";
        }
    });

    horario1.addEventListener("change", function() {
        if (this.checked) {
            horario2.checked = false;
            horarioBox.style.display = "none";
            horariosDisponiblesInput.value = "horario1";
        }
    });

    // Eventos para regenerar horarios al cambiar valores
    cantidad.addEventListener("input", generarHorarios);
    hora_inicio.addEventListener("change", generarHorarios);
    hora_fin.addEventListener("change", generarHorarios);

    // Inicialización
    if (horariosDisponiblesInput.value.includes("horario2")) {
        generarHorarios();
    } else if (horariosDisponiblesInput.value.includes("horario1")) {
        horario1.checked = true;
    }
    
    cargarHorariosExistentes();
});