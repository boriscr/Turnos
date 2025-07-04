<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('mensaje_bienvenida'); //default('Bienvenido a nuestra aplicación de turnos. Aquí podrás reservar tus turnos de manera fácil y rápida.');
            $table->text('pie_pagina'); //default('© 2025 Turnos.com. Todos los derechos reservados.');
            $table->string('nombre_institucion');
            $table->integer('faltas');
            $table->integer('limites');
            $table->integer('cancelacion_turnos');
            $table->integer('preview_window_amount');//valor 1,24,30,etc
            $table->string('preview_window_unit'); // 'hora', 'dia' o 'mes'
            $table->string('logo')->nullable(); // Path to the logo image
            $table->string('hora_verificacion_asistencias'); // Horas para verificar asistencias automáticamente
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
