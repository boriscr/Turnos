<x-body.body>
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
                        <input type="text" name="nombre" id="nombre" value="{{ $settings->nombre }}" required>
                        <label for="mensaje_bienvenida">Mensaje de bienvenida en la app</label>
                        <small>Se mostrará en la pantalla de inicio de la aplicación.</small>
                        <textarea name="mensaje_bienvenida" id="mensaje_bienvenida" cols="30" rows="5" required>{{ $settings->mensaje_bienvenida }}</textarea>
                        <label for="pie_pagina">Pie de página</label>
                        <small>Se mostrará en la parte inferior de la aplicación.</small>
                        <textarea name="pie_pagina" id="pie_pagina" cols="30" rows="3" required>{{ $settings->pie_pagina }}</textarea>

                        <label for="nombre_institucion">Nombre de la institución prestadora del servicio.</label>
                        <input type="text" name="nombre_institucion" id="nombre_institucion"
                            value="{{ $settings->nombre_institucion }}" required>
                    </div>
                    <br>
                    <hr>
                    <br>
                    <div class="item">
                        <h3>Usuarios</h3>
                        <label for="faltas">Faltas</label>
                        <small>Cantidad de faltas que un usuario puede tener antes de ser bloqueado.</small>
                        <input type="number" name="faltas" id="faltas" value="{{ $settings->faltas }}" required>
                        <label for="faltas">Limites de turnos</label>
                        <small>Cantidad de turnos que un usuario puede reservar en un día.</small>
                        <input type="number" name="limites" id="limites" value="{{ $settings->limites }}" required>
                        <label for="faltas">Cancelacion de turnos.</label>
                        <small>Tiempo mínimo para cancelar un turno (en horas).</small>
                        <input type="number" name="cancelacion_turnos" id="cancelacion_turnos"
                            value="{{ $settings->cancelacion_turnos }}" required>
                    </div>
                    <br>
                    <hr>
                    <br>
                    <div class="item">
                        <h3>Turnos Reservados</h3>
                        <p>¿Desde cuántas horas/días antes los usuarios pueden ver los turnos?</p>
                        <div class="box-style">
                            <label for="preview_window_amount">Ingresá el número (por ejemplo: 24)</label>
                            <input type="number" name="preview_window_amount" id="preview_window_amount"
                                value="{{ $settings->preview_window_amount }}" min="1" required>
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
                                    <input type="radio" name="preview_window_unit" value="hora" id="hora"
                                        {{ $settings->preview_window_unit == 'hora' ? 'checked' : '' }}>
                                    <label for="hora">Horas</label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="preview_window_unit" value="dia" id="dia"
                                        {{ $settings->preview_window_unit == 'dia' ? 'checked' : '' }}>
                                    <label for="dia">Días</label>
                                </div>
                                <div class="item">
                                    <input type="radio" name="preview_window_unit" value="mes" id="mes"
                                        {{ $settings->preview_window_unit == 'mes' ? 'checked' : '' }}>
                                    <label for="mes">Meses</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Actualizar</button>
            </form>
        </div>
</x-body.body>
