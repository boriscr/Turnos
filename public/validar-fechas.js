document.getElementById('dateFilterForm').addEventListener('submit', function (e) {
    const fechaInicio = document.getElementsByName('fecha_inicio')[0].value;
    const fechaFin = document.getElementsByName('fecha_fin')[0].value;

    if (fechaInicio && fechaFin && fechaInicio > fechaFin) {
        alert('La fecha de inicio no puede ser mayor a la fecha final');
        e.preventDefault();
    }
});