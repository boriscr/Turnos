// Control de botones de incremento/decremento
if (window.location.pathname.includes('/users/edit')) {
    let inputCantidad = document.getElementById('faults');
    let decrement = document.getElementById('decrement-btn');
    let increment = document.getElementById('increment-btn');

    decrement.addEventListener('click', function () {
        if (inputCantidad.value > 0) {
            inputCantidad.value = parseInt(inputCantidad.value) - 1;
            inputCantidad.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });

    increment.addEventListener('click', function () {
        inputCantidad.value = parseInt(inputCantidad.value) + 1;
        inputCantidad.dispatchEvent(new Event('change', { bubbles: true }));
    });
}