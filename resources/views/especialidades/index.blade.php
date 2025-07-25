<x-app-layout>
    <div class="main-table centrado-total">
        <div class="container-form centrado-total">
            <h3 class="title-form">Especialidades</h3>
            <div class="opciones centrado-total">
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
                        <th>Medico</th>
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
                            <td> <a href="{{ route('lista.medicos', $especialidades->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a></td>

                            <td class="option-movil">{{ $especialidades->estado ? 'Activo' : 'Inactivo' }}</td>
                            <td class="acciones centrado-total">
                                <a href="{{ route('especialidad.show', $especialidades->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('especialidad.edit', $especialidades->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i></a>

                                <form action="{{ route('especialidad.destroy', $especialidades->id) }}" method="POST"
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
</x-app-layout>