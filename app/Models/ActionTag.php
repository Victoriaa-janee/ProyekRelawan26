<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionTag extends Model
{
    protected $fillable = ['tag_name'];

    public function disasterReports()
    {
        return $this->belongsToMany(DisasterReport::class, 'disaster_tag');
    }
}