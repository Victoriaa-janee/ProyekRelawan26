<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerProfile extends Model
{
    protected $fillable = ['volunteer_id', 'blood_type', 'emergency_contact', 'skills'];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }
}