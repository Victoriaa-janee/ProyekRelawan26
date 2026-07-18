<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBencana extends Model
{
    protected $table = 'kategori_bencana';
    protected $fillable = ['nama_kategori', 'is_urgent'];

    public function bencanas() {
        return $this->hasMany(Bencana::class, 'kategori_id');
    }
}