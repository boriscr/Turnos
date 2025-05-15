<form action="{{ $ruta }}" method="post">
    @csrf
    @if (isset($edit))
        @method('PUT')
    @endif
    <div class="item">
        <label for="nombre"><span aria-hidden="true" style="color: red;">*</span> Nombre de la especialidad</label>
        <input type="text" name="nombre" id="nombre" required value="{{ $nombre }}">
        @error('nombre')
            <div style="color: red;">
                <ul>
                    <li>{{ $message }}</li>
                </ul>
            </div>
        @enderror
    </div>
    <div class="item">
        <label for="descripcion">Descripcion</label>
        <textarea name="descripcion" id="descripcion" cols="30" rows="5">{{ $descripcion }}</textarea>
        @error('descripcion')
            <div style="color: red;">
                <ul>
                    <li>{{ $message }}</li>
                </ul>
            </div>
        @enderror
    </div>

    <div class="item">
        <label for="descripcion-esp"><span aria-hidden="true" style="color: red;">*</span> Estado</label>
        <select name="estado" id="estado">
            <option value="1"{{ isset($edit) ? '' : 'selected' }}
                {{ isset($estado) && $estado == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ isset($estado) && $estado == 0 ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
    <br>
    <hr>
    <button type="submit">Registrar</button>
    <button type="button" id="close-btn" style="background: red; color: #fff;">Cancelar</button>
</form>
