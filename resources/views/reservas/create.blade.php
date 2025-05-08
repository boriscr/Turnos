<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Solicitar un nuevo turno</h3>

            <!-- Indicador de pasos -->
            <div class="step-indicator">
                <div class="step active" data-step="1"><i class="bi bi-person-circle"></i> Datos Personales</div>
                <div class="step" data-step="2"><i class="bi bi-clock-history"></i> Selección de Turno</div>
            </div>

            <form action="{{ route('reservarTurno') }}" method="post" id="multiStepForm">
                @csrf

                <!-- Paso 1 - Datos Personales -->
                <div class="form-step active" data-step="1">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input name="nombre" value="{{ Auth::user()->name }}" readonly>

                        <label for="apellido">Apellido</label>
                        <input name="apellido" value="{{ Auth::user()->surname }}" readonly>

                        <label for="email">Email</label>
                        <input name="email" value="{{ Auth::user()->email }}" readonly>

                        <label for="telefono">Teléfono</label>
                        <input name="telefono" value="{{ Auth::user()->phone }}" readonly>

                        <label for="dni">DNI</label>
                        <input name="dni" value="{{ Auth::user()->dni }}" readonly>

                        <label for="direccion">Dirección</label>
                        <input name="direccion" value="{{ Auth::user()->address }}" readonly>

                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input name="fecha_nacimiento" value="{{ Auth::user()->birthdate }}" readonly>
                    </div>

                    <div class="form-navigation navegation-next">
                        <button type="button" class="next-btn">Siguiente<i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Paso 2 - Selección de Turno -->
                <div class="form-step" data-step="2">
                    <div class="form-group">
                        <label for="especialidad_id">Especialidad</label>
                        <select name="especialidad_id" id="especialidad_id" required class="form-control">
                            <option value="">Seleccione una especialidad</option>
                            @foreach ($especialidades as $especialidad)
                                @if ($especialidad->estado == 1)
                                    <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="equipo_id">Profesional</label>
                        <select name="equipo_id" id="equipo_id" required class="form-control">
                            <option value="">Seleccione un equipo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_turno">Fecha del Turno</label>
                        <select name="fecha_turno" id="fecha_turno" required class="form-control">
                            <option value="">Seleccione una fecha</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="hora_turno">Hora del Turno</label>
                        <select name="hora_turno" id="hora_turno" required class="form-control">
                            <option value="">Seleccione una hora</option>
                        </select>
                        <input type="hidden" name="turno_id" id="turno_id">
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="prev-btn"><i class="bi bi-chevron-left"></i></button>
                        <button type="submit" class="submit-btn">Confirmar Turno <i class="bi bi-check-circle"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="forms/formulario-3paso-reserva.js"></script>
</x-body.body>
