<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <H3 class="title-form">Settings</H3>
            <form action="{{ route('settings.update') }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <h3>Contenido personalizado</h3>
                    <div class="item">
                        <label for="name">Nombre de la Aplicación</label>
                        <input type="text" name="app[name]" id="name" value="{{ $settings['app.name'] }}"
                            required>
                        @error('app.name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="item">
                        <label for="mensaje_bienvenida">Mensaje de bienvenida en la app</label>
                        <small>Se mostrará en la pantalla de inicio de la aplicación.</small>
                        <textarea name="app[mensaje_bienvenida]" id="mensaje_bienvenida" cols="30" rows="5" required> {{ $settings['app.mensaje_bienvenida'] }}</textarea>
                        @error('app.mensaje_bienvenida')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="item">
                        <label for="pie_pagina">Pie de página</label>
                        <small>Se mostrará en la parte inferior de la aplicación.</small>
                        <textarea name="app[pie_pagina]" id="pie_pagina" cols="30" rows="3" required>{{ $settings['app.pie_pagina'] }}</textarea>
                        @error('app.pie_pagina')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="item">
                        <label for="nombre_institucion">Nombre de la institución prestadora del servicio.</label>
                        <input type="text" name="app[nombre_institucion]" id="nombre_institucion"
                            value="{{ $settings['app.nombre_institucion'] }}" required>
                        @error('app.nombre_institucion')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="item">
                        <label for="mensaje_paciente">Mensaje al solicitar un turno.</label>
                        <small>Se mostrará al paciente cuando solicite un turno.</small>
                        <textarea name="app[mensaje_paciente]" id="mensaje_paciente" cols="30" rows="3" required>{{ $settings['app.mensaje_paciente'] }}</textarea>
                        @error('app.mensaje_paciente')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <h3>Usuarios</h3>
                    <div class="item">
                        <label for="faltas">Faltas</label>
                        <small>Cantidad de faltas que un usuario puede tener antes de ser bloqueado.</small>
                        <input type="number" name="turnos[faltas_maximas]" id="faltas"
                            value="{{ $settings['turnos.faltas_maximas'] }}" required>
                        @error('turnos.faltas_maximas')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="item">
                        <label for="faltas">Limites de turnos</label>
                        <small>Cantidad de turnos que un usuario puede reservar en un día.</small>
                        <input type="number" name="turnos[limite_diario]" id="limites"
                            value="{{ $settings['turnos.limite_diario'] }}" required>
                        @error('turnos.limite_diario')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="item">
                        <label for="horas_cancelacion">Cancelacion de turnos.</label>
                        <small>Tiempo mínimo para cancelar un turno (en horas).</small>
                        <input type="number" name="turnos[horas_cancelacion]" id="horas_cancelacion"
                            value="{{ $settings['turnos.horas_cancelacion'] }}" required>
                        @error('turnos.horas_cancelacion')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="item">
                        <label for="antelacion_reserva">Anticipación para reservar</label>
                        <small>¿Desde cuántas horas/días antes los usuarios pueden ver los turnos?(por ejemplo:
                            24)</small>
                        <input type="number" name="turnos[antelacion_reserva]" id="preview_window_amount"
                            value="{{ $settings['turnos.antelacion_reserva'] }}" min="1" required>
                        @error('turnos.antelacion_reserva')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <br>
                    <div class="box-style">
                        <label for="unidad_antelacion">Elegí la unidad de tiempo: Horas, Días, o Meses</label>
                        <small>
                            *Ejemplo: si configuras “3 días”, el usuario podrá ver los turnos hasta 3 días antes
                            de
                            que ocurran.
                        </small>
                        <div class="item-style">
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
                        </div>
                        @error('turnos.unidad_antelacion')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="item">
                        <label for="intervalo_verificacion">Frecuancia de verificacion Asistencias</label>
                        <small>Cada x hora se verifican las asistencias automáticamente. Marcando si Asistío o No
                            asistio</small>
                        <input type="number" name="asistencias[intervalo_verificacion]"
                            id="hora_verificacion_asistencias"
                            value="{{ $settings['asistencias.intervalo_verificacion'] }}" min="1" required>
                        @error('asistencias.intervalo_verificacion')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="item-colores">
                    <hr>
                    <h1>Personalizacion</h1>
                    <div class="card">
                        <h3>Colores generales</h3>
                        <div class="item">
                            <input type="color" name="design[color_texto_titulo]" id="color_texto_titulo"
                                value="{{ $settings['design.color_texto_titulo'] }}">
                            <label for="color_texto_titulo">Titulos</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[color_primario_btn]" id="color_primario_btn"
                                value="{{ $settings['design.color_primario_btn'] }}">
                            <label for="color_primario_btn">Color primario boton</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[color_secundario_btn]" id="color_secundario_btn"
                                value="{{ $settings['design.color_secundario_btn'] }}">
                            <label for="color_secundario_btn">Color secundario boton</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[color_texto_btn]" id="color_texto_btn"
                                value="{{ $settings['design.color_texto_btn'] }}">
                            <label for="color_texto_btn">Color de texto boton</label>
                        </div>
                    </div>
                    <hr>
                    <div class="card card-oscuro">
                        <h3>Tema Oscuro</h3>
                        <div class="item">
                            <input type="color" name="design[fondo_aplicacion_dark]" id="fondo_aplicacion_dark"
                                value="{{ $settings['design.fondo_aplicacion_dark'] }}">
                            <label for="fondo_aplicacion_dark">Color de fondo</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[color_texto_dark]" id="color_texto_dark"
                                value="{{ $settings['design.color_texto_dark'] }}">
                            <label for="color_texto_dark">Color de texto</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[color_texto_small_dark]" id="color_texto_small_dark"
                                value="{{ $settings['design.color_texto_small_dark'] }}">
                            <label for="color_texto_small_dark">Color de texto pequeño</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[fondo_navbar_dark]" id="fondo_navbar_dark"
                                value="{{ $settings['design.fondo_navbar_dark'] }}">
                            <label for="fondo_navbar_dark"><b>Barra de navegacion</b> color de fondo</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[fondo_login_register_dark]"
                                id="fondo_login_register_dark"
                                value="{{ $settings['design.fondo_login_register_dark'] }}">
                            <label for="fondo_login_register_dark"><b>Login y Register</b> color de fondo</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[color_texto_form_elements_dark]"
                                id="color_texto_form_elements_dark"
                                value="{{ $settings['design.color_texto_form_elements_dark'] }}">
                            <label for="color_texto_form_elements_dark"><b>Elementos en formulario</b> color de
                                texto</label>
                        </div>
                    </div>
                    <hr>
                    <div class="card card-claro">
                        <h3>Tema Claro</h3>
                        <div class="item">
                            <input type="color" name="design[fondo_aplicacion_light]" id="fondo_aplicacion_light"
                                value="{{ $settings['design.fondo_aplicacion_light'] }}">
                            <label for="fondo_aplicacion_light">Color de fondo</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[color_texto_light]" id="color_texto_light"
                                value="{{ $settings['design.color_texto_light'] }}">
                            <label for="color_texto_light">Color de texto</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[color_texto_small_light]" id="color_texto_small_light"
                                value="{{ $settings['design.color_texto_small_light'] }}">
                            <label for="color_texto_small_light">Color de texto pequeño</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[fondo_navbar_light]" id="fondo_navbar_light"
                                value="{{ $settings['design.fondo_navbar_light'] }}">
                            <label for="fondo_navbar_light"><b>Barra de navegacion</b> color de fondo</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[fondo_login_register_light]"
                                id="fondo_login_register_light"
                                value="{{ $settings['design.fondo_login_register_light'] }}">
                            <label for="fondo_login_register_light"><b>Login y Register</b> color de fondo</label>
                        </div>
                        <div class="item">
                            <input type="color" name="design[color_texto_form_elements_light]"
                                id="color_texto_form_elements_light"
                                value="{{ $settings['design.color_texto_form_elements_light'] }}">
                            <label for="color_texto_form_elements_light"><b>Elementos en formulario</b> color de
                                texto</label>
                        </div>
                    </div>
                </div>
                <br>
                <hr>
                <br>
                <button type="submit" class="primary-btn">Actualizar</button>
            </form>
        </div>
</x-app-layout>
