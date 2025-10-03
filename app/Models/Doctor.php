<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TracksUserActions;

class Doctor extends Model
{
    use TracksUserActions;
    // Definimos constante para name de tabla
    public const TABLE = 'doctors';
    protected $table = 'doctors';
    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'idNumber',
        'email',
        'phone',
        'specialty_id',
        'licenseNumber',
        'status',
        'role',
    ];

    // Relación inversa (pertenece a un User)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function availableAppointments()
    {
        return $this->hasMany(AvailableAppointment::class);
    }
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    // Usuario que creó el doctor
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Usuario que actualizó el doctor
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
