<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Agregar nuevo usuario</h3>
            <x-form.usuario
            :ruta="route('usuario.store')"
            :nombre="old('nombre')"
            :apellido="old('apellido')"
            :dni="old('dni')"
            :email="old('email')"
            :telefono="old('telefono')"
            :birthdate="old('birthdate')"
            :genero="old('genero')"
            :pais="old('pais')"
            :provincia="old('provincia')"
            :ciudad="old('ciudad')"
            :direccion="old('direccion')"
            :estado="old('estado')"
            />
        </div>
    </div>
</x-body.body>