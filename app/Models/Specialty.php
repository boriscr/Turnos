<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $table = 'specialties';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'specialty_id');
    }
    public function reservation()
    {
        return $this->hasMany(Reservation::class, 'specialty_id');
    }
}
