<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisasterDocumentation extends Model
{
    protected $fillable = ['title', 'file_path', 'file_type'];
}