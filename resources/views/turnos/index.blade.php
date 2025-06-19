<x-body.body>
    <div class="main-table">
        <div class="container-form">
            <h3 class="title-form">Turnos creados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th class="option-movil">Direccion</th>
                        <th class="option-movil">Especialidad</th>
                        <th>Encargado</th>
                        <th class="option-movil">Turno</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turnos as $turno)
                        <tr>
                            <td>{{ $turno->id }}</td>
                            <td>{{ $turno->nombre }}</td>
                            <td class="option-movil">{{ $turno->direccion }}</td>
                            <td class="option-movil">{{ $turno->especialidad->nombre }}</td>
                            <td>{{ $turno->medico->nombre . ' ' . $turno->medico->apellido }}</td>
                            <td class="option-movil">{{ $turno->turno }}</td>
                            <td>{{ $turno->estado ? 'Activo' : 'Inactivo' }}</td>
                            <td class="acciones">
                                <a href="{{ route('turnos.show', $turno->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('turnos.edit', $turno->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i></a>

                                <form action="{{ route('turnos.destroy', $turno->id) }}" method="POST"
                                    class="delete-form" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete delete-btn">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="accionesMovil"><button><i class="bi bi-gear"></i></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="delete-btn.js"></script>

</x-body.body>
