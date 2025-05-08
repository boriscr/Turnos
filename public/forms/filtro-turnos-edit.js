// Variables globales para la edición
let todosLosTurnos = [];
let equipoInicial = document.getElementById('equipo_id').value;
let especialidadInicial = document.getElementById('especialidad_id').value;

// Función para cargar equipos con selección inicial
async function cargarEquipos(especialidadId, equipoSeleccionado = null) {
    const selectEquipo = document.getElementById('equipo_id');
    
    if (!especialidadId) {
        selectEquipo.innerHTML = '<option value="">Seleccione un equipo</option>';
        return;
    }

    selectEquipo.disabled = true;
    selectEquipo.innerHTML = '<option value="">Cargando equipos...</option>';

    try {
        const response = await fetch(`/getEquiposPorEspecialidad/${especialidadId}`);
        const data = await response.json();
        
        selectEquipo.innerHTML = '<option value="">Seleccione un equipo</option>';
        data.equipos.forEach(equipo => {
            const option = new Option(equipo.nombre, equipo.id);
            if (equipoSeleccionado && equipo.id == equipoSeleccionado) {
                option.selected = true;
            }
            selectEquipo.add(option);
        });
        
        selectEquipo.disabled = false;
        
        // Si hay equipo seleccionado, cargar sus turnos
        if (equipoSeleccionado) {
            await cargarTurnos(equipoSeleccionado);
        }
    } catch (error) {
        console.error("Error al cargar equipos:", error);
        selectEquipo.innerHTML = '<option value="">Error al cargar</option>';
    }
}

// Función para cargar turnos con selección inicial
async function cargarTurnos(equipoId) {
    const selectFecha = document.getElementById('fecha_turno');
    const selectHora = document.getElementById('hora_turno');

    selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';
    selectHora.innerHTML = '<option value="">Seleccione una hora</option>';

    if (!equipoId) return;

    selectFecha.disabled = true;
    selectFecha.innerHTML = '<option value="">Cargando fechas...</option>';

    try {
        const response = await fetch(`/getTurnosPorEquipo/${equipoId}`);
        const data = await response.json();
        
        todosLosTurnos = data.turnos;

        if (todosLosTurnos.length === 0) {
            selectFecha.innerHTML = '<option value="">Sin fechas disponibles</option>';
            return;
        }

        // Mostrar fechas únicas
        const fechasUnicas = [...new Set(todosLosTurnos.map(t => t.fecha))];
        selectFecha.innerHTML = '<option value="">Seleccione una fecha</option>';
        
        fechasUnicas.forEach(fecha => {
            const fechaObj = new Date(fecha);
            const option = new Option(
                fechaObj.toLocaleDateString('es-ES', {
                    weekday: 'long',
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }),
                fecha
            );
            selectFecha.add(option);
        });
        
        selectFecha.disabled = false;
    } catch (error) {
        console.error("Error al cargar turnos:", error);
        selectFecha.innerHTML = '<option value="">Error al cargar</option>';
    }
}

// Inicialización al cargar la página
document.addEventListener('DOMContentLoaded', async function() {
    // Cargar equipos si hay especialidad seleccionada
    if (especialidadInicial) {
        await cargarEquipos(especialidadInicial, equipoInicial);
    }

    // Event listeners
    document.getElementById('especialidad_id').addEventListener('change', function() {
        cargarEquipos(this.value);
    });

    document.getElementById('equipo_id').addEventListener('change', function() {
        cargarTurnos(this.value);
    });

    document.getElementById('fecha_turno').addEventListener('change', function() {
        const turnosDisponibles = todosLosTurnos.filter(t => t.fecha === this.value);
        const selectHora = document.getElementById('hora_turno');
        
        selectHora.innerHTML = '<option value="">Seleccione una hora</option>';
        turnosDisponibles.forEach(turno => {
            selectHora.add(new Option(turno.hora, turno.id));
        });
    });
});