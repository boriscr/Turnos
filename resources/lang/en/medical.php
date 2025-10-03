<?php
return [
    'update' => 'Update',
    'register' => 'Register',
    'cancel' => 'Cancel',
    'select_default' => 'Select',
    'id' => 'Id',
    'description_txt' => 'Description',
    'license_number' => 'License Number',
    'role' => 'Role',
    'admin' => 'Admin',
    'doctor' => 'Doctor',
    'dr' => 'Dr.',
    'user' => 'User',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'actions' => 'Actions',
    'patient' => 'Patient',
    'attendance' => 'Attendance',
    'created_by' => 'Created by',
    'cancellation_date' => 'Cancellation date',
    'deletion_date' => 'Deletion date',
    'creation_date' => 'Creation date',
    'updated_by' => 'Updated by',
    'update_date' => 'Update date',
    'existing' => 'Existing',
    'no_data' => 'No data',
    'no_profile' => 'No user profile',
    'no_actions' => 'No available actions',
    'profile' => 'Profile',
    'none' => 'None',
    'unassisted_reservations' => 'Unassisted reservations',
    'no_appointments' => 'No booked appointments.',

    'status' => [
        'title' => 'Status',
        'assisted' => 'Assisted',
        'not_attendance' => 'Not attendance',
        'pending' => 'Pending',
        'canceled' => 'Canceled',
        'cancelled_by_user' => 'Cancelled by user',
        'cancelled_by_admin' => 'Cancelled by admin',
        'deleted_by_admin' => 'Deleted by admin',
        'unknown' => 'Unknown',
        'scheduled' => 'Scheduled',
    ],
    'labels' => [
        'start_date' => 'Start date',
        'end_date' => 'End date',
    ],

    'titles' => [
        'section_title_add' => 'Add new',
        'section_title_edit' => 'Edit data',
        'book_a_new_appointment' => 'Book a new appointment',
        'user_index_title' => 'List of registered users',
        'doctor_index_title' => 'List of created doctors',
        'doctor_show_title' => 'List of related doctors',
        'appointment_index_title' => 'List of created appointment',
        'reservation_list' => 'Reservation list',
        'specialty_list' => 'Specialty list',
        'details' => 'Details',
        'management' => 'Management',
        'creation' => 'Creation',
        'personal_data' => 'Personal data',
        'patient_data' => 'Patient data',
        'doctor_details' => 'Doctor information',
        'contact_details' => 'Contact details',
        'my_data' => 'My data',
        'reservations' => 'Reservations',
        'reserved_appointment_details' => 'Reserved appointment details',
        'historical' => 'Historical'
    ],

    'setting' => [
        'title_1' => 'Custom Content',
        'title_2' => 'Booking Settings',
        'title_3' => 'Design Customization',

        'subtitle_1' => 'General Colors',
        'subtitle_2' => 'Dark Theme',
        'subtitle_3' => 'Light Theme',

        'name' => 'Application Name',
        'name_context' => 'Will be displayed at the top of the application.',

        'welcome_message' => 'Welcome Message',
        'welcome_message_context' => 'Will be displayed on the application\'s home screen.',

        'footer' => 'Footer',
        'footer_context' => 'Will be displayed at the bottom of the application.',

        'institution_name' => 'Institution Name',

        'patient_message' => 'Patient Message',
        'patient_message_context' => 'Will be displayed to patients before booking an appointment.',

        // User
        'maximum_faults' => 'Absence Limit',
        'maximum_faults_context' => 'Maximum number of no-shows allowed before a patient gets blocked.',

        'daily_limit' => 'Active Bookings Limit',
        'daily_limit_context' => 'Maximum number of active bookings a patient can have simultaneously.',

        'cancellation_hours' => 'Booking Cancellation',
        'cancellation_hours_context' => 'Minimum advance time required to cancel a booking (in hours).',

        'advance_reservation' => 'Booking Window',
        'advance_reservation_context' => 'Maximum time window for viewing and booking available appointments (example: 24 hours).',

        'unit_advance' => 'Time Unit',
        'unit_advance_context' => 'Select the time unit for advance booking (hours, days, or months). Example: if you set "24 hours", patients will see available appointments up to 24 hours in advance.',

        'time' => 'Hour(s)',
        'day' => 'Day(s)',
        'month' => 'Month(s)',

        'verification_interval' => 'Attendance Check Frequency',
        'verification_interval_context' => 'Time interval for automatic attendance verification (will be marked as *Attended* or *No-show*).',

        // Color Customization
        'general_design_color' => 'Overall design color',
        'title_text_color' => 'Title Text Color',
        'subtitle_text_color' => 'Subtitle Text Color',
        'primary_color_btn' => 'Primary Button Color',
        'secondary_color_btn' => 'Secondary Button Color',
        'btn_text_color' => 'Button Text Color',
        'footer_background' => 'Footer background color',

        // Dark/Light Modes
        'application_background' => 'Application Background Color',
        'text_color' => 'Main Text Color',
        'text_color_small' => 'Secondary Text Color',
        'background_navbar' => 'Navigation Bar Background',
        'background_login_and_register' => 'Login/Register Background',
        'text_color_form_elements' => 'Form Elements Text Color',
    ]
];
