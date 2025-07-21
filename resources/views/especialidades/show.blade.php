<x-app-layout>
    @if (isset($especialidad))
        <div class="content-wrapper">
            <h3 class="title-form">Detalles</h3>
            <div class="section-container centrado-total">
                <div class="card">
                    <p><b>Nombre: </b>{{ $especialidad->nombre }}</p>
                    <p><b>Descripcion:</b> {{ $especialidad->descripcion ?? 'Sin datos' }}</p>
                    <p><b>Encargados:</b> {{ 'datos' }}</p>
                    <p><b>Estado:</b> {{ $especialidad->estado ? 'Activo' : 'Inactivo' }}</p>
                    <p><b>Fecha de creación:</b> {{ $especialidad->created_at }}</p>
                    <p><b>Fecha de última actualizacion:</b> {{ $especialidad->updated_at }}</p>
                </div>
            </div>
            <div class="opciones centrado-total">
                <a href="{{ route('especialidad.edit', $especialidad->id) }}" class="btn-edit"><i
                        class="bi bi-pencil-fill">Editar</i></a>
                <form action="{{ route('especialidad.destroy', $especialidad->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-delete delete-btn"><i class="bi bi-trash-fill">Eliminar</i>
                    </button>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>
