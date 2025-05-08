<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'turno_disponible_id',
        'user_id',
    ];

    public function turnoDisponible()
    {
        return $this->belongsTo(TurnoDisponible::class);
    }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
