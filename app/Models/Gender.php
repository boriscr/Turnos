<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    //
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
