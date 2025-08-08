<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'specialty_id',
        'doctor_id',
        'shift',
        'number_of_slots',
        'start_time',
        'end_time',
        'available_dates',
        'available_time_slots',
        'status',
        'createBy',
        'updateBy'
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
    // Usuario que creó el appointment
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'createBy');
    }

    // Usuario que actualizó el appointment
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updateBy');
    }
}
