<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'turno_id',
        'date',
        'time',
        'available_spots',
        'reserved_spots',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'disponible' => 'boolean'
    ];

    public function turno()
    {
        return $this->belongsTo(Turno::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getCuposRestantesAttribute()
    {
        return $this->available_spots - $this->reserved_spots;
    }
}