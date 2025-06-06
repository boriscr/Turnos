//Automatizacion de la carga de equipos por especialidad
// Este script se encarga de cargar los equipos disponibles en función de la especialidad seleccionada
function cargarEquiposPorEspecialidad(especialidadId) {
    fetch(`/equipos-por-especialidad/${especialidadId}`)
        .then(response => response.json())
        .then(data => {
            let equipoSelect = document.getElementById("equipo_id");
            equipoSelect.innerHTML = "";

            if (data.length === 0) {
                equipoSelect.innerHTML = `<option value="">No hay equipos disponibles</option>`;
            } else {
                data.forEach(equipo => {
                    let option = document.createElement("option");
                    option.value = equipo.id;
                    option.text = equipo.nombre+' '+equipo.apellido;
                    equipoSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error("Error al obtener los equipos:", error);
        });
}

// Cuando cambia la especialidad
document.getElementById("especialidad_id").addEventListener("change", function() {
    cargarEquiposPorEspecialidad(this.value);
});

// Al cargar la página, cargar los equipos para la especialidad seleccionada por defecto
window.addEventListener("load", function() {
    let especialidadId = document.getElementById("especialidad_id").value;
    cargarEquiposPorEspecialidad(especialidadId);
});
