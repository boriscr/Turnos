<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nombre',
        'mensaje_bienvenida',
        'pie_pagina',
        'nombre_institucion',
        'cancelacion_turnos',
        'preview_window_amount',
        'preview_window_unit',
        'faltas',
        'limites',
        'hora_verificacion_asistencias',
    ];

    public $timestamps = false;

    // Define any relationships if necessary
}
