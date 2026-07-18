<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumentasiRelawan extends Model
{
    use HasFactory;

    protected $table = 'dokumentasi_relawan';

    protected $fillable = [
        'bencana_id',
        'user_id',
        'foto_dokumentasi',
        'keterangan',
    ];

    protected $casts = [
        'foto_dokumentasi' => 'array',
    ];

    public function bencana()
    {
        return $this->belongsTo(Bencana::class, 'bencana_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}