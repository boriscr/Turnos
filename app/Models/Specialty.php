<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TracksUserActions;

class Specialty extends Model
{
    use TracksUserActions;

    protected $table = 'specialties';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];
    public function availableAppointment()
    {
        return $this->hasMany(AvailableAppointment::class);
    }
    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'specialty_id');
    }
    public function reservation()
    {
        return $this->hasMany(Reservation::class, 'specialty_id');
    }
}
