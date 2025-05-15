<x-body.body>
    @if (isset($equipo))
        <div class="content-wrapper">
            <h3 class="title-form">Detalles</h3>
            <div class="section-container">
                <div class="card">
                    <p><b> Nombre y apellido: </b>{{ $equipo->nombre . ' ' . $equipo->apellido }}</p>
                    <p><b> Dni: </b>{{ $equipo->dni }}</p>
                    <p><b> Matricula: </b>{{ $equipo->matricula ?? 'Sin datos' }}</p>
                    <p><b> Especialidad: </b>{{ $equipo->especialidad->nombre }}</p>
                    <p><b> Email: </b>{{ $equipo->email }}</p>
                    <p><b> Celular: </b>{{ $equipo->telefono }}</p>
                    <p><b> Rol: </b>{{ $equipo->rol }}</p>
                    <p><b> Estado: </b>{{ $equipo->estado ? 'Activo' : 'Inactivo' }}</p>
                    <p><b> Fecha de creación: </b>{{ $equipo->created_at }}</p>
                    <p><b> Fecha de última actualizacion: </b>{{ $equipo->updated_at }}</p>
                </div>
            </div>
            <div class="opciones">
                <a href="{{ route('equipo.edit', $equipo->id) }}" class="btn-edit"><i
                        class="bi bi-pencil-fill">Editar</i></a>
                <form action="{{ route('equipo.destroy', $equipo->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill"></i>
                        Eliminar</button>
                </form>
            </div>
        </div>
    @endif
    <script src="../../delete-btn.js"></script>

</x-body.body>
