if (window.location.pathname.includes('/doctors/create') || window.location.pathname.includes('/doctors/edit')) {
        // Selecciona todos los botones con clase o id común
        const specialtyBtns = document.querySelectorAll("#specialty-btn");
        const specialtyForms = document.querySelectorAll("#specialty-form");
        const closeBtns = document.querySelectorAll("#close-btn");

        specialtyBtns.forEach((btn, index) => {
                btn.addEventListener("click", () => {
                        if (specialtyForms[index]) {
                                specialtyForms[index].style.display = "flex";
                        }
                });
        });

        closeBtns.forEach((btn, index) => {
                btn.addEventListener("click", () => {
                        if (specialtyForms[index]) {
                                specialtyForms[index].style.display = "none";
                        }
                });
        });
}