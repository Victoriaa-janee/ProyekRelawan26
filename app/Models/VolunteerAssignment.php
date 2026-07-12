<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerAssignment extends Model
{
    protected $fillable = ['disaster_report_id', 'volunteer_id', 'role_in_field', 'briefing_note'];

    public function disasterReport()
    {
        return $this->belongsTo(DisasterReport::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }
}