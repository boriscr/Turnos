<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable
{
    
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',
        'dni',
        'birthdate',
        'genero',
        'country',
        'province',
        'city',
        'address',
        'phone',
        'email',
        'estado',
        'password',
        'faults',
    ];

    public function turno()
    {
        return $this->hasMany(Turno::class);
    } 
// Relación 1:1 con Medico (un usuario PUEDE ser un profesional)
public function medico()
{
    return $this->hasOne(Medico::class, 'user_id'); 
    // 'user_id' es la FK en la tabla medicos
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
