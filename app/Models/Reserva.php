<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'turno_disponible_id',
        'user_id',
        'asistencia',
    ];
    protected $casts = [
        'asistencia' => 'boolean',
    ];

    public function turnoDisponible()
    {
        return $this->belongsTo(AvailableAppointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
