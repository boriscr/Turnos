<x-app-layout>
    <div class="main centrado-total">
        <div class="container-form centrado-total">
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
                    <div class="item">
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
                    <br>
                    <hr>
                    <br>
                    <div class="navegation-next centrado-total">
                        <button type="button" class="next-btn centrado-total">Siguiente<i
                                class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Paso 2 - Selección de Turno -->
                <div class="form-step" data-step="2">
                    <div class="item">
                        <label for="especialidad_id"><i class="bi bi-1-circle"></i> Especialidad</label>
                        <select name="especialidad_id" id="especialidad_id" required>
                            <option value="">Seleccione una especialidad</option>
                            @foreach ($especialidades as $especialidad)
                                @if ($especialidad->estado == 1)
                                    <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="item">
                        <label for="medico_id"><i class="bi bi-2-circle"></i> Médico/a</label>
                        <select name="medico_id" id="medico_id" required>
                            <option value="">Seleccione un médico</option>
                        </select>
                    </div>
                    <div class="item">
                        <label for="turno_nombre_id"><i class="bi bi-3-circle"></i> Turno</label>
                        <select name="turno_nombre_id" id="turno_nombre_id" required>
                            <option value="">Seleccione un turno</option>
                        </select>
                    </div>
                    <div class="item">
                        <label for="fecha_turno"><i class="bi bi-4-circle"></i> Fecha del Turno</label>
                        <select name="fecha_turno" id="fecha_turno" required>
                            <option value="">Seleccione una fecha</option>
                        </select>
                    </div>

                    <div class="item">
                        <label for="hora_turno"><i class="bi bi-5-circle"></i> Horario del Turno</label>
                        <select name="hora_turno" id="hora_turno" required>
                            <option value="">Seleccione un horario</option>
                        </select>
                        <input type="hidden" name="turno_id" id="turno_id">
                    </div>
                    <br>
                    <hr>
                    <br>
                    <div class="form-navigation">
                        <button type="button" class="prev-btn centrado-total"><i
                                class="bi bi-chevron-left"></i></button>
                        <button type="submit" class="primary-btn">Confirmar Turno <i
                                class="bi bi-check-circle"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
