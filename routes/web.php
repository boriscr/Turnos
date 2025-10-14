<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AvailableAppointmentsController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\MyAppointmentsController;
use App\Http\Controllers\AppointmentHistoryController;

Route::view('/', 'home')->name('home');

Route::middleware(['auth', 'role:user|doctor|admin'])->group(function () {
    //Solicitud de Appointments
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::get('/getDoctorsBySpecialty/{specialty_id}', [ReservationController::class, 'getDoctorsBySpecialty']);
    Route::get('/getAvailableReservationByName/{doctor_id}', [ReservationController::class, 'getAvailableReservationByName']);
    Route::get('/getAvailableReservationByDoctor/{appointment_name_id}', [ReservationController::class, 'getAvailableReservationByDoctor']);
    Route::post('/reservations/store', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    ///My Appointments
    Route::get('/myAppointments', [MyAppointmentsController::class, 'index'])->name('myAppointments.index');
    Route::get('/myAppointment/show/{id}', [MyAppointmentsController::class, 'show'])->name('myAppointments.show');
    //Cancelar reserva (turno) - solo para usuarios / pacientes
    Route::delete('/myAppointment/{id}/cancel', [MyAppointmentsController::class, 'destroy'])
        ->name('myAppointments.destroy');
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit/{id}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Route::get('/profile/show/{id}', [ProfileController::class, 'show'])->name('profile.show');
    //Route::get('/profile/historial', [ProfileController::class, 'historial'])->name('profile.historial');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    //Edit users
    Route::patch('/registered/edit', [RegisteredUserController::class, 'edit'])->name('registered.edit');
    Route::patch('/registered/update', [RegisteredUserController::class, 'update'])->name('registered.update');
    //Historial de Appointments
    Route::get('/appointmentHistory', [AppointmentHistoryController::class, 'index'])->name('appointmentHistory.index');
    Route::get('/appointmentHistory/search', [AppointmentHistoryController::class, 'search'])->name('appointmentHistory.search');

    Route::get('/appointmentHistory/show/{id}', [AppointmentHistoryController::class, 'show'])->name('appointmentHistory.show');
    Route::delete('/appointmentHistory/{id}', [AppointmentHistoryController::class, 'destroy'])->name('appointmentHistory.destroy');

    //Appointments disponibles "Reservations"
    //Reservations
    Route::get('/reservations/index', [ReservationController::class, 'index'])->name('reservations.index');
    //Route::post('/reservations/store', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/show/{id}', [ReservationController::class, 'show'])->name('reservations.show');
    //Route::get('/reservations/edit/{id}', [ReservationController::class, 'edit'])->name('reservations.edit');
    //Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'actualizarstatus'])
        ->name('reservations.status');


    //Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/users/show/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    //Control del Doctors
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('doctor/search', [DoctorController::class, 'search'])->name('doctors.search');
    Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
    Route::post('/doctors/store', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('/doctors/show/{id}', [DoctorController::class, 'show'])->name('doctors.show');
    Route::get('/doctors/edit/{id}', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('/doctors/destroy/{id}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
    //Control de specialties
    Route::get('/specialty', [SpecialtyController::class, 'index'])->name('specialty.index');
    Route::get('specialty/search', [SpecialtyController::class, 'search'])->name('specialty.search');
    Route::get('/specialty/create', [SpecialtyController::class, 'create'])->name('specialty.create');
    Route::post('/specialty', [SpecialtyController::class, 'store'])->name('specialty.store');
    Route::get('/specialty/show/{id}', [SpecialtyController::class, 'show'])->name('specialty.show');
    Route::get('/specialty/edit/{id}', [SpecialtyController::class, 'edit'])->name('specialty.edit');
    Route::put('/specialty/{id}', [SpecialtyController::class, 'update'])->name('specialty.update');
    Route::delete('/specialty/{id}', [SpecialtyController::class, 'destroy'])->name('specialty.destroy');
    Route::get('/specialty/doctors/{id}', [SpecialtyController::class, 'listaEquipo'])->name('lista.doctors');

    //Appointments
    Route::get('/getBySpecialty/{id}', [AppointmentController::class, 'getBySpecialty']); //Selects decargar especialidad seleccionada (fetch API) segun los doctores
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointment/search', [AppointmentController::class, 'search'])->name('appointment.search');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/show/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/appointments/edit/{id}', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::patch('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    //Appointments disponibles creados
    Route::get('/availableAppointments/{id?}', [AvailableAppointmentsController::class, 'index'])->name('availableAppointments.index');
    Route::get('/availableAppointments/show/{id?}', [AvailableAppointmentsController::class, 'show'])->name('availableAppointments.show');

    //Setting
    Route::get('/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__ . '/auth.php';



// Rutas para cargar datos directamente desde las tablas
Route::get('/get-states/{countryId}', function ($countryId) {
    $states = DB::table('states')
        ->where('country_id', $countryId)
        ->orderBy('name')
        ->get(['id', 'name']);

    return response()->json($states);
});

Route::get('/get-cities/{stateId}', function ($stateId) {
    $cities = DB::table('cities')
        ->where('state_id', $stateId)
        ->orderBy('name')
        ->get(['id', 'name']);

    return response()->json($cities);
});
