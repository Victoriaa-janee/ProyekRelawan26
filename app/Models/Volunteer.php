<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $fillable = ['name', 'email', 'phone_number'];

    public function profile()
    {
        return $this->hasOne(VolunteerProfile::class);
    }

    public function assignments()
    {
        return $this->hasMany(VolunteerAssignment::class);
    }
}