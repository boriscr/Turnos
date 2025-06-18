<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Crear turno</h3>

            <!-- Indicador de pasos -->
            <div class="step-indicator">
                <div class="step active" data-step="1"><i class="bi bi-stars"></i> Datos</div>
                <div class="step" data-step="2"><i class="bi bi-clock-history"></i> Horario/s</div>
                <div class="step" data-step="3"><i class="bi bi-calendar-date-fill"></i> Fecha/s</div>
            </div>

            <form x-data="iosCalendar()" x-init="init({{ $fechas ?? '[]' }})" id="multiStepForm"
                @submit.prevent="updateSelectedDatesInput(); $el.submit()" method="POST"
                action="{{ route('turnos.store') }}">
                @csrf
                <!-- Paso 1 - Datos Iniciales -->
                <div class="form-step active" data-step="1">
                    <div class="form-grid">
                        <div class="item">
                            <label for="nombre">Nombre del Turno</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                                class="form-control">
                        </div>

                        <div class="item">
                            <label for="direccion">Direccion</label>
                            <textarea name="direccion" id="direccion" rows="2" required class="form-control">{{ old('direccion') }}</textarea>
                        </div>

                        <div class="item">
                            <label for="especialidad_id">Especialidad</label>
                            <select name="especialidad_id" id="especialidad_id" required class="form-control">
                                @foreach ($especialidades as $especialidad)
                                    @if ($especialidad->estado == 1)
                                        <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="item">
                            <label for="equipo_id">Equipo</label>
                            <select name="equipo_id" id="equipo_id" required class="form-control">
                                <option value="">Seleccione un equipo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-navigation navegation-next">
                        <button type="button" class="next-btn">Siguiente</button>
                    </div>
                </div>

                <!-- Paso 2 - Horarios y Turnos -->
                <div class="form-step" data-step="2">
                    <div class="form-grid">
                        <div class="box-style">
                            <h3>Asignar turno</h3>
                            <div class="item-style">
                                <div class="item">
                                    <input type="checkbox" name="turno" value="mañana" id="manana">
                                    <label for="manana">Turno Mañana</label>
                                </div>

                                <div class="item">
                                    <input type="checkbox" name="turno" value="tarde" id="tarde">
                                    <label for="tarde">Turno Tarde</label>
                                </div>
                                <div class="item">
                                    <input type="checkbox" name="turno" value="noche" id="noche">
                                    <label for="noche">Turno Noche</label>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <label for="cantidad">Cantidad de citas</label>
                            <input type="number" name="cantidad" id="cantidad" value="{{ old('cantidad') }}"
                                min="1" required class="form-control">
                        </div>

                        <div class="item">
                            <label for="hora_inicio">Hora de Inicio</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('inicio') }}"
                                required class="form-control">
                        </div>

                        <div class="item">
                            <label for="hora_fin">Hora de Fin</label>
                            <input type="time" name="hora_fin" id="hora_fin" value="{{ old('fin') }}" required
                                class="form-control">
                        </div>

                        <div class="box-style">
                            <br>
                            <H3>Distribucion de horarios</H3>
                            <input type="hidden" name="horarios_disponibles" id="horarios_disponibles">
                            <div class="item-style">
                                <div class="item">
                                    <input type="checkbox" name="horario1" id="horario1"
                                        value="{{ old('horario1') }}">
                                    <label for="horario1">Asignar turnos sin horarios</label>
                                </div>
                                <div class="item">
                                    <input type="checkbox" name="horario2" id="horario2"
                                        value="{{ old('horario2') }}">
                                    <label for="horario2">Asignar turnos con division horaria</label>
                                </div>
                            </div>
                            <div id="horario-box">
                                <H3>Horarios generados</H3>
                                <div class="item-style" id="horarios-item">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-navigation navegation-next">
                        <button type="button" class="prev-btn">Anterior</button>
                        <button type="button" class="next-btn">Siguiente</button>
                    </div>
                </div>

                <!-- Paso 3 - Fecha y Estado -->
                <div class="form-step" data-step="3">
                    <div class="form-grid">
                        @include('layouts.calendario')
                        <input type="hidden" name="selected_dates" x-ref="selectedDatesInput">
                    </div>
                    <div class="item">
                        <label for="estado">Estado</label>
                        <select name="estado" id="estado" required class="form-control">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-navigation">
                        <button type="button" class="prev-btn"><i class="bi bi-chevron-left"></i></button>
                        <button type="submit" class="submit-btn">Crear Turno <i
                                class="bi bi-check-circle"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script src="../forms/calendario.js"></script>
    <script src="../forms/horarios.js"></script>
    <script src="../forms/checkboxs.js"></script>
    <script src="../forms/especialidades.js"></script>
    <script src="../forms/formulario-3paso-turno.js"></script>

</x-body.body>
