if (window.location.pathname.includes('/medicos/create') || window.location.pathname.includes('/medicos/edit')) {

        let especialidadBtn = document.getElementById("specialty-btn");
        let especialidadForm = document.getElementById("specialty-form");
        let closeBtn = document.getElementById("close-btn");

        especialidadBtn.addEventListener("click", function () {
                especialidadForm.style.display = "flex";
        });

        closeBtn.addEventListener("click", function () {
                especialidadForm.style.display = "none";
        });
}