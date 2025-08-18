if (window.location.pathname.includes('/reservation/index')) {
    // solo ejecutar si estamos en esa ruta
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('dateFilterForm').addEventListener('submit', function (e) {
            const fechaInicio = document.getElementsByName('fecha_inicio')[0].value;
            const fechaFin = document.getElementsByName('fecha_fin')[0].value;

            if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
                alert('La date de inicio no puede ser mayor a la date final');
                e.preventDefault();
            }
        });
    });
}
