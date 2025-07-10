<x-app-layout>
    <div class="main">
        <div class="container-form">
            <H3 class="title-form">Settings</H3>
            <form action="{{ route('settings.update') }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="item">
                        <h3>Contenido personalizado</h3>
                        <label for="nombre">Nombre de la Aplicación</label>
                        <input type="text" name="app[nombre]" id="nombre" value="{{ $settings['app.nombre'] }}"
                            required>
                        @error('app.nombre')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        <label for="mensaje_bienvenida">Mensaje de bienvenida en la app</label>
                        <small>Se mostrará en la pantalla de inicio de la aplicación.</small>
                        <textarea name="app[mensaje_bienvenida]" id="mensaje_bienvenida" cols="30" rows="5" required> {{ $settings['app.mensaje_bienvenida'] }}</textarea>
                        @error('app.mensaje_bienvenida')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        <label for="pie_pagina">Pie de página</label>
                        <small>Se mostrará en la parte inferior de la aplicación.</small>
                        <textarea name="app[pie_pagina]" id="pie_pagina" cols="30" rows="3" required>{{ $settings['app.pie_pagina'] }}</textarea>
                        @error('app.pie_pagina')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        <label for="nombre_institucion">Nombre de la institución prestadora del servicio.</label>
                        <input type="text" name="app[nombre_institucion]" id="nombre_institucion"
                            value="{{ $settings['app.nombre_institucion'] }}" required>
                        @error('app.nombre_institucion')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        <label for="mensaje_paciente">Mensaje al solicitar un turno.</label>
                        <small>Se mostrará al paciente cuando solicite un turno.</small>
                        <textarea name="app[mensaje_paciente]" id="mensaje_paciente" cols="30" rows="3" required>{{ $settings['app.mensaje_paciente'] }}</textarea>
                        @error('app.mensaje_paciente')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <br>
                    <hr>
                    <br>
                    <div class="item">
                        <h3>Usuarios</h3>
                        <label for="faltas">Faltas</label>
                        <small>Cantidad de faltas que un usuario puede tener antes de ser bloqueado.</small>
                        <input type="number" name="turnos[faltas_maximas]" id="faltas"
                            value="{{ $settings['turnos.faltas_maximas'] }}" required>
                        @error('turnos.faltas_maximas')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        <label for="faltas">Limites de turnos</label>
                        <small>Cantidad de turnos que un usuario puede reservar en un día.</small>
                        <input type="number" name="turnos[limite_diario]" id="limites"
                            value="{{ $settings['turnos.limite_diario'] }}" required>
                        @error('turnos.limite_diario')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        <label for="faltas">Cancelacion de turnos.</label>
                        <small>Tiempo mínimo para cancelar un turno (en horas).</small>
                        <input type="number" name="turnos[horas_cancelacion]" id="cancelacion_turnos"
                            value="{{ $settings['turnos.horas_cancelacion'] }}" required>
                        @error('turnos.horas_cancelacion')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <br>
                    <hr>
                    <br>
                    <div class="item">
                        <h3>Turnos Reservados</h3>
                        <p>¿Desde cuántas horas/días antes los usuarios pueden ver los turnos?</p>
                        <div class="box-style">
                            <label for="preview_window_amount">Ingresá el número (por ejemplo: 24)</label>
                            <input type="number" name="turnos[antelacion_reserva]" id="preview_window_amount"
                                value="{{ $settings['turnos.antelacion_reserva'] }}" min="1" required>
                            @error('turnos.antelacion_reserva')
                                <div class="error">{{ $message }}</div>
                            @enderror
                            <br>
                            <small>Cantidad de unidades antes del turno que el usuario podrá
                                ver.</small>

                            <h3>Elegí la unidad de tiempo: Horas, Días, o Meses</h3>
                            <div class="item-style">
                                <small>
                                    *Ejemplo: si configuras “3 días”, el usuario podrá ver los turnos hasta 3 días antes
                                    de
                                    que ocurran.
                                </small>
                                <div class="item">
                                    <input type="radio" name="turnos[unidad_antelacion]" value="hora" id="horas"
                                        {{ $settings['turnos.unidad_antelacion'] == 'hora' ? 'checked' : '' }}>
                                    <label for="hora">Horas</label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="turnos[unidad_antelacion]" value="dia" id="dias"
                                        {{ $settings['turnos.unidad_antelacion'] == 'dia' ? 'checked' : '' }}>
                                    <label for="dia">Días</label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="turnos[unidad_antelacion]" value="mes" id="mes"
                                        {{ $settings['turnos.unidad_antelacion'] == 'mes' ? 'checked' : '' }}>
                                    <label for="mes">Meses</label>
                                </div>
                                @error('turnos.unidad_antelacion')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <h3>Frecuancia de verificacion Asistencias</h3>
                        <small>Cada x hora se verifican las asistencias automáticamente. Marcando si Asistío o No
                            asistio</small>
                        <input type="number" name="asistencias[intervalo_verificacion]"
                            id="hora_verificacion_asistencias"
                            value="{{ $settings['asistencias.intervalo_verificacion'] }}" min="1" required>
                        @error('asistencias.intervalo_verificacion')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">Actualizar</button>
            </form>
        </div>
</x-app-layout>
