<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumentasiRelawan;
use Illuminate\Support\Facades\Auth;

class DokumentasiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bencana_id' => 'required|exists:bencana,id',
            'foto_dokumentasi' => 'required|image|max:10240',
            'keterangan' => 'nullable|string',
        ]);

        $path = $request->file('foto_dokumentasi')->store('dokumentasi', 'public');

        DokumentasiRelawan::create([
            'bencana_id' => $request->bencana_id,
            'user_id' => Auth::id(),
            'foto_dokumentasi' => $path,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Dokumentasi aksi relawan berhasil ditambahkan.');
    }
}