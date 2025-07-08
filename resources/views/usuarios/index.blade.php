<x-body.body>
    <div class="main-table">
        <div class="container-form">
            <h3 class="title-form">Usuarios creados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre y Apellido</th>
                        <th class="option-movil">DNI</th>
                        <th class="option-movil">Rol</th>
                        <th class="option-movil">Direccion</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id }}</td>
                            <td>{{ $usuario->name . ' ' . $usuario->surname }}</td>
                            <td class="option-movil">{{ $usuario->dni }}</td>
                            <td class="option-movil"> {{ $usuario->getRoleNames()->first() }}</p>
                            </td>
                            <td class="option-movil">{{ $usuario->address }}</td>
                            <td>{{ $usuario->estado ? 'Activo' : 'Inactivo' }}</td>
                            <td class="acciones">
                                <a href="{{ route('usuario.show', $usuario->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i><b class="accionesMovil">Ver</b></a>
                                <a href="{{ route('usuario.edit', $usuario->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i><b class="accionesMovil">Editar</b></a>
                                <form action="{{ route('usuario.destroy', $usuario->id) }}" method="POST"
                                    style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-delete delete-btn"><i
                                            class="bi bi-trash-fill"></i><b class="accionesMovil">Eliminar</b></button>
                                </form>
                            </td>
                            <td class="accionesMovil">
                                <button type="button" class="accionesMovilBtn">
                                    <i class="bi bi-gear"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-body.body>
