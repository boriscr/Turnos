<x-body.body>
    <div class="content-wrapper">
        <H1 class="section-title">Lista de equipos</H1>
        <div class="section-container">
            <div class="card">
                <p><b>Nombre:</b> {{ $turno->nombre }}</p>
                <p><b>Descripción:</b> {{ $turno->descripcion }}</p>
                <p><b>Especialidad:</b> {{ $turno->especialidad->nombre }}</p>
                <p><b>Encargado/a:</b> {{ $turno->equipo->nombre . ' ' . $turno->equipo->apellido }} <a href=""><i
                            class="bi bi-eye"></i></a></p>
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
                <p><b>Estado:</b> {{ $turno->estado ? 'Activo' : 'Inactivo' }}</p>
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
    </div>
</x-body.body>
