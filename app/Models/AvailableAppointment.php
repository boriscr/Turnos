<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'appointment_id',
        'specialty_id',
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
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
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