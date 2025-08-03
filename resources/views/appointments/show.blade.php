<x-app-layout>
    <div class="content-wrapper">
        <H3 class="title-form">Detalles</H3>

        <div class="section-container full-center">
            <div class="card">
                <p><b>Nombre:</b> {{ $appointment->name }}</p>
                <p><b>Direccion:</b> {{ $appointment->address }}</p>
                <p><b>Specialty:</b> {{ $appointment->specialty->name }}</p>
                <p><b>Encargado/a:</b> {{ $appointment->doctor->name . ' ' . $appointment->doctor->surname }} <a
                        href="{{ route('doctor.show', $appointment->doctor->id) }}"><i class="bi bi-eye"></i></a></p>
                <p><b>Appointment:</b> {{ $appointment->appointment }}</p>
                <p><b>Cantidad de appointments:</b> {{ $appointment->number_of_slots }} </p>
                <p><b>Hora de inicio:</b> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</p>
                <p><b>Hora de finalizacion:</b> {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</p>
                @if ($appointment->available_time_slots)
                    <p><b>Horarios dispobibles:</b> {{ $appointment->available_time_slots }}</p>
                @endif
            </div>
            <div class="card">
                <p><b>Fechas disponibles:</b> @json($appointment->available_dates)</p>
                <p><b>Estado:</b> {{ $appointment->status ? 'Activo' : 'Inactivo' }}</p>
                <p><b>Creado por:</b> {{ $appointment->createdBy->name . ' ' . $appointment->createdBy->surname }} <a
                        href="{{ route('user.show', $appointment->createBy) }}"><i class="bi bi-eye"></i></a></p>
                <p><b>Ultima actualizacion:</b>
                    {{ $appointment->updatedBy->name . ' ' . $appointment->updatedBy->surname }} <a
                        href="{{ route('user.show', $appointment->updateBy) }}"><i class="bi bi-eye"></i></a></p>
                <p><b>Fecha de creación:</b> {{ \Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y H:i') }}
                </p>
                <p><b>Ultima actualización:</b>
                    {{ \Carbon\Carbon::parse($appointment->updated_at)->format('d/m/Y H:i') }}
                </p>
                </p>
            </div>
        </div>
        <div class="opciones full-center">
            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn-edit"><i
                    class="bi bi-pencil-fill">Editar</i></a>
            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill">Eliminar</i></button>
            </form>
        </div>
        <br>
        <a href="{{ route('availableAppointments.index', $appointment->doctor_id) }}">Ver appointments disponibles</a>
</x-app-layout>
