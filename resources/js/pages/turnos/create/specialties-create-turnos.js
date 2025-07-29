//Automatizacion de la carg Medicos por specialty
// Este script se encarga de cargar los medico disponibles en función de la specialty seleccionada
if (window.location.pathname.includes('/turnos/create') || window.location.pathname.includes('/turnos/edit')) {

    function cargarMedicosPorEspecialidad(especialidadId) {
        fetch(`/medicos-por-specialty/${especialidadId}`)
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
                        option.text = medico.name + ' ' + medico.surname;
                        medicoSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error("Error al obtener lista de médicos:", error);
            });
    }

    // Cuando cambia la specialty
    document.getElementById("specialty_id").addEventListener("change", function () {
        cargarMedicosPorEspecialidad(this.value);
    });

    // Al cargar la página, cargar los medico para la specialty seleccionada por defecto
    window.addEventListener("load", function () {
        let especialidadId = document.getElementById("specialty_id").value;
        cargarMedicosPorEspecialidad(especialidadId);
    });
}