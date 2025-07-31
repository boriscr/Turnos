<x-app-layout>
    @if (isset($doctor))
        <div class="content-wrapper">
            <h3 class="title-form">Detalles</h3>
            <div class="section-container centrado-t">
                <div class="card">
                    <p><b> Nombre y surname: </b>{{ $doctor->name . ' ' . $doctor->surname }}</p>
                    <p><b> Dni: </b>{{ $doctor->idNumber }}</p>
                    <p><b>Perfil:</b>
                        @if ($doctor->user_id)
                            <a href="{{ route('user.show', $doctor->user_id) }}">
                                Ver <i class="bi bi-eye"></i>
                            </a>
                        @else
                            No tiene un perfil de user
                        @endif
                    </p>
                    <p><b> Matricula: </b>{{ $doctor->licenseNumber ?? 'Sin datos' }}</p>
                    <p><b> Specialty: </b>{{ $doctor->specialty->name ?? 'Sin datos' }}</p>
                    <p><b> Email: </b>{{ $doctor->email }}</p>
                    <p><b> Celular: </b>{{ $doctor->phone }}</p>
                    <p><b> Rol: </b>{{ $doctor->role }}</p>
                    <p><b> Estado: </b>{{ $doctor->status ? 'Activo' : 'Inactivo' }}</p>
                    <p><b> Fecha de creación: </b>{{ $doctor->created_at }}</p>
                    <p><b> Fecha de última actualizacion: </b>{{ $doctor->updated_at }}</p>
                </div>
            </div>
            <div class="opciones full-center">
                <a href="{{ route('doctor.edit', $doctor->id) }}" class="btn-edit"><i
                        class="bi bi-pencil-fill">Editar</i></a>
                <form action="{{ route('doctor.destroy', $doctor->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill">Eliminar</i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
