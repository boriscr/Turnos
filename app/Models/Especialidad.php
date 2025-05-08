<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidads';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'id');
    }
        
}
