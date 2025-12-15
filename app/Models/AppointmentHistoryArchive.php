<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentHistoryArchive extends Model
{
    protected $table = 'appointment_histories_archive';

    protected $fillable = [
        'appointment_id',
        'appointment_name',
        'reservation_id',
        'user_id',
        'doctor_name',
        'specialty',
        'type',
        'appointment_date',
        'appointment_time',
        'status',
        'cancelled_by',
        'cancellation_reason',
        'cancelled_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'cancelled_at' => 'datetime',
        'status' => 'string',
    ];

    public function getStatusMessageAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'assisted' => 'Asistido',
            'not_attendance' => 'No asistido',
            'cancelled_by_user' => 'Cancelado por usuario',
            'cancelled_by_admin' => 'Cancelado por administrador',
            'deleted_by_admin' => 'Eliminado por administrador',
            default => 'Desconocido',
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
}
