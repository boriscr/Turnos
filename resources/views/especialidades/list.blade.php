<x-body.body>
    <div class="main-table">
        <div class="container-form">
            <h3 class="title-form">Especialidades</h3>
            <div class="opciones">
                <button class="btn-add">
                    <a href="{{ route('medico.create') }}">Crear medico</a>
                </button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicos as $medico)
                        <tr>
                            <td>{{ $medicos->id }}</td>
                            <td>{{ $medicos->nombre }}</td>
                            <td>{{ $medicos->descripcion ?? 'Sin datos' }}</td>
                            <td>{{ $medicos->especialidad->nombre }}</td>

                            <td class="option-movil">{{ $medicos->estado ? 'Activo' : 'Inactivo' }}</td>
                            <td class="acciones">
                                <a href="{{ route('medico.create', $medicos->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('medico.edit', $medicos->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('medico.destroy', $medicos->id) }}" method="POST"
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
