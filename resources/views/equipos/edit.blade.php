<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Editar datos del equipo</h3>
            <x-form.equipo
                :ruta="route('equipo.update', $equipo->id)"
                :especialidades="$especialidades"
                :especialidad="$equipo->$especialidad_id"
                :nombre="$equipo->nombre"
                :apellido="$equipo->apellido"
                :dni="$equipo->dni"
                :matricula="$equipo->matricula"
                :email="$equipo->email"
                :telefono="$equipo->telefono"
                :rol="$equipo->rol"
                :estado="$equipo->estado"
                :especialidad="$equipo->especialidad_id"
            />

        </div>
    </div>
</x-body.body>