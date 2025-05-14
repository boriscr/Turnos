<x-body.body>
    <div class="main-table">
        <div class="container-form">
            <h3 class="title-form">Usuarios creados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th class="option-movil">DNI</th>
                        <th>Nacimiento</th>
                        <th class="option-movil">Direccion</th>
                        <th class="option-movil">Celular</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->surname }}</td>
                            <td class="option-movil">{{ $usuario->dni }}</td>
                            <td>{{ $usuario->birthdate }}</td>
                            <td class="option-movil">{{ $usuario->address }}</td>
                            <td class="option-movil">{{ $usuario->phone }}</td>
                            <td class="acciones">
                                <a href="{{ route('usuario.show', $usuario->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('usuario.edit', $usuario->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('usuario.destroy', $usuario->id) }}" method="POST"
                                    style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-delete delete-btn"><i
                                            class="bi bi-trash-fill"></i></button>
                                </form>

                                <!-- Incluye SweetAlert2 en tu layout o vista -->
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const deleteButtons = document.querySelectorAll('.delete-btn');

                                        deleteButtons.forEach(button => {
                                            button.addEventListener('click', function(e) {
                                                e.preventDefault();

                                                const form = this.closest('.delete-form');

                                                Swal.fire({
                                                    title: '¿Estás seguro de eliminar éste usuario?',
                                                    text: "¡No podrás revertir esta acción!",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Sí, eliminar',
                                                    cancelButtonText: 'Cancelar'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        form.submit();
                                                    }
                                                });
                                            });
                                        });
                                    });
                                </script>
                            </td>
                            <td class="accionesMovil"><button><i class="bi bi-gear"></i></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-body.body>
