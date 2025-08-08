<x-app-layout>
    <div class="main-table full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Appointments creados</h3>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th class="option-movil">Direccion</th>
                        <th class="option-movil">Specialty</th>
                        <th>Encargado</th>
                        <th class="option-movil">Turno</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->name }}</td>
                            <td class="option-movil">{{ $appointment->address }}</td>
                            <td class="option-movil">{{ $appointment->specialty->name }}</td>
                            <td>{{ $appointment->doctor->name . ' ' . $appointment->doctor->surname }}</td>
                            <td class="option-movil">{{ $appointment->shift }}</td>
                            <td>{{ $appointment->status ? 'Activo' : 'Inactivo' }}</td>
                            <td class="acciones full-center">
                                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-view"><i
                                        class="bi bi-eye"></i><b class="accionesMovil">Ver</b></a>
                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-edit"><i
                                        class="bi bi-pencil-fill"></i><b class="accionesMovil">Editar</b></a>

                                <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST"
                                    class="delete-form" style="display:inline;">
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