<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Solicitar un nuevo turno</h3>

            <!-- Indicador de pasos -->
            <div class="step-indicator">
                <div class="step active" data-step="1"><i class="bi bi-person-circle"></i> Datos Personales</div>
                <div class="step" data-step="2"><i class="bi bi-clock-history"></i> Selección de Turno</div>
            </div>

            <form action="{{ route('bookAvailableAppointment') }}" method="post" id="multiStepForm">
                @csrf

                <!-- Paso 1 - Datos Personales -->
                <div class="form-step active" data-step="1">
                    <div class="item">
                        <label for="name">Nombre</label>
                        <input name="name" value="{{ Auth::user()->name }}" readonly>

                        <label for="surname">Apellido</label>
                        <input name="surname" value="{{ Auth::user()->surname }}" readonly>

                        <label for="email">Email</label>
                        <input name="email" value="{{ Auth::user()->email }}" readonly>

                        <label for="phone">Teléfono</label>
                        <input name="phone" value="{{ Auth::user()->phone }}" readonly>

                        <label for="idNumber">DNI</label>
                        <input name="idNumber" value="{{ Auth::user()->idNumber }}" readonly>

                        <label for="direccion">Dirección</label>
                        <input name="direccion" value="{{ Auth::user()->address }}" readonly>

                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input name="fecha_nacimiento" value="{{ Auth::user()->birthdate }}" readonly>
                    </div>
                    <br>
                    <hr>
                    <br>
                    <div class="navegation-next full-center">
                        <button type="button" class="next-btn full-center">Siguiente<i
                                class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Paso 2 - Selección de Turno -->
                <div class="form-step" data-step="2">
                    <div class="item">
                        <label for="specialty_id"><i class="bi bi-1-circle"></i> Specialty</label>
                        <select name="specialty_id" id="specialty_id" required>
                            <option value="">Seleccione una specialty</option>
                            @foreach ($specialties as $specialty)
                                @if ($specialty->status == 1)
                                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="item">
                        <label for="doctor_id"><i class="bi bi-2-circle"></i> Médico/a</label>
                        <select name="doctor_id" id="doctor_id" required>
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
                        <button type="button" class="prev-btn full-center"><i
                                class="bi bi-chevron-left"></i></button>
                        <button type="submit" class="primary-btn">Confirmar Turno <i
                                class="bi bi-check-circle"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
