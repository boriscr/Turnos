<x-app-layout>
    <div class="content-wrapper">
        <H3 class="title-form">Detalles</H3>

        <div class="section-container full-center">
            <div class="card">
                <p><b>Nombre:</b> {{ $turno->name }}</p>
                <p><b>Direccion:</b> {{ $turno->direccion }}</p>
                <p><b>Specialty:</b> {{ $turno->specialty->name }}</p>
                <p><b>Encargado/a:</b> {{ $turno->doctor->name . ' ' . $turno->doctor->surname }} <a
                        href="{{ route('doctor.create', $turno->doctor->id) }}"><i class="bi bi-eye"></i></a></p>
                <p><b>Turno:</b> {{ $turno->turno }}</p>
                <p><b>Cantidad de turnos:</b> {{ $turno->cantidad_turnos }} </p>
                <p><b>Hora de inicio:</b> {{ \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') }}</p>
                <p><b>Hora de finalizacion:</b> {{ \Carbon\Carbon::parse($turno->hora_fin)->format('H:i') }}</p>
                @if ($turno->horarios_disponibles)
                    <p><b>Horarios dispobibles:</b> {{ $turno->horarios_disponibles }}</p>
                @endif
            </div>
            <div class="card">
                <p><b>Fechas disponibles:</b> @json($turno->fechas_disponibles)</p>
                <p><b>Estado:</b> {{ $turno->status ? 'Activo' : 'Inactivo' }}</p>
                <p><b>Creado por:</b> {{ $turno->user->name . ' ' . $turno->user->surname }} <a href=""><i
                            class="bi bi-eye"></i></a></p>
                <p><b>Ultima actualizacion:</b> {{ $turno->user->name . ' ' . $turno->user->surname }} <a
                        href=""><i class="bi bi-eye"></i></a></p>
                <p><b>Fecha de creación:</b> {{ \Carbon\Carbon::parse($turno->created_at)->format('d/m/Y H:i') }}</p>
                <p><b>Ultima actualización:</b> {{ \Carbon\Carbon::parse($turno->updated_at)->format('d/m/Y H:i') }}
                </p>
                </p>
            </div>
        </div>
        <div class="opciones full-center">
            <a href="{{ route('turnos.edit', $turno->id) }}" class="btn-edit"><i
                    class="bi bi-pencil-fill">Editar</i></a>
            <form action="{{ route('turnos.destroy', $turno->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill">Eliminar</i></button>
            </form>
        </div>
        <br>
        <a href="{{ route('availableAppointments.index', $turno->doctor_id) }}">Ver turnos disponibles</a>
</x-app-layout>
