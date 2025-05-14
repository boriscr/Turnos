<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Editar usuario</h3>
            <x-form.usuario
            :edit="true"
            :ruta="route('usuario.update', $usuario->id)"
            :nombre="$usuario->name"
            :apellido="$usuario->surname"
            :dni="$usuario->dni"
            :email="$usuario->email"
            :telefono="$usuario->phone"
            :birthdate="$usuario->birthdate"
            :genero="$usuario->genero"
            :pais="$usuario->country"
            :provincia="$usuario->province"
            :ciudad="$usuario->city"
            :direccion="$usuario->address"
            :estado="$usuario->estado"
            :role="$usuario->role"
            />
        </div>
    </div>
</x-body.body>