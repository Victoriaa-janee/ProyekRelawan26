<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisasterReport extends Model
{
    protected $fillable = ['title', 'description', 'location', 'required_volunteers', 'joined_volunteers', 'incident_date'];

    public function assignments()
    {
        return $this->hasMany(VolunteerAssignment::class);
    }

    public function actionTags()
    {
        return $this->belongsToMany(ActionTag::class, 'disaster_tag');
    }
}