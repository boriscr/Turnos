<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoDisponible extends Model
{
    use HasFactory;

    protected $fillable = [
        'medico_id',
        'turno_id',
        'fecha',
        'hora',
        'cupos_disponibles',
        'cupos_reservados',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i',
        'disponible' => 'boolean'
    ];

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function getCuposRestantesAttribute()
    {
        return $this->cupos_disponibles - $this->cupos_reservados;
    }
}