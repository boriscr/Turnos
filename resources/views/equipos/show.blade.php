<x-body.body>
    @if (isset($equipo))
        <div class="main">
            <div class="container-form">
                <h3 class="title-form">Detalles del equipo</h3>
                <p>Nombre y apellido: {{ $equipo->nombre . ' ' . $equipo->apellido }}</p>
                <p>Dni: {{ $equipo->dni }}</p>
                <p>Matricula: {{ $equipo->matricula ?? 'Sin datos' }}</p>
                <p>Especialidad: {{ $equipo->especialidad->nombre }}</p>
                <p>Email: {{ $equipo->email }}</p>
                <p>Celular: {{ $equipo->telefono }}</p>
                <p>Rol: {{ $equipo->rol }}</p>
                <p>Estado: {{ $equipo->estado ? 'Activo' : 'Inactivo' }}</p>
                <p>Fecha de creación: {{ $equipo->created_at }}</p>
                <p>Fecha de última actualizacion: {{ $equipo->updated_at }}</p>
                <div class="acciones">
                    <a href="{{ route('equipo.edit', $equipo->id) }}" class="btn btn-edit"><i
                            class="bi bi-pencil-fill"></i></a>
                    <form action="{{ route('equipo.destroy', $equipo->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete"><i class="bi bi-trash-fill"></i></button>
                    </form>

                </div>
            </div>
        </div>
    @endif
</x-body.body>
