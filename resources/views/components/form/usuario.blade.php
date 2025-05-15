<form action="{{$ruta}}" method="post">
    @csrf
    @if (isset($edit))
        @method('PUT')
    @endif
    <div class="item">
        <label for="name"><span aria-hidden="true" style="color: red;">*</span> Nombre</label>
        <input type="text" name="name" id="name" required value="{{ $nombre }}">
    </div>
    <div class="item">
        <label for="surname"><span aria-hidden="true" style="color: red;">*</span> Apellido</label>
        <input type="text" name="surname" id="surname" required value="{{ $apellido }}">
    </div>
    <div class="item">
        <label for="dni"><span aria-hidden="true" style="color: red;">*</span> Dni</label>
        <input type="text" name="dni" id="dni" required value="{{ $dni }}">
    </div>
    <div class="item">
        <label for="birthdate"><span aria-hidden="true" style="color: red;">*</span> Fecha de nacimiento</label>
        <input type="date" name="birthdate" id="birthdate" required value="{{ $birthdate }}">
    </div>
    <div class="item">
        <label for="genero"><span aria-hidden="true" style="color: red;">*</span> Genero</label>
        <select name="genero" id="genero" class="w-full rounded p-2" required>
            <option value="Femenino" {{ isset($edit) && $genero == 'Femenino' ? 'Selected' : '' }}>Femenino</option>
            <option value="Masculino" {{ isset($edit) && $genero == 'Masculino' ? 'Selected' : '' }}>Masculino</option>
            <option value="No binario" {{ isset($edit) && $genero == 'No binario' ? 'Selected' : '' }}>No binario</option>
            <option value="Otro" {{ isset($edit) && $genero == 'Otro' ? 'Selected' : '' }}>Otro</option>
            <option value="Prefiero no decir" {{ isset($edit) && $genero == 'Prefiero no decir' ? 'Selected' : '' }}>Prefiero
                no decir
            </option>
        </select>
    </div>
    <div class="item">
        <label for="country"><span aria-hidden="true" style="color: red;">*</span> Pais</label>
        <input type="text" name="country" id="country" required value="{{ $pais }}">
    </div>
    <div class="item">
        <label for="province"><span aria-hidden="true" style="color: red;">*</span> Provincia</label>
        <input type="text" name="province" id="province" required value="{{ $provincia }}">
    </div>
    <div class="item">
        <label for="city"><span aria-hidden="true" style="color: red;">*</span> Ciudad</label>
        <input type="text" name="city" id="city" required value="{{ $ciudad }}">
    </div>
    <div class="item">
        <label for="address"><span aria-hidden="true" style="color: red;">*</span> Direccion</label>
        <input type="text" name="address" id="address" required value="{{ $direccion }}">
    </div>
    <div class="item">
        <label for="telefono"><span aria-hidden="true" style="color: red;">*</span> Telefono</label>
        <input type="text" name="telefono" id="telefono" required value="{{ $telefono }}">
    </div>
    <div class="item">
        <label for="email"><span aria-hidden="true" style="color: red;">*</span> Email</label>
        <input type="email" name="email" id="email" required value="{{ $email }}">
    </div>
    <div class="item">
        <label for="email"><span aria-hidden="true" style="color: red;">*</span> Estado</label>
        <select name="estado" id="estado">
            <option value="1" {{ isset($edit) && $estado == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ isset($edit) && $estado == 0 ? 'selected' : '' }}>Inactivo</option>
        </select>
    </div>
    @if (isset($edit))
        <div class="item">
            <label for="role"><span aria-hidden="true" style="color: red;">Rol</span></label>
            <input type="text" required value="{{ $role }}" readonly>
        </div>
    @endif
    <button type="submit">Registrar</button>
</form>
