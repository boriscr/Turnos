<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\TurnoDisponibleController;

Route::view('/', 'home')->name('home');

Route::middleware('auth')->group(function () {
    //Turnos disponibles "Reservas"
    Route::get('/reserva', [TurnoDisponibleController::class, 'create'])->name('reserva.create');
    Route::get('/getEquiposPorEspecialidad/{especialidad_id}', [TurnoDisponibleController::class, 'getEquiposPorEspecialidad']);
    Route::get('/getTurnosPorEquipo/{equipo_id}', [TurnoDisponibleController::class, 'getTurnosPorEquipo']);
    Route::post('/reservarTurno', [TurnoDisponibleController::class, 'reservarTurno'])->name('reservarTurno');

    //Users
    Route::get('/usuario', [UsuarioController::class, 'index'])->name('usuario.index');
    Route::get('/usuario/create', [UsuarioController::class, 'create'])->name('usuario.create');
    Route::post('/usuario/store', [UsuarioController::class, 'store'])->name('usuario.store');
    Route::get('/usuario/{id}/show', [UsuarioController::class, 'show'])->name('usuario.show');
    Route::get('/usuario/{id}/edit', [UsuarioController::class, 'edit'])->name('usuario.edit');
    Route::put('/usuario/{id}', [UsuarioController::class, 'update'])->name('usuario.update');
    Route::delete('/usuario/{id}', [UsuarioController::class, 'destroy'])->name('usuario.destroy');

    //Control del peronal
    Route::get('/equipo', [EquipoController::class, 'index'])->name('equipo.index');
    Route::post('/equipo/store', [EquipoController::class, 'store'])->name('equipo.store');
    Route::get('/equipo/create', [EquipoController::class, 'create'])->name('equipo.create');
    Route::get('/equipo/{id}/show', [EquipoController::class, 'show'])->name('equipo.show');
    Route::get('/equipo/{id}/edit', [EquipoController::class, 'edit'])->name('equipo.edit');
    Route::put('/equipo/{id}', [EquipoController::class, 'update'])->name('equipo.update');
    Route::delete('/equipo/{id}', [EquipoController::class, 'destroy'])->name('equipo.destroy');
    //Control de especialidades
    Route::get('/equipos-por-especialidad/{id}', [EquipoController::class, 'getPorEspecialidad']);
    Route::get('/especialidad', [EspecialidadController::class, 'index'])->name('especialidad.index');
    Route::get('/especialidad/create', [EspecialidadController::class, 'create'])->name('especialidad.create');
    Route::post('/especialidad', [EspecialidadController::class, 'store'])->name('especialidad.store');
    Route::get('/especialidad/{id}/show', [EspecialidadController::class, 'show'])->name('especialidad.show');
    Route::get('/especialidad/{id}/edit', [EspecialidadController::class, 'edit'])->name('especialidad.edit');
    Route::put('/especialidad/{id}', [EspecialidadController::class, 'update'])->name('especialidad.update');
    Route::delete('/especialidad/{id}', [EspecialidadController::class, 'destroy'])->name('especialidad.destroy');

    //Turnos disponibles
    Route::get('/turnos', [TurnoController::class, 'index'])->name('turnos.index');
    Route::get('/turnos/create', [TurnoController::class, 'create'])->name('turnos.create');
    Route::post('/turnos', [TurnoController::class, 'store'])->name('turnos.store');
    Route::get('/turnos/{id}/show', [TurnoController::class, 'show'])->name('turnos.show');
    Route::get('/turnos/{id}/edit', [TurnoController::class, 'edit'])->name('turnos.edit');
    Route::put('/turnos/{id}', [TurnoController::class, 'update'])->name('turnos.update');
    Route::delete('/turnos/{id}', [TurnoController::class, 'destroy'])->name('turnos.destroy');
});




/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
