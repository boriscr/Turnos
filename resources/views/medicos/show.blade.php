<x-app-layout>
    @if (isset($medico))
        <div class="content-wrapper">
            <h3 class="title-form">Detalles</h3>
            <div class="section-container centrado-t">
                <div class="card">
                    <p><b> Nombre y surname: </b>{{ $medico->name . ' ' . $medico->surname }}</p>
                    <p><b> Dni: </b>{{ $medico->idNumber }}</p>
                    <p><b>Perfil:</b>
                        @if ($medico->user_id)
                            <a href="{{ route('usuario.show', $medico->user_id) }}">
                                Ver <i class="bi bi-eye"></i>
                            </a>
                        @else
                            No tiene un perfil de usuario
                        @endif
                    </p>
                    <p><b> Matricula: </b>{{ $medico->licenseNumber ?? 'Sin datos' }}</p>
                    <p><b> Especialidad: </b>{{ $medico->specialty->name ?? 'Sin datos' }}</p>
                    <p><b> Email: </b>{{ $medico->email }}</p>
                    <p><b> Celular: </b>{{ $medico->phone }}</p>
                    <p><b> Rol: </b>{{ $medico->role }}</p>
                    <p><b> Estado: </b>{{ $medico->status ? 'Activo' : 'Inactivo' }}</p>
                    <p><b> Fecha de creación: </b>{{ $medico->created_at }}</p>
                    <p><b> Fecha de última actualizacion: </b>{{ $medico->updated_at }}</p>
                </div>
            </div>
            <div class="opciones full-center">
                <a href="{{ route('medico.edit', $medico->id) }}" class="btn-edit"><i
                        class="bi bi-pencil-fill">Editar</i></a>
                <form action="{{ route('medico.destroy', $medico->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill">Eliminar</i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
