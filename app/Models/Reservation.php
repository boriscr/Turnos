<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'available_appointment_id', 
        'specialty_id',
        'user_id',
        'type',
        'third_party_name',
        'third_party_surname',
        'third_party_idNumber',
        'third_party_email',
        'status',
    ];

    public function availableAppointment()
    {
        return $this->belongsTo(AvailableAppointment::class);
    }
    public function appointmentHistories()
    {
        return $this->hasOne(AvailableAppointment::class);
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
