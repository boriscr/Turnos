<x-app-layout>
    <div class="main centrado-total">
        <div class="container-form centrado-total">
            <h3 class="title-form">Editar usuario</h3>
            <x-form.usuario
            :edit="true"
            :ruta="route('usuario.update', $usuario->id)"
            :name="$usuario->name"
            :surname="$usuario->surname"
            :idNumber="$usuario->idNumber"
            :email="$usuario->email"
            :phone="$usuario->phone"
            :birthdate="$usuario->birthdate"
            :gender="$usuario->gender"
            :country="$usuario->country"
            :province="$usuario->province"
            :city="$usuario->city"
            :address="$usuario->address"
            :isActive="$usuario->isActive"
            :role="$usuario->role"
            />
        </div>
    </div>
</x-app-layout>