<x-body.body>
    <div class="main-table">
        <div class="container-form">
            <h3 class="title-form">Lista de equipos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th class="option-movil">Dni</th>
                        <th>Especialidad</th>
                        <th class="option-movil">Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipos as $equipo)
                        <tr>
                            <td>{{ $equipo->id }}</td>
                            <td>{{ $equipo->nombre }}</td>
                            <td>{{ $equipo->apellido }}</td>
                            <td class="option-movil">{{ $equipo->dni }}</td>
                            <td>{{ $equipo->especialidad->nombre??null }}</td>
                            <td class="option-movil">{{ $equipo->role }}</td>
                            <td class="acciones">
                                <a href="{{ route('equipo.show', $equipo->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('equipo.edit', $equipo->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('equipo.destroy', $equipo->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete delete-btn"><i
                                            class="bi bi-trash-fill"></i></button>
                                </form>
                            </td>
                            <td class="accionesMovil"><button><i class="bi bi-gear"></i></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <script src="../../delete-btn.js"></script>

</x-body.body>
