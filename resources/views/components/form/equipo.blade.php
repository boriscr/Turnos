<form action="{{ $ruta }}" method="post">
    @csrf
    @if (!isset($crear))
        @method('PUT')
    @endif
    <div class="item">
        <label for="nombre"><span aria-hidden="true" style="color: red;">*</span> Nombre</label>
        <input type="text" name="nombre" id="nombre" required value="{{ $nombre }}">
        @error('nombre')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="apellido"><span aria-hidden="true" style="color: red;">*</span> Apellido</label>
        <input type="text" name="apellido" id="apellido" required value="{{ $apellido }}">
        @error('apellido')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="dni"><span aria-hidden="true" style="color: red;">*</span> Dni</label>
        <input type="number" name="dni" id="dni" required value="{{ $dni }}">
        @error('dni')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="email"><span aria-hidden="true" style="color: red;">*</span> Email</label>
        <input type="email" name="email" id="email" required value="{{ $email }}">
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="telefono"><span aria-hidden="true" style="color: red;">*</span> Telefono</label>
        <input type="tel" name="telefono" id="telefono" required value="{{ $telefono }}">
        @error('telefono')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="especialidad_id"><span aria-hidden="true" style="color: red;">*</span> Especialidad</label>
        <select name="especialidad_id" id="especialidad_id" required>
            <option value="">Seleccionar</option>
            @if (isset($especialidades) && !empty($especialidades))
                @foreach ($especialidades as $especialidades)
                    <option value="{{ $especialidades->id }}"
                        {{ $especialidades->id == $especialidad ? 'selected' : '' }}>{{ $especialidades->nombre }}
                    </option>
                @endforeach
            @else
                <option value="">No hay especialidades disponibles</option>
            @endif
        </select>
    </div>
    @if (isset($nuevoEquipo) != null || isset($crear))
        <div class="box-new-especialidad">
            <button type="button" id="especialidad-btn">Crear especialidad</button>
        </div>
    @endif
    <div class="item">
        <label for="matricula"><span aria-hidden="true" style="color: red;">*</span> Matricula</label>
        <input type="text" name="matricula" id="matricula" required value="{{ $matricula }}">
        @error('matricula')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="item">
        <label for="role"><span aria-hidden="true" style="color: red;">*</span> Rol</label>
        <select name="role" id="role" required value="{{ $role }}">
            <option value="">Seleccionar</option>
            <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="equipo" {{ $role == 'equipo' ? 'selected' : '' }}>Equipo</option>
        </select>
    </div>

    <div class="item">
        <label for="descripcion-esp"><span aria-hidden="true" style="color: red;">*</span> Estado</label>
        <select class="{{isset($estado) && $estado == 0? 'btn-danger':'btn-success'}}" name="estado" id="estado" required>
            <option value="1"{{ isset($edit) ? '' : 'selected' }}
                {{ isset($estado) && $estado == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ isset($estado) && $estado == 0 ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
    <button type="submit" class="submit-btn">{{ isset($crear) ? 'Registrar' : 'Actualizar' }}</button>
</form>
