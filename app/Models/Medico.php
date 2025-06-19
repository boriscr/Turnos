<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
        // Definimos constante para nombre de tabla
    public const TABLE = 'medicos';
    protected $table = 'medicos';
    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'dni',
        'email',
        'telefono',
        'especialidad_id',
        'matricula',
        'estado',
        'role',
    ];

    // Relación inversa (pertenece a un User)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function turnosDisponibles()
    {
        return $this->hasMany(TurnoDisponible::class);
    }
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
