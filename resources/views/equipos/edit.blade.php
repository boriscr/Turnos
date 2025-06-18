<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Editar datos del equipo</h3>
            <x-form.equipo :ruta="route('equipo.update', $equipo->id)" :nuevoEquipo=isset($nuevoEquipo) ? $nuevoEquipo : false :especialidades="$especialidades"
                :especialidad="$equipo->$especialidad_id ?? null" :nombre="$equipo->nombre" :apellido="$equipo->apellido" :dni="$equipo->dni" :matricula="$equipo->matricula"
                :email="$equipo->email" :telefono="$equipo->telefono" :role="$equipo->role" :estado="$equipo->estado" :especialidad="$equipo->especialidad_id" />
            
                {{--Formulario para crear una nueva especialidad--}}
                <div class="form-especialidad" id="especialidad-form">
                    <x-form.especialidad 
                    :ruta="route('especialidad.store',)" 
                    :crear="true" :nombre="old('nombre')" 
                    :descripcion="old('descripcion')" />
                <script src="../../forms/especialidad.js"></script>
            </div>
        </div>
    </div>
</x-body.body>
