document.addEventListener('DOMContentLoaded', function () {
  // Manejar clic en el botón de acciones móviles
  document.querySelectorAll('.accionesMovilBtn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.stopPropagation();

      // Obtener la fila padre
      const row = this.closest('tr');

      // Crear o encontrar el box de acciones
      let box = row.querySelector('.box-accionesMovil');

      if (!box) {
        // Crear el box si no existe
        box = document.createElement('div');
        box.className = 'box-accionesMovil';

        // Obtener los datos necesarios de la fila
        if (window.location.pathname.includes('/turnos')) {
          var elemento = 'td:nth-child(2)'
          var editarLink = row.querySelector('.acciones a:nth-child(2)').outerHTML;
        } else if (window.location.pathname.includes('/reservas')) {
          var elemento = 'td:nth-child(5) a'
        } else if (window.location.pathname.includes('/usuario')) {
          var elemento = 'td:nth-child(2)'
          var editarLink = row.querySelector('.acciones a:nth-child(2)').outerHTML;
        } else if (window.location.pathname.includes('/medicos')) {
          var elemento = 'td:nth-child(2)'
          var editarLink = row.querySelector('.acciones a:nth-child(2)').outerHTML;
        }

        // Obtener el nombre del paciente
        const pacienteNombre = row.querySelector(elemento).textContent;
        // Obtener el enlace de ver detalles
        const verLink = row.querySelector('.acciones a').outerHTML;
        //Obtener el segundo enlace editar
        // Obtener el formulario de eliminación
        const eliminarForm = row.querySelector('.acciones form').outerHTML;

        // Construir el contenido del box
        box.innerHTML = `
                    <a href="#" class="paciente-info">${pacienteNombre}</a>
                    <hr>
                    <h1>Acciones</h1>
                    <hr>
                    <div class="contenido-accionesMovil">
                        ${verLink}
                        ${editarLink ?? ''}
                        ${eliminarForm}
                    </div>
                `;

        // Agregar el box al body
        document.body.appendChild(box);

        // Configurar el evento de eliminación
        const deleteBtn = box.querySelector('.delete-btn');
        if (deleteBtn) {
          deleteBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('.delete-form');

            Swal.fire({
              title: '¿Estás seguro de eliminar?',
              text: "¡No podrás revertir esta acción!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Sí, eliminar',
              cancelButtonText: 'Cancelar'
            }).then((result) => {
              if (result.isConfirmed) {
                form.submit();
              }
            });
          });
        }
      }

      // Ocultar otros boxes y quitar active de otros botones
      document.querySelectorAll('.box-accionesMovil').forEach(b => {
        if (b !== box) b.classList.remove('active');
      });
      document.querySelectorAll('.accionesMovilBtn').forEach(b => {
        if (b !== this) b.classList.remove('active');
      });

      // Mostrar/ocultar el box actual y alternar clase active en el botón
      const isActive = box.classList.toggle('active');
      this.classList.toggle('active', isActive);
    });
  });

  // Cerrar el box y quitar active del botón al hacer clic fuera
  document.addEventListener('click', function () {
    document.querySelectorAll('.box-accionesMovil').forEach(box => {
      box.classList.remove('active');
    });
    document.querySelectorAll('.accionesMovilBtn').forEach(btn => {
      btn.classList.remove('active');
    });
  });

  // Prevenir que el clic dentro del box cierre el mismo
  document.addEventListener('click', function (e) {
    if (e.target.closest('.box-accionesMovil')) {
      e.stopPropagation();
    }
  });
});