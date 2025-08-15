<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>Usuarios creados</h1>
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
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name . ' ' . $user->surname }}</td>
                            <td class="option-movil">{{ $user->idNumber }}</td>
                            <td class="option-movil"> {{ $user->getRoleNames()->first() }}</p>
                            </td>
                            <td class="option-movil">{{ $user->address }}</td>
                            <td>{{ $user->status ? 'Activo' : 'Inactivo' }}</td>
                            <td class="acciones full-center">
                                <a href="{{ route('user.show', $user->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i><b class="accionesMovil">Ver</b></a>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i><b class="accionesMovil">Editar</b></a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="POST"
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
</x-app-layout>