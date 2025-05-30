<x-body.body>
    <div class="main">
        <div class="container-form">
            <h3 class="title-form">Agregar nuevo equipo</h3>
            <x-form.equipo :ruta="route('equipo.store')" 
            :crear="true" 
            :especialidades="$especialidades" 
            :nombre="old('nombre')" 
            :apellido="old('apellido')"
            :dni="old('dni')" 
            :matricula="old('matricula')" 
            :email="old('email')" 
            :telefono="old('telefono')" 
            :rol="old('rol')"
            :estado="old('estado')" 
            :especialidad="old('especialidad')" />
            
            <div class="form-especialidad" id="especialidad-form">
                <form action="{{ route('especialidad.store') }}" method="post">
                    @csrf
                    <h3 class="title-form">Crear una nueva especialidad</h3>
                    <div class="item">
                        <label for="nombre"><span aria-hidden="true" style="color: red;">*</span>Nombre de la
                            especialidad</label>
                        <input type="text" name="nombre" id="nombre" required value="{{ old('nombre') }}">
                    </div>
                    <div class="item">
                        <label for="descripcion">Descripcion</label>
                        <textarea name="descripcion" id="descripcion" cols="30" rows="5">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="item switch-item">
                        <label for="descripcion-esp"><b>Estado</b></label>
                        <div class="switch">
                            <input type="checkbox" id="toggle-esp" name="estado" value="1" checked>
                            <label for="toggle-esp"></label>
                        </div>
                        <p id="estadoTxt-esp" class="estadoTxt">Activo</p>
                    </div>
                    <button type="submit" class="submit-btn">Registrar</button>
                    <button type="button" id="close-btn" style="background: red; color: #fff;">Cancelar</button>
                </form>

                @if ($errors->any())
                    <div style="color: red;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <script src="../forms/switch.js"></script>
                <script src="../forms/especialidad.js"></script>

            </div>
        </div>
</x-body.body>
