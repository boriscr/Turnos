<x-app-layout>
    @if (isset($specialty))
        <div class="content-wrapper">
            <h1>Detalles</h1>
            <div class="section-container full-center">
                <div class="card">
                    <h2>Información</h2>
                    <p><b>Nombre: </b>{{ $specialty->name }}</p>
                    <p><b>Descripcion:</b> {{ $specialty->description ?? 'Sin datos' }}</p>
                    <p><b>Encargados:</b> {{ 'datos' }}</p>
                    <p><b>Estado:</b> {{ $specialty->status ? 'Activo' : 'Inactivo' }}</p>
                    <p><b>Fecha de creación:</b> {{ $specialty->created_at }}</p>
                    <p><b>Fecha de última actualizacion:</b> {{ $specialty->updated_at }}</p>
                </div>
            </div>
            <div class="opciones full-center">
                <a href="{{ route('specialty.edit', $specialty->id) }}" class="btn-edit"><i
                        class="bi bi-pencil-fill">Editar</i></a>
                <form action="{{ route('specialty.destroy', $specialty->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill">Eliminar</i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
