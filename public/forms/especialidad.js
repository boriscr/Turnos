let especialidadBtn = document.getElementById("especialidad-btn");
let especialidadForm = document.getElementById("especialidad-form");
let closeBtn = document.getElementById("close-btn");

especialidadBtn.addEventListener("click", function() {
        especialidadForm.style.display = "flex";
});

closeBtn.addEventListener("click", function() {
        especialidadForm.style.display = "none";
});