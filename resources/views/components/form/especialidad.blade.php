<form action="{{ $ruta }}" method="post">
    @csrf
    @if (isset($edit))
        @method('PUT')
    @endif
    @if (isset($crear))
        <h3 class="title-form">Crear especialidad</h3>
        <hr>
    @endif
    <div class="item">
        <label for="nombre"><span aria-hidden="true" style="color: red;">*</span> Nombre de la especialidad</label>
        <input type="text" name="nombre" id="nombre" placeholder="Ej. Nutricionista, Traumatólogo, Odontología..."
            required value="{{ $nombre }}">
        @error('nombre')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="descripcion"><span aria-hidden="true" style="color: red;">*</span>Descripcion</label>
        <textarea name="descripcion" id="descripcion" cols="30" rows="5"
            placeholder="Describe las funciones y servicios que ofrece esta especialidad. Puedes incluir ubicaciones, requisitos o advertencias para los pacientes."
            required>{{ $descripcion }}</textarea>
        @error('descripcion')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="item">
        <label for="descripcion-esp"><span aria-hidden="true" style="color: red;">*</span> Estado</label>
        <select name="estado" id="estado" required>
            <option value="1"{{ isset($edit) ? '' : 'selected' }}
                {{ isset($estado) && $estado == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ isset($estado) && $estado == 0 ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
    <br>
    <hr>
    <br>
    <button type="submit" class="primary-btn">Registrar</button>
    @if (isset($crear))
        <button type="button" id="close-btn" style="background: red; color: #fff;">Cancelar</button>
    @endif
</form>
