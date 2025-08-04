//Automatizacion de la carg Doctors por specialty
// Este script se encarga de cargar los doctor appointmentsAvailableDestroy en función de la specialty seleccionada
if (window.location.pathname.includes('/appointments/create') || window.location.pathname.includes('/appointments/edit')) {

    function cargarMedicosPorEspecialidad(especialidadId) {
        fetch(`/doctors-por-specialty/${especialidadId}`)
            .then(response => response.json())
            .then(data => {
                let medicoSelect = document.getElementById("doctor_id");
                medicoSelect.innerHTML = "";

                if (data.length === 0) {
                    medicoSelect.innerHTML = `<option value="">No hay doctores disponibles</option>`;
                } else {
                    data.forEach(doctor => {
                        let option = document.createElement("option");
                        option.value = doctor.id;
                        option.text = doctor.name + ' ' + doctor.surname;
                        medicoSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error("Error al obtener lista de doctores:", error);
            });
    }

    // Cuando cambia la specialty
    document.getElementById("specialty_id").addEventListener("change", function () {
        cargarMedicosPorEspecialidad(this.value);
    });

    // Al cargar la página, cargar los doctor para la specialty seleccionada por defecto
    window.addEventListener("load", function () {
        let especialidadId = document.getElementById("specialty_id").value;
        cargarMedicosPorEspecialidad(especialidadId);
    });
}