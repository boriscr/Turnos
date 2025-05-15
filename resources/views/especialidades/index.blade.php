<x-body.body>
    <div class="main-table">
        <div class="container-form">
            <h3 class="title-form">Especialidades</h3>
            <div class="opciones">
                <button class="btn-add">
                    <a href="{{ route('especialidad.create') }}">Crear especialidad</a>
                </button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Equipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($especialidades as $especialidades)
                        <tr>
                            <td>{{ $especialidades->id }}</td>
                            <td>{{ $especialidades->nombre }}</td>
                            <td>{{ $especialidades->descripcion ?? 'Sin datos' }}</td>
                            <td> <a href="{{ route('especialidad.show', $especialidades->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a></td>

                            <td class="option-movil">{{ $especialidades->estado ? 'Activo' : 'Inactivo' }}</td>
                            <td class="acciones">
                                <a href="{{ route('especialidad.show', $especialidades->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('especialidad.edit', $especialidades->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('especialidad.destroy', $especialidades->id) }}" method="POST"
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
