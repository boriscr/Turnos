<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'direccion',
        'specialty_id',
        'medico_id',
        'turno',
        'hora_inicio',
        'hora_fin',
        'fechas_disponibles',
        'cantidad_turnos',
        'user_id',
        'horarios_disponibles',
        'status'
    ];
    
    protected $casts = [
        'fechas_disponibles' => 'array',
        'horarios_disponibles' => 'array',
        'status' => 'boolean',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    public function specialty()
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function disponibilidades()
    {
        return $this->hasMany(TurnoDisponible::class);
    }
    // metodo obtener id del user que creo el turno
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
