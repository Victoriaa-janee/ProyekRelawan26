<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bencana;
use App\Models\KategoriBencana;
use App\Models\DokumentasiRelawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BencanaController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun', date('Y'));

        $query = Bencana::with(['user', 'kategori']);

        if ($bulan) {
            $query->whereMonth('tanggal_kejadian', $bulan);
        }
        if ($tahun) {
            $query->whereYear('tanggal_kejadian', $tahun);
        }

        $bencana = $query->latest()->get();
        $kategori = KategoriBencana::all();

        return view('welcome', compact('bencana', 'kategori', 'bulan', 'tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_bencana,id',
            'judul_laporan' => 'required|string|max:255',
            'deskripsi' => 'required',
            'lokasi' => 'required|string',
            // Tambahkan validasi koordinat baru dari peta di sini
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'foto_awal' => 'required|array|max:5',
            'foto_awal.*' => 'image|max:10240',
        ]);

        $paths = [];
        if ($request->hasFile('foto_awal')) {
            foreach ($request->file('foto_awal') as $file) {
                $paths[] = $file->store('bencana', 'public');
            }
        }

        Bencana::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'judul_laporan' => $request->judul_laporan,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            // Simpan data koordinat ke database
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'tanggal_kejadian' => date('Y-m-d'),
            'jam_kejadian' => date('H:i:s'),
            'foto_awal' => $paths,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Laporan bencana berhasil dikirim.');
    }

    public function show($id)
    {
        $item = Bencana::with(['user', 'kategori', 'dokumentasiRelawan.user'])->findOrFail($id);
        return view('detail', compact('item'));
    }

    public function storeDokumentasi(Request $request, $id)
    {
        $item = Bencana::findOrFail($id);

        // Pagar Pengaman: Tolak jika yang upload BUKAN admin DAN BUKAN relawan pembuat laporan ini
        if (Auth::user()->role !== 'admin' && Auth::user()->id !== $item->user_id) {
            abort(403, 'Anda tidak memiliki hak untuk memperbarui situasi pada laporan ini.');
        }

        $request->validate([
            'foto_dokumentasi' => 'nullable|array|max:5',
            'foto_dokumentasi.*' => 'image|max:10240',
            'keterangan' => 'required_without:foto_dokumentasi|nullable|string',
        ]);

        $paths = null;
        if ($request->hasFile('foto_dokumentasi')) {
            $paths = [];
            foreach ($request->file('foto_dokumentasi') as $file) {
                $paths[] = $file->store('dokumentasi', 'public');
            }
        }

        DokumentasiRelawan::create([
            'bencana_id' => $id,
            'user_id' => Auth::id(),
            'foto_dokumentasi' => $paths,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Pesan diskusi berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,ditangani,selesai',
        ]);

        $bencana = Bencana::findOrFail($id);
        $bencana->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status bencana berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bencana = Bencana::findOrFail($id);

        // Logika aslimu sudah aman! Mengizinkan Admin menghapus apa saja, 
        // dan mengizinkan Relawan pembuat menghapusnya *hanya jika* statusnya masih pending.
        if (Auth::user()->role !== 'admin' && ($bencana->user_id !== Auth::id() || $bencana->status !== 'pending')) {
            abort(403);
        }

        if (is_array($bencana->foto_awal)) {
            foreach ($bencana->foto_awal as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }
        
        $bencana->delete();

        // Diubah sedikit redirect-nya agar kembali ke halaman utama jika menghapus dari halaman detail
        return redirect()->route('home')->with('success', 'Laporan berhasil dihapus.');
    }
}