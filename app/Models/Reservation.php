<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'available_appointment_id', 
        'specialty_id',
        'user_id',
        //'asistencia',
    ];
    protected $casts = [
        'asistencia' => 'boolean',
    ];

    public function availableAppointment()
    {
        return $this->belongsTo(AvailableAppointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function specialties()
    {
        return $this->belongsTo(Specialty::class);
    }
}
