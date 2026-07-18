<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BencanaController;
use App\Http\Controllers\AuthController;

Route::get('/', [BencanaController::class, 'index'])->name('home');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/bencana', [BencanaController::class, 'store'])->name('bencana.store');
Route::patch('/bencana/{id}/status', [BencanaController::class, 'updateStatus'])->name('bencana.updateStatus');
Route::delete('/bencana/{id}', [BencanaController::class, 'destroy'])->name('bencana.destroy');

Route::get('/bencana/{id}', [BencanaController::class, 'show'])->name('bencana.show');
Route::post('/bencana/{id}/dokumentasi', [BencanaController::class, 'storeDokumentasi'])->name('bencana.storeDokumentasi');

Route::get('/login', function () {
    return redirect()->route('home');
});