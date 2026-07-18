<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bencana extends Model
{
    use HasFactory;

    protected $table = 'bencana';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul_laporan',
        'deskripsi',
        'lokasi',
        'tanggal_kejadian',
        'jam_kejadian',
        'foto_awal',
        'status',
    ];

    protected $casts = [
        'foto_awal' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBencana::class, 'kategori_id');
    }

    public function dokumentasiRelawan()
    {
        return $this->hasMany(DokumentasiRelawan::class, 'bencana_id');
    }
}