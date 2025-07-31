<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Lista de médicos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre y Apellido</th>
                        <th class="option-movil">Dni</th>
                        <th>Specialty</th>
                        <th class="option-movil">Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($doctors as $doctor)
                        <tr>
                            <td>{{ $doctor->id }}</td>
                            <td>{{ $doctor->name . ' ' . $doctor->surname }}</td>
                            <td class="option-movil">{{ $doctor->idNumber }}</td>
                            <td>{{ $doctor->specialty->name ?? 'Sin datos' }}</td>
                            <td class="option-movil">{{ $doctor->role }}</td>
                            <td class="acciones full-center">
                                <a href="{{ route('doctor.show', $doctor->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i><b class="accionesMovil">Ver</b></a>
                                <a href="{{ route('doctor.edit', $doctor->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i><b class="accionesMovil">Editar</b></a>

                                <form class="delete-form" action="{{ route('doctor.destroy', $doctor->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete delete-btn">
                                        <i class="bi bi-trash-fill"></i><b class="accionesMovil">Eliminar</b>
                                    </button>
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
