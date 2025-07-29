<form action="{{ $ruta }}" method="post">
    @csrf
    @if (isset($edit))
        @method('PUT')
    @endif
    <div class="item">
        <label for="name"><span aria-hidden="true" style="color: red;">*</span> Nombre</label>
        <input type="text" name="name" id="name" required value="{{ $name }}">
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="surname"><span aria-hidden="true" style="color: red;">*</span> Apellido</label>
        <input type="text" name="surname" id="surname" required value="{{ $surname }}">
        @error('surname')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="idNumber"><span aria-hidden="true" style="color: red;">*</span> Dni</label>
        <input type="text" name="idNumber" id="idNumber" required value="{{ $idNumber }}">
        @error('idNumber')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="birthdate"><span aria-hidden="true" style="color: red;">*</span> Fecha de nacimiento</label>
        <input type="date" name="birthdate" id="birthdate" required value="{{ $birthdate }}">
        @error('birthdate')
            <div class="error">{{ $message }}</div>
        @enderror
        <small id="edad"></small>
    </div>
    <div class="item">
        <label for="gender"><span aria-hidden="true" style="color: red;">*</span> Genero</label>
        <select name="gender" id="gender" class="w-full rounded p-2" required>
            <option value="Femenino" {{ isset($edit) && $gender == 'Femenino' ? 'Selected' : '' }}>Femenino</option>
            <option value="Masculino" {{ isset($edit) && $gender == 'Masculino' ? 'Selected' : '' }}>Masculino</option>
            <option value="No binario" {{ isset($edit) && $gender == 'No binario' ? 'Selected' : '' }}>No binario
            </option>
            <option value="Otro" {{ isset($edit) && $gender == 'Otro' ? 'Selected' : '' }}>Otro</option>
            <option value="Prefiero no decir" {{ isset($edit) && $gender == 'Prefiero no decir' ? 'Selected' : '' }}>
                Prefiero
                no decir
            </option>
        </select>
        @error('gender')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="country"><span aria-hidden="true" style="color: red;">*</span> Pais</label>
        <input type="text" name="country" id="country" required value="{{ $country }}">
        @error('country')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="province"><span aria-hidden="true" style="color: red;">*</span> Provincia</label>
        <input type="text" name="province" id="province" required value="{{ $province }}">
        @error('province')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="city"><span aria-hidden="true" style="color: red;">*</span> Ciudad</label>
        <input type="text" name="city" id="city" required value="{{ $city }}">
        @error('city')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="address"><span aria-hidden="true" style="color: red;">*</span> Direccion</label>
        <input type="text" name="address" id="address" required value="{{ $address }}">
        @error('address')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>
    <div class="item">
        <label for="phone"><span aria-hidden="true" style="color: red;">*</span> Telefono</label>
        <input type="text" name="phone" id="phone" required value="{{ $phone }}">
        @error('phone')
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
        <label for="status"><span aria-hidden="true" style="color: red;">*</span> Estado</label>
        <select name="status" id="status">
            <option value="1" {{ isset($edit) && $status == 1 ? 'selected' : '' }}>Activo</option>
            <option value="0" {{ isset($edit) && $status == 0 ? 'selected' : '' }}>Inactivo</option>
        </select>
        @error('status')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    @if (isset($edit))
        <div class="item">
            <label for="role"><span aria-hidden="true" style="color: red;">*</span> Estado</label>
            <select name="role" id="role">
                <option value="user" {{ isset($edit) && $role == 'user' ? 'selected' : '' }}>User</option>
                <option value="medico" {{ isset($edit) && $role == 'medico' ? 'selected' : '' }}>Medico</option>
                <option value="admin" {{ isset($edit) && $role == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
    @endif
    <br>
    <hr>
    <br>
    <button type="submit" class="primary-btn">Registrar</button>
</form>
