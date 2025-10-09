<?php
return [
    'available' => 'Disponibles',
    'reserved' => 'Reservados',
    'update' => 'Actualizar',
    'register' => 'Registrar',
    'cancel' => 'Cancelar',
    'select_default' => 'Seleccionar',
    'id' => 'Id',
    'description_txt' => 'Descripción',
    'license_number' => 'Matricula',
    'role' => 'Rol',
    'admin' => 'Admin',
    'doctor' => 'Doctor',
    'dr' => 'Dr.',
    'user' => 'Usuario',
    'active' => 'Activo',
    'inactive' => 'Inactivo',
    'actions' => 'Acciones',
    'patient' => 'Paciente',
    'attendance' => 'Asistencia',
    'created_by' => 'Creado por',
    'cancellation_date' => 'Fecha de cancelacion',
    'deletion_date' => 'Fecha de eliminacion',
    'creation_date' => 'Fecha de creación',
    'updated_by' => 'Actualizado por',
    'update_date' => 'Fecha de actualizacion',
    'existing' => 'Existente',
    'no_data' => 'Sin datos',
    'no_profile' => 'Sin perfil de usuario',
    'no_actions' => 'Sin acciones disponibles',
    'profile' => 'Perfil',
    'none' => 'Ninguno',
    'unassisted_reservations' => 'Reservas no asistidas',
    'no_appointments' => 'No hay turnos reservados.',
    'status' => [
        'title' => 'Estado',
        'assisted' => 'Asistió',
        'not_attendance' => 'No asistió',
        'pending' => 'Pendiente',
        'canceled' => 'Cancelado',
        'cancelled_by_user' => 'Cancelado por el usuario',
        'cancelled_by_admin' => 'Cancelado por el administrador',
        'deleted_by_admin' => 'Eliminado por el administrador',
        'unknown' => 'Desconocido',
        'scheduled' => 'Agendado',
    ],
    'labels' => [
        'start_date' => 'Fecha inicio',
        'end_date' => 'Fecha fin',
    ],

    'titles' => [
        'section_title_add' => 'Agregar nuevo',
        'section_title_edit' => 'Editar datos',
        'book_a_new_appointment' => 'Reservar un nuevo turno',
        'user_index_title' => 'Lista de usuarios registrados',
        'doctor_index_title' => 'Lista de doctores creados',
        'doctor_show_title' => 'Lista de doctores relacionados',
        'appointment_index_title' => 'Lista de turnos creados',
        'reservation_list' => 'Lista de reservas',
        'specialty_list' => 'Lista de especialidades',
        'details' => 'Detalles',
        'management' => 'Gestión',
        'creation' => 'Creación',
        'personal_data' => 'Datos personales',
        'patient_data' => 'Datos del paciente',
        'doctor_details' => 'Datos del doctor',
        'contact_details' => 'Datos de contacto',
        'my_data' => 'Mis datos',
        'reservations' => 'Reservas',
        'reserved_appointment_details' => 'Detalles del turno reservado',
        'historical' => 'Historial',
        'appointment_quotas_created'=>'Cupos de turnos creadas'
    ],
    'setting' => [
        'title_1' => 'Contenido personalizado',
        'title_2' => 'Configuración de reservas',
        'title_3' => 'Personalización del diseño',

        'subtitle_1' => 'Colores generales',
        'subtitle_2' => 'Tema oscuro',
        'subtitle_3' => 'Tema claro',

        'name' => 'Nombre de la aplicación',
        'name_context' => 'Se mostrará en la parte superior de la aplicación.',

        'welcome_message' => 'Mensaje de bienvenida',
        'welcome_message_context' => 'Se mostrará en la pantalla de inicio de la aplicación.',

        'footer' => 'Pie de página',
        'footer_context' => 'Se mostrará en la parte inferior de la aplicación.',

        'institution_name' => 'Nombre de la institución',

        'patient_message' => 'Mensaje para pacientes',
        'patient_message_context' => 'Se mostrará a los pacientes antes de reservar un turno.',

        // Usuario
        'maximum_faults' => 'Límite de faltas',
        'maximum_faults_context' => 'Número máximo de inasistencias permitidas antes de bloquear a un paciente.',

        'daily_limit' => 'Límite de reservas activas',
        'daily_limit_context' => 'Cantidad máxima de reservas activas que un paciente puede tener simultáneamente.',

        'cancellation_hours' => 'Cancelación de reservas',
        'cancellation_hours_context' => 'Tiempo mínimo de anticipación requerido para cancelar una reserva (en horas).',

        'advance_reservation' => 'Anticipación para reservar',
        'advance_reservation_context' => 'Período máximo de anticipación para visualizar y reservar turnos (ejemplo: 24 horas).',

        'unit_advance' => 'Unidad de tiempo',
        'unit_advance_context' => 'Seleccione la unidad de tiempo para la anticipación (horas, días o meses). Ejemplo: si configura "24 horas", los pacientes podrán ver los turnos disponibles hasta 24 horas antes.',

        'time' => 'Hora(s)',
        'day' => 'Día(s)',
        'month' => 'Mes(es)',

        'verification_interval' => 'Frecuencia de verificación de asistencias',
        'verification_interval_context' => 'Intervalo de tiempo para la verificación automática de asistencias (se marcará como *Asistió* o *No asistió*).',

        // Personalización de colores
        'general_design_color' => 'Color general de diseño',
        'title_text_color' => 'Color de los títulos',
        'subtitle_text_color' => 'Color de los subtítulos',
        'primary_color_btn' => 'Color principal para botones',
        'secondary_color_btn' => 'Color secundario para botones',
        'btn_text_color' => 'Color del texto en botones',
        'footer_background' => 'Color de fondo del pie de página',

        // Modos oscuro/claro
        'application_background' => 'Color de fondo de la aplicación',
        'text_color' => 'Color del texto principal',
        'text_color_small' => 'Color del texto secundario',
        'background_navbar' => 'Color de fondo de las barras de navegación',
        'background_login_and_register' => 'Color de fondo para inicio de sesión y registro',
        'text_color_form_elements' => 'Color del texto en elementos de formulario',
    ]
];
