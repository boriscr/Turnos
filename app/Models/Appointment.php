<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TracksUserActions;

class Appointment extends Model
{
    use HasFactory;
    use TracksUserActions;
    
    protected $fillable = [
        'name',
        'address',
        'specialty_id',
        'doctor_id',
        'shift',
        'number_of_reservations',
        'start_time',
        'end_time',
        'available_dates',
        'available_time_slots',
        'status'
    ];

    protected $casts = [
        'available_dates' => 'array',
        'available_time_slots' => 'array',
        'status' => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }


    public function disponibilidades()
    {
        return $this->hasMany(AvailableAppointment::class);
    }
    // metodo obtener id del user que creo el appointment
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //metodo para relacionar con appointmentHistory
    public function appointmentHistories()
    {
        return $this->hasMany(AppointmentHistory::class);
    }
    // Usuario que creó el appointment
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Usuario que actualizó el appointment
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
