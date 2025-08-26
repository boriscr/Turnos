<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h1>Especialidades</h1>
            <div class="options full-center">
                <button class="btn-add">
                    <a href="{{ route('specialty.create') }}">Crear specialty</a>
                </button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Doctor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($specialties as $specialties)
                        <tr>
                            <td>{{ $specialties->id }}</td>
                            <td>{{ $specialties->name }}</td>
                            <td>{{ $specialties->description ?? 'Sin datos' }}</td>
                            <td> <a href="{{ route('lista.doctors', $specialties->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a></td>

                            <td class="option-movil">{{ $specialties->status ? 'Activo' : 'Inactivo' }}</td>
                            <td class="acciones full-center">
                                <a href="{{ route('specialty.show', $specialties->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i></a>
                                <a href="{{ route('specialty.edit', $specialties->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i></a>

                                <form action="{{ route('specialty.destroy', $specialties->id) }}" method="POST"
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