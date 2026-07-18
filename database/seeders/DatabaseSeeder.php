<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\KategoriBencana;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin SIAMBA',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        KategoriBencana::create(['nama_kategori' => 'Kebakaran Hutan / Lahan', 'is_urgent' => true]);
        KategoriBencana::create(['nama_kategori' => 'Banjir Bandang', 'is_urgent' => true]);
        KategoriBencana::create(['nama_kategori' => 'Tanah Longsor', 'is_urgent' => true]);
        KategoriBencana::create(['nama_kategori' => 'Kekeringan', 'is_urgent' => false]);
        KategoriBencana::create(['nama_kategori' => 'Cuaca Ekstrem', 'is_urgent' => false]);
    }
}