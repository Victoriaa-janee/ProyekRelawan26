<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BencanaController;
use App\Http\Controllers\AuthController;

// ==========================================
// 1. RUTE PUBLIK (Bisa diakses siapa saja tanpa login)
// ==========================================
Route::get('/', [BencanaController::class, 'index'])->name('home');
Route::get('/bencana/{id}', [BencanaController::class, 'show'])->name('bencana.show');

// Rute Proses Autentikasi
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pengalihan jika ada yang iseng akses /login lewat URL browser
Route::get('/login', function () {
    return redirect()->route('home');
});

// ==========================================
// 2. RUTE TERPROTEKSI (Wajib Login Dahulu)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Fitur Relawan: Laporkan kejadian baru & tambah update situasi lapangan
    Route::post('/bencana', [BencanaController::class, 'store'])->name('bencana.store');
    Route::post('/bencana/{id}/dokumentasi', [BencanaController::class, 'storeDokumentasi'])->name('bencana.storeDokumentasi');

    // Fitur Kontrol Admin: Ubah status & hapus data bencana
    // (Pagar pengaman tambahannya ada di dalam fungsi Controller masing-masing menggunakan cek role)
    Route::patch('/bencana/{id}/status', [BencanaController::class, 'updateStatus'])->name('bencana.updateStatus');
    Route::delete('/bencana/{id}', [BencanaController::class, 'destroy'])->name('bencana.destroy');
    
});