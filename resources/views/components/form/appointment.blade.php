<!-- Indicador de pasos -->
<div class="step-indicator">
    <div class="step active" data-step="1"><i class="bi bi-stars"></i> Datos</div>
    <div class="step" data-step="2"><i class="bi bi-clock-history"></i> Horario/s</div>
    <div class="step" data-step="3"><i class="bi bi-calendar-date-fill"></i> Fecha/s</div>
</div>

<form x-data="iosCalendar()" x-init="init({{ $fechas ?? '[]' }})" id="multiStepForm"
    @submit.prevent="updateSelectedDatesInput(); $el.submit()" method="POST" action="{{ $route }}">
    @csrf
    @if (!isset($create))
        @method('PATCH')
    @endif
    <!-- Paso 1 - Datos Iniciales -->
    <div class="form-step active" data-step="1">
        <div class="form-grid">
            <div class="item">
                <label for="name">Nombre del Appointment</label>
                <input type="text" name="name" id="name"
                    value="{{ !isset($create) ? $appointment->name : old('name') }}" minlength="3" maxlength="80"
                    required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="item">
                <label for="address">Direccion</label>
                <input type="text" name="address" id="address"
                    value="{{ !isset($create) ? $appointment->address : old('address') }}" minlength="3"
                    maxlength="150" required></input>
                @error('address')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="item">
                <label for="specialty_id">Specialty</label>
                <select name="specialty_id" id="specialty_id" required>
                    @foreach ($specialties as $specialty)
                        @if ($specialty->status == 1)
                            <option value="{{ $specialty->id }}"
                                {{ !isset($create) ? ($appointment->specialty_id == $specialty->id ? 'selected' : '') : '' }}>
                                {{ $specialty->name }}</option>
                        @endif
                    @endforeach
                </select>
                @error('specialty_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="item">
                <label for="doctor_id">Doctor</label>
                <select name="doctor_id" id="doctor_id" required>
                    @if (!isset($create) || isset($doctor_id))
                        <option value="{{ $appointment->doctor_id }}" selected>
                            {{ $appointment->doctor->name }}
                        </option>
                    @else
                        <option value="">Seleccione un doctor</option>
                    @endif
                </select>
                @error('doctor_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-navigation navegation-next">
            <button type="button" class="next-btn full-center">Siguiente<i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <!-- Paso 2 - Horarios y Appointments -->
    <div class="form-step" data-step="2">
        <div class="form-grid">
            <div class="box-style">
                <h3>Asignar appointment</h3>
                <div class="item-style">
                    <div class="item">
                        <input type="radio" name="appointment" value="mañana" id="manana"
                            {{ !isset($create) ? ($appointment->appointment == 'mañana' ? 'checked' : '') : '' }}>
                        <label for="manana">Turno Mañana</label>
                    </div>
                    <div class="item">
                        <input type="radio" name="appointment" value="tarde" id="tarde"
                            {{ !isset($create) ? ($appointment->appointment == 'tarde' ? 'checked' : '') : '' }}>
                        <label for="tarde">Turno Tarde</label>
                    </div>
                    <div class="item">
                        <input type="radio" name="appointment" value="noche" id="noche"
                            {{ !isset($create) ? ($appointment->appointment == 'noche' ? 'checked' : '') : '' }}>
                        <label for="noche">Turno Noche</label>
                    </div>
                </div>
            </div>
            <div class="item">
                <label for="cantidad">Cantidad de citas</label>
                <input type="number" name="cantidad" id="cantidad"
                    value="{{ !isset($create) ? $appointment->number_of_slots : old('cantidad') }}" min="1"
                    required>
                @error('cantidad')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="item">
                <label for="start_time">Hora de Inicio</label>
                <input type="time" name="start_time" id="start_time"
                    value="{{ !isset($create) ? $appointment->start_time->format('H:i') : old('start_time') }}"
                    required>
                @error('start_time')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="item">
                <label for="end_time">Hora de Fin</label>
                <input type="time" name="end_time" id="end_time"
                    value="{{ !isset($create) ? $appointment->end_time->format('H:i') : old('end_time') }}">
                @error('end_time')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="box-style">
                <br>
                <H3>Distribucion de horarios</H3>
                <div class="item-style">
                    <div class="item">
                        <input type="radio" name="appointment_type" id="horario1" value="horario1"
                            {{ !isset($appointment) || $appointment->available_time_slots == null ? 'checked' : '' }}>
                        <label for="horario1">Asignar reservas con único horario</label>
                    </div>
                    <div class="item">
                        <input type="radio" name="appointment_type" id="horario2" value="horario2"
                            {{ isset($appointment) && $appointment->available_time_slots != null ? 'checked' : '' }}>
                        <label for="horario2">Asignar reservas con división horaria</label>
                    </div>
                    <input type="hidden" name="available_time_slots" id="available_time_slots">
                </div>
                <div id="horario-box">
                    <H3>Horarios generados</H3>
                    <div class="item-style" id="horarios-item">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-navigation navegation-next">
            <button type="button" class="prev-btn full-center"><i class="bi bi-chevron-left"></i>Anterior</button>
            <button type="button" class="next-btn full-center">Siguiente<i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <!-- Paso 3 - Fecha y Estado -->
    <div class="form-step" data-step="3">
        <div class="form-grid">
            @include('layouts.calendario')
        </div>
        <div class="item">
            <label for="status">Estado</label>
            <select name="status" id="status" required>
                <option value="1" {{ !isset($create) ? ($appointment->status == true ? 'selected' : '') : '' }}>
                    Activo</option>
                <option value="0" {{ !isset($create) ? ($appointment->status == false ? 'selected' : '') : '' }}>
                    Inactivo</option>
            </select>
            @error('status')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-navigation">
            <button type="button" class="prev-btn"><i class="bi bi-chevron-left"></i></button>
            <button type="submit" class="primary-btn">Crear Turno <i class="bi bi-check-circle"></i></button>
        </div>
    </div>
</form>
