<form action="" method="post">
    @csrf
    <div class="item">
        <label for="nombre"><span aria-hidden="true" style="color: red;">*</span> Nombre</label>
        <input type="text" name="nombre" id="nombre" required {{ old('nombre') }}>
    </div>
    <div class="item">
        <label for="apellido"><span aria-hidden="true" style="color: red;">*</span> Apellido</label>
        <input type="text" name="apellido" id="apellido" required {{ old('apellido') }}>
    </div>
    <div class="item">
        <label for="dni"><span aria-hidden="true" style="color: red;">*</span>  Dni</label>
        <input type="text" name="dni" id="dni" required {{ old('dni') }}>
    </div>
    <div class="item">
        <label for="email"><span aria-hidden="true" style="color: red;">*</span> Email</label>
        <input type="email" name="email" id="email" required {{ old('email') }}>
    </div>
    <div class="item">
        <label for="telefono"><span aria-hidden="true" style="color: red;">*</span> Telefono</label>
        <input type="text" name="telefono" id="telefono" required {{ old('telefono') }}>
    </div>

    <button type="submit">Registrar</button>
</form>
