<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    //fillable fields
    protected $fillable = [
        'name',
        'status',
    ];
    //
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
