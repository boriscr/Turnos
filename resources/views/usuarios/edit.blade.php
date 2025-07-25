<x-app-layout>
    <div class="main centrado-total">
        <div class="container-form centrado-total">
            <h3 class="title-form">Editar usuario</h3>
            <x-form.usuario
            :edit="true"
            :ruta="route('usuario.update', $usuario->id)"
            :name="$usuario->name"
            :surname="$usuario->surname"
            :dni="$usuario->dni"
            :email="$usuario->email"
            :phone="$usuario->phone"
            :birthdate="$usuario->birthdate"
            :genero="$usuario->genero"
            :country="$usuario->country"
            :province="$usuario->province"
            :city="$usuario->city"
            :address="$usuario->address"
            :estado="$usuario->estado"
            :role="$usuario->role"
            />
        </div>
    </div>
</x-app-layout>