<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\TracksUserActions;

class User extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;
    use TracksUserActions;
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
        'country_id',
        'state_id',
        'city_id',
        'address',
        'phone',
        'email',
        'password',
        'status',
    ];

    // Métodos para obtener los nombres (sin relaciones Eloquent)
    public function getCountryNameAttribute()
    {
        return \DB::table('countries')->where('id', $this->country_id)->value('name');
    }

    public function getStateNameAttribute()
    {
        return \DB::table('states')->where('id', $this->state_id)->value('name');
    }

    public function getCityNameAttribute()
    {
        return \DB::table('cities')->where('id', $this->city_id)->value('name');
    }
    public function country()
    {
        return $this->belongsTo(\Nnjeim\World\Models\Country::class);
    }

    public function state()
    {
        return $this->belongsTo(\Nnjeim\World\Models\State::class);
    }

    public function city()
    {
        return $this->belongsTo(\Nnjeim\World\Models\City::class);
    }
    // Scope para búsquedas por ubicación
    public function scopeByLocation($query, $countryId = null, $stateId = null, $cityId = null)
    {
        if ($countryId) {
            $query->where('country_id', $countryId);
        }
        if ($stateId) {
            $query->where('state_id', $stateId);
        }
        if ($cityId) {
            $query->where('city_id', $cityId);
        }
        return $query;
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class);
    }
    public function appointmentHistory()
    {
        return $this->hasMany(AppointmentHistory::class);
    }
    // Relación 1:1 con Doctor (un user PUEDE ser un profesional)
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id');
    }

    // Usuario que actualizó el appointment
    public function updatedById()
    {
        return $this->belongsTo(User::class, 'updated_by');
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
