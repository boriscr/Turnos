<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <h3 class="title-form">Editar turno</h3>

            <!-- Indicador de pasos -->
            <div class="step-indicator">
                <div class="step active" data-step="1"><i class="bi bi-stars"></i> Datos</div>
                <div class="step" data-step="2"><i class="bi bi-clock-history"></i> Horario/s</div>
                <div class="step" data-step="3"><i class="bi bi-calendar-date-fill"></i> Fecha/s</div>
            </div>
            <form x-data="iosCalendar()" x-init="init({{ json_encode($fechas) }})" id="multiStepForm"
                @submit.prevent="updateSelectedDatesInput(); $el.submit()" method="POST"
                action="{{ route('turnos.update', $turno->id) }}">
                @csrf
                @method('PATCH')

                <!-- Paso 1 - Datos Iniciales -->
                <div class="form-step active" data-step="1">
                    <div class="form-grid">
                        <div class="item">
                            <label for="name">Nombre del Turno</label>
                            <input type="text" name="name" id="name" value="{{ $name }}" required>
                            @error('name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="item">
                            <label for="direccion">Dirección</label>
                            <textarea name="direccion" id="direccion" rows="2" required>{{ $direccion }}</textarea>
                            @error('direccion')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="item">
                            <label for="specialty_id">Specialty</label>
                            <select name="specialty_id" id="specialty_id" required>
                                @foreach ($specialties as $specialty)
                                    <option value="{{ $specialty->id }}"
                                        {{ $specialty_id == $specialty->id ? 'selected' : '' }}>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialty_id')
                                <div class="error">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="item">
                            <label for="doctor_id">Doctor</label>
                            <select name="doctor_id" id="doctor_id" required>
                                @if ($doctor_id)
                                    <option value="{{ $doctor_id }}" selected>
                                        {{ $medico_nombre }}
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
                        <button type="button" class="next-btn full-center">Siguiente</button>
                    </div>
                </div>

                <!-- Paso 2 - Horarios y Turnos -->
                <div class="form-step" data-step="2">
                    <div class="form-grid">
                        <div class="box-style">
                            <h3>Asignar turno</h3>
                            <div class="item-style">
                                <div class="item">
                                    <input type="radio" name="turno" value="mañana" id="manana"
                                        {{ $turnoTipo == 'mañana' ? 'checked' : '' }}>
                                    <label for="manana">Turno Mañana</label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="turno" value="tarde" id="tarde"
                                        {{ $turnoTipo == 'tarde' ? 'checked' : '' }}>
                                    <label for="tarde">Turno Tarde</label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="turno" value="noche" id="noche"
                                        {{ $turnoTipo == 'noche' ? 'checked' : '' }}>
                                    <label for="noche">Turno Noche</label>
                                </div>
                            </div>
                        </div>

                        <div class="item">
                            <label for="cantidad">Cantidad de citas</label>
                            <input type="number" name="cantidad" id="cantidad" value="{{ $cantidad }}"
                                min="1" required>
                            @error('cantidad')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="item">
                            <label for="hora_inicio">Hora de Inicio</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" value="{{ $inicio }}"
                                required>
                            @error('hora_inicio')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="item">
                            <label for="hora_fin">Hora de Fin</label>
                            <input type="time" name="hora_fin" id="hora_fin" value="{{ $fin }}" required>
                            @error('hora_fin')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="box-style">
                            <br>
                            <h3>Distribución de horarios</h3>
                            <input type="hidden" name="horarios_disponibles" id="horarios_disponibles"
                                value="{{ $horarios_disponibles }}">
                            <div class="item-style">
                                <div class="item">
                                    <input type="radio" name="horario1" id="horario1"
                                        {{ $horarios_disponibles == null ? 'checked' : '' }}>
                                    <label for="horario1">Asignar turnos sin horarios</label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="horario2" id="horario2"
                                        {{ $horarios_disponibles == !null ? 'checked' : '' }}>
                                    <label for="horario2">Asignar turnos con división horaria</label>
                                </div>
                            </div>
                            <div id="horario-box">
                                <h3>Horarios generados</h3>
                                <div class="item-style" id="horarios-item">
                                    <!-- Horarios generados dinámicamente -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation navegation-next">
                        <button type="button" class="prev-btn"><i class="bi bi-chevron-left"></i>Anterior</button>
                        <button type="button" class="next-btn full-center">Siguiente<i
                                class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Paso 3 - Fecha y Estado -->
                <div class="form-step" data-step="3">
                    <div class="form-grid">
                        @include('layouts.calendario')
                        <input type="hidden" name="selected_dates" x-ref="selectedDatesInput">
                    </div>
                    <div class="item">
                        <label for="status">Estado</label>
                        <select name="status" id="status" required>
                            <option {{ isset($edit) ? ($turno->status == true ? 'selected' : '') : '' }}
                                value="1">
                                Activo</option>
                            <option {{ isset($edit) ? ($turno->status == false ? 'selected' : '') : '' }}
                                value="0">
                                Inactivo</option>
                            @error('status')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </select>
                    </div>
                    <div class="form-navigation">
                        <button type="button" class="prev-btn"><i class="bi bi-chevron-left"></i></button>
                        <button type="submit" class="primary-btn">Actualizar Turno <i
                                class="bi bi-check-circle"></i></button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
