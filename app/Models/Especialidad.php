<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    // Definimos constante para nombre de tabla
    public const TABLE = 'especialidades';

    protected $table = self::TABLE;
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'especialidad_id');
    }
}
