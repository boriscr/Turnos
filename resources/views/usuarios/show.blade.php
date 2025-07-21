<x-app-layout>
    <div class="content-wrapper">
        <H3 class="title-form">Detalles</H3>

        <div class="section-container centrado-total">
            <div class="card">
                <h1><b>Datos Personales</b></h1>
                <p><b>Nombre y apellido: </b>{{ $usuario->name . ' ' . $usuario->surname }}</p>
                <p><b>DNI: </b>{{ $usuario->dni }}</p>
                <p><b>Fecha de nacimiento: </b>{{ \Carbon\Carbon::parse($usuario->birthdate)->format('d/m/Y') }}</p>
                <p><b>Edad: </b>{{ \Carbon\Carbon::parse($usuario->birthdate)->age }} años</p>
                <p><b>Genero: </b> {{ $usuario->genero }}</p>
                <p><b>Rol:</b> {{ $usuario->getRoleNames()->first() }}</p>
                <p><b>Fecha de creación: </b>{{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y H:i') }}</p>
                <p><b>Ultima actualización:</b> {{ \Carbon\Carbon::parse($usuario->updated_at)->format('d/m/Y H:i') }}
                <p><b>Estado: </b>{{ $usuario->estado ? 'Activo' : 'Inactivo' }}</p>
                <p><b>Turnos perdidos: </b>{{ $usuario->faults === null ? 'Ninguno' : $usuario->faults }}</p>

            </div>
            <div class="card">
                <h1><b>Direccion</b></h1>
                <p><b>Pais: </b> {{ $usuario->country }}</p>
                <p><b>Provincia: </b>{{ $usuario->province }}</p>
                <p><b>Ciudad: </b>{{ $usuario->city }}</p>
                <p><b>Calle: </b>{{ $usuario->address }}</p>
            </div>
            <div class="card">
                <h1><b>Datos de contacto</b></h1>
                <p><b>Teléfono: </b>{{ $usuario->phone }}</p>
                <p><b>Correo electrónico: </b>{{ $usuario->email }}</p>
            </div>
        </div>
        <div class="opciones centrado-total">
            <a href="{{ route('usuario.edit', $usuario->id) }}" class="btn-edit"><i
                    class="bi bi-pencil-fill">Editar</i></a>
            <form action="{{ route('usuario.destroy', $usuario->id) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill"></i> Eliminar</button>
            </form>
        </div>
    </div>
</x-app-layout>