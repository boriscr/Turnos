<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\TurnoDisponibleController;
use App\Http\Controllers\ReservaController;

Route::view('/', 'home')->name('home');

Route::middleware('auth')->group(function () {
    //Turnos disponibles "Reservas"
    Route::get('/reserva/create', [TurnoDisponibleController::class, 'create'])->name('reserva.create');
    Route::get('/getMedicosPorEspecialidad/{especialidad_id}', [TurnoDisponibleController::class, 'getMedicosPorEspecialidad']);
    Route::get('/getTurnosPorEquipo/{medico_id}', [TurnoDisponibleController::class, 'getTurnosPorEquipo']);
    Route::post('/reservarTurno', [TurnoDisponibleController::class, 'reservarTurno'])->name('reservarTurno');
    Route::delete('/disponible/{id}', [TurnoDisponibleController::class, 'destroy'])->name('disponible.destroy');

    //Reservas
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/create', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas/store', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/{id}/show', [ReservaController::class, 'show'])->name('reservas.show');
    Route::get('/reservas/{id}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    Route::patch('/reservas/{reserva}/asistencia', [ReservaController::class, 'actualizarAsistencia'])
        ->name('reservas.asistencia');
    //Users
    Route::get('/usuario', [UsuarioController::class, 'index'])->name('usuario.index');
    //Route::get('/usuario/create', [UsuarioController::class, 'create'])->name('usuario.create');
    //Route::post('/usuario/store', [UsuarioController::class, 'store'])->name('usuario.store');
    Route::get('/usuario/{id}/show', [UsuarioController::class, 'show'])->name('usuario.show');
    Route::get('/usuario/{id}/edit', [UsuarioController::class, 'edit'])->name('usuario.edit');
    Route::put('/usuario/{id}', [UsuarioController::class, 'update'])->name('usuario.update');
    Route::delete('/usuario/{id}', [UsuarioController::class, 'destroy'])->name('usuario.destroy');

    //Control del Medicos
    Route::get('/medico', [MedicoController::class, 'index'])->name('medico.index');
    Route::get('/medico/create', [MedicoController::class, 'create'])->name('medico.create');
    Route::post('/medico/store', [MedicoController::class, 'store'])->name('medico.store');
    Route::get('/medico/{id}/show', [MedicoController::class, 'show'])->name('medico.show');
    Route::get('/medico/{id}/edit', [MedicoController::class, 'edit'])->name('medico.edit');
    Route::put('/medico/{id}', [MedicoController::class, 'update'])->name('medico.update');
    Route::delete('/medico/{id}/destroy', [MedicoController::class, 'destroy'])->name('medico.destroy');
    //Control de especialidades
    Route::get('/especialidad', [EspecialidadController::class, 'index'])->name('especialidad.index');
    Route::get('/especialidad/create', [EspecialidadController::class, 'create'])->name('especialidad.create');
    Route::post('/especialidad', [EspecialidadController::class, 'store'])->name('especialidad.store');
    Route::get('/especialidad/{id}/show', [EspecialidadController::class, 'show'])->name('especialidad.show');
    Route::get('/especialidad/{id}/edit', [EspecialidadController::class, 'edit'])->name('especialidad.edit');
    Route::put('/especialidad/{id}', [EspecialidadController::class, 'update'])->name('especialidad.update');
    Route::delete('/especialidad/{id}', [EspecialidadController::class, 'destroy'])->name('especialidad.destroy');
    Route::get('/especialidad/{id}/medicos', [EspecialidadController::class, 'listaEquipo'])->name('lista.medicos');

    //Turnos
    Route::get('/turnos', [TurnoController::class, 'index'])->name('turnos.index');
    Route::get('/turnos/create', [TurnoController::class, 'create'])->name('turnos.create');
    Route::post('/turnos', [TurnoController::class, 'store'])->name('turnos.store');
    Route::get('/turnos/{id}/show', [TurnoController::class, 'show'])->name('turnos.show');
    Route::get('/turnos/{id}/edit', [TurnoController::class, 'edit'])->name('turnos.edit');
    Route::patch('/turnos/{id}', [TurnoController::class, 'update'])->name('turnos.update');
    Route::delete('/turnos/{id}', [TurnoController::class, 'destroy'])->name('turnos.destroy');
    Route::get('/medicos-por-especialidad/{id}', [TurnoController::class, 'getPorEspecialidad']);//En controlador Medicos

    //disponibles
    Route::get('/disponible/{equipoId?}', [TurnoController::class, 'search'])->name('disponible.index');


    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/show/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/updateAdm', [ProfileController::class, 'updateAdmin'])->name('profile.updateAdmin');
    Route::get('/profile/historial', [ProfileController::class, 'historial'])->name('profile.historial');
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__ . '/auth.php';
