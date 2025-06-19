//Automatizacion de la carg Medicos por especialidad
// Este script se encarga de cargar los medico disponibles en función de la especialidad seleccionada
function cargarMedicosPorEspecialidad(especialidadId) {
    fetch(`/medicos-por-especialidad/${especialidadId}`)
        .then(response => response.json())
        .then(data => {
            let medicoSelect = document.getElementById("medico_id");
            medicoSelect.innerHTML = "";

            if (data.length === 0) {
                medicoSelect.innerHTML = `<option value="">No hay médicos disponibles</option>`;
            } else {
                data.forEach(medico => {
                    let option = document.createElement("option");
                    option.value = medico.id;
                    option.text = medico.nombre+' '+medico.apellido;
                    medicoSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error("Error al obtener lista de médicos:", error);
        });
}

// Cuando cambia la especialidad
document.getElementById("especialidad_id").addEventListener("change", function() {
    cargarMedicosPorEspecialidad(this.value);
});

// Al cargar la página, cargar los medico para la especialidad seleccionada por defecto
window.addEventListener("load", function() {
    let especialidadId = document.getElementById("especialidad_id").value;
    cargarMedicosPorEspecialidad(especialidadId);
});
