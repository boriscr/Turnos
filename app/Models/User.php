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
        'idNumber',
        'birthdate',
        'gender',
        'country',
        'province',
        'city',
        'address',
        'phone',
        'email',
        'status',
        'password',
        'faults',
        'update_by'
    ];

    public function getTranslatedRoleAttribute()
    {
        $role = $this->getRoleNames()->first();

        return match ($role) {
            'user' => __('medical.user'),
            'doctor' => __('medical.doctor'),
            'admin' => __('medical.admin'),
            default => __('medical.status.unknown')
        };
    }
    
    public function appointment()
    {
        return $this->hasMany(Appointment::class);
    }
    // Relación 1:1 con Doctor (un user PUEDE ser un profesional)
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id');
        // 'user_id' es la FK en la tabla doctors
    }

    // Usuario que actualizó el appointment
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'update_by');
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
