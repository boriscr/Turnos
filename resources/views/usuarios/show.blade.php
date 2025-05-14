<x-body.body>
    <div class="content-wrapper">
        <H1 class="section-title">Usuario Info</H1>
        <div class="section-container">
            <div class="card">
                <h1><b>Datos Personales</b></h1>
                <p><b>Nombre y apellido: </b>{{ $usuario->name . ' ' . $usuario->surname }}</p>
                <p><b>DNI: </b>{{ $usuario->dni }}</p>
                <p><b>Fecha de nacimiento: </b>{{ \Carbon\Carbon::parse($usuario->birthdate)->format('d/m/Y') }}</p>
                <p><b>Edad: </b>{{ \Carbon\Carbon::parse($usuario->birthdate)->age }} años</p>
                <p><b>Genero: </b> {{ $usuario->genero }}</p>
                <p><b>Rol: </b>{{ $usuario->role }}</p>
                <p><b>Fecha de creación: </b>{{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y H:i') }}</p>
                <p><b>Ultima actualización:</b> {{ \Carbon\Carbon::parse($usuario->updated_at)->format('d/m/Y H:i') }}
                <p><b>Estado: </b>{{ $usuario->estado ? 'Activo' : 'Inactivo' }}</p>
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
</x-body.body>
