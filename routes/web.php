<?php

use App\Http\Controllers\VolunteerSystemController;
use App\Http\Controllers\VolunteerFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VolunteerSystemController::class, 'index'])->name('volunteer.dashboard');
Route::get('/volunteer/register', function () {
    return view('proyek_relawan.register');
})->name('volunteer.register.form');

Route::post('/volunteer/register', [VolunteerSystemController::class, 'storeVolunteer'])->name('volunteer.register');
Route::post('/volunteer/assign', [VolunteerSystemController::class, 'assignVolunteer'])->name('volunteer.assign');
Route::post('/disaster/upload', [VolunteerFileController::class, 'uploadDocumentation'])->name('disaster.upload');