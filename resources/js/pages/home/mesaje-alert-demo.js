if (window.location.pathname === '/') {

    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("demoModal");
        const btn = document.getElementById("btnAceptar");

        // Si ya aceptó → ocultar completamente
        if (localStorage.getItem("demoAccepted") === "true") {
            modal.style.display = "none";
            return;
        }

        // Mostrar modal
        modal.classList.add("active");

        btn.onclick = function () {
            modal.classList.remove("active");
            setTimeout(() => modal.style.display = "none", 500);
            localStorage.setItem("demoAccepted", "true");
        };
    });
}