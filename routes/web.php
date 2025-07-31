<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\TurnoDisponibleController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::view('/', 'home')->name('home');

Route::middleware(['auth', 'role:user|doctor|admin'])->group(function () {
    //Solicitud de Turnos
    Route::get('/disponibles/create', [TurnoDisponibleController::class, 'create'])->name('disponible.create');
    Route::get('/getDoctorsBySpecialty/{specialty_id}', [TurnoDisponibleController::class, 'getDoctorsBySpecialty']);
    Route::get('/getTurnosPorNombre/{doctor_id}', [TurnoDisponibleController::class, 'getTurnosPorNombre']);

    Route::get('/getTurnosPorEquipo/{turno_nombre_id}', [TurnoDisponibleController::class, 'getTurnosPorEquipo']);
    Route::post('/reservarTurno', [TurnoDisponibleController::class, 'reservarTurno'])->name('reservarTurno');
    Route::delete('/disponibles/{id}', [TurnoDisponibleController::class, 'destroy'])->name('disponible.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/show/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/historial', [ProfileController::class, 'historial'])->name('profile.historial');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    //Edit users
    Route::patch('/registered/edit', [RegisteredUserController::class, 'edit'])->name('registered.edit');
    Route::patch('/registered/update', [RegisteredUserController::class, 'update'])->name('registered.update');
    //Turnos disponibles "Reservas"

    //Reservas
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/create', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas/store', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/show/{id}', [ReservaController::class, 'show'])->name('reservas.show');
    Route::get('/reservas/edit/{id}', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::patch('/reservas/{reserva}/asistencia', [ReservaController::class, 'actualizarAsistencia'])
        ->name('reservas.asistencia');
    //Users
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::get('/users/show/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/users/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    //Control del Doctors
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctor.index');
    Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctor.create');
    Route::post('/doctors/store', [DoctorController::class, 'store'])->name('doctor.store');
    Route::get('/doctors/show/{id}', [DoctorController::class, 'show'])->name('doctor.show');
    Route::get('/doctors/edit/{id}', [DoctorController::class, 'edit'])->name('doctor.edit');
    Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('doctor.update');
    Route::delete('/doctors/destroy/{id}', [DoctorController::class, 'destroy'])->name('doctor.destroy');
    //Control de specialties
    Route::get('/specialty', [SpecialtyController::class, 'index'])->name('specialty.index');
    Route::get('/specialty/create', [SpecialtyController::class, 'create'])->name('specialty.create');
    Route::post('/specialty', [SpecialtyController::class, 'store'])->name('specialty.store');
    Route::get('/specialty/show/{id}', [SpecialtyController::class, 'show'])->name('specialty.show');
    Route::get('/specialty/edit/{id}', [SpecialtyController::class, 'edit'])->name('specialty.edit');
    Route::put('/specialty/{id}', [SpecialtyController::class, 'update'])->name('specialty.update');
    Route::delete('/specialty/{id}', [SpecialtyController::class, 'destroy'])->name('specialty.destroy');
    Route::get('/specialty/doctors/{id}', [SpecialtyController::class, 'listaEquipo'])->name('lista.doctors');

    //Turnos
    Route::get('/turnos', [TurnoController::class, 'index'])->name('turnos.index');
    Route::get('/turnos/create', [TurnoController::class, 'create'])->name('turnos.create');
    Route::post('/turnos', [TurnoController::class, 'store'])->name('turnos.store');
    Route::get('/turnos/show/{id}', [TurnoController::class, 'show'])->name('turnos.show');
    Route::get('/turnos/edit/{id}', [TurnoController::class, 'edit'])->name('turnos.edit');
    Route::patch('/turnos/{id}', [TurnoController::class, 'update'])->name('turnos.update');
    Route::delete('/turnos/{id}', [TurnoController::class, 'destroy'])->name('turnos.destroy');
    Route::get('/doctors-por-specialty/{id}', [TurnoController::class, 'getPorEspecialidad']); //En controlador Doctors

    //disponibles
    Route::get('/disponible/{equipoId?}', [TurnoController::class, 'search'])->name('disponible.index');
    //Setting
    Route::get('/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
});





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__ . '/auth.php';
