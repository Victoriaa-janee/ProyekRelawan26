<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VolunteerLog extends Model
{
    use HasFactory;
    protected $fillable = ['log_title', 'activity_detail', 'verified_votes'];
}