<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
 protected $table = 'specialties';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function medicos()
    {
        return $this->hasMany(Medico::class, 'specialty_id');
    }
}
