<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipos';
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'email',
        'telefono',
        'especialidad',
        'matricula',
        'rol',
    ];
    public function turnosDisponibles()
    {
        return $this->hasMany(TurnoDisponible::class);
    }
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'id');
    }
    
    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }
}
