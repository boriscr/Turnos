<x-app-layout>
    <div class="content-wrapper">
        <H3 class="title-form">Detalles</H3>

        <div class="section-container full-center">
            <div class="card">
                <h1><b>Datos Personales</b></h1>
                <p><b>Nombre y surname: </b>{{ $user->name . ' ' . $user->surname }}</p>
                <p><b>DNI: </b>{{ $user->idNumber }}</p>
                <p><b>Fecha de nacimiento: </b>{{ \Carbon\Carbon::parse($user->birthdate)->format('d/m/Y') }}</p>
                <p><b>Edad: </b>{{ \Carbon\Carbon::parse($user->birthdate)->age }} años</p>
                <p><b>Genero: </b> {{ $user->gender }}</p>
                <p><b>Rol:</b> {{ $user->getRoleNames()->first() }}</p>
                <p><b>Fecha de creación: </b>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</p>
                <p><b>Ultima actualización:</b> {{ \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i') }}
                <p><b>Estado: </b>{{ $user->status ? 'Activo' : 'Inactivo' }}</p>
                <p><b>Appointments perdidos: </b>{{ $user->faults === null ? 'Ninguno' : $user->faults }}</p>

            </div>
            <div class="card">
                <h1><b>Direccion</b></h1>
                <p><b>Pais: </b> {{ $user->country }}</p>
                <p><b>Provincia: </b>{{ $user->province }}</p>
                <p><b>Ciudad: </b>{{ $user->city }}</p>
                <p><b>Calle: </b>{{ $user->address }}</p>
            </div>
            <div class="card">
                <h1><b>Datos de contacto</b></h1>
                <p><b>Teléfono: </b>{{ $user->phone }}</p>
                <p><b>Correo electrónico: </b>{{ $user->email }}</p>
            </div>
        </div>
        <div class="opciones full-center">
            <a href="{{ route('user.edit', $user->id) }}" class="btn-edit"><i
                    class="bi bi-pencil-fill">Editar</i></a>
            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill"></i> Eliminar</button>
            </form>
        </div>
    </div>
</x-app-layout>