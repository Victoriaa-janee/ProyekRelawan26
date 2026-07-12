<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Relawan - Indorelawan</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-red-600 text-white p-4 shadow-sm">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('volunteer.dashboard') }}" class="text-xl font-bold tracking-wider">INDORELAWAN</a>
            <a href="{{ route('volunteer.dashboard') }}" class="text-sm font-semibold hover:underline">← Kembali ke Beranda</a>
        </div>
    </nav>

    <main class="max-w-xl mx-auto p-6 mt-8">
        @if(session('success'))
            <div class="bg-green-600 text-white p-3 rounded font-semibold text-sm shadow mb-4">{{ session('success') }}</div>
        @endif

        <div class="bg-white border-t-4 border-red-600 p-6 rounded shadow-sm">
            <div class="text-center mb-4">
                <h2 class="text-lg font-bold text-gray-900">📝 Pendaftaran Anggota Relawan Resmi</h2>
                <p class="text-xs text-gray-500">Mencatat profil fisik dan keahlian medis ke database induk posko.</p>
            </div>
            <form action="{{ route('volunteer.register') }}" method="POST" class="space-y-3">
                @csrf
                <input type="text" name="name" placeholder="Nama Lengkap" class="w-full p-2 border text-xs rounded" required>
                <input type="email" name="email" placeholder="Alamat Email" class="w-full p-2 border text-xs rounded" required>
                <input type="text" name="phone_number" placeholder="Nomor Telepon / WA" class="w-full p-2 border text-xs rounded" required>
                <select name="blood_type" class="w-full p-2 border text-xs rounded bg-white" required>
                    <option value="">-- Pilih Golongan Darah --</option>
                    <option value="A">Golongan Darah A</option>
                    <option value="B">Golongan Darah B</option>
                    <option value="O">Golongan Darah O</option>
                    <option value="AB">Golongan Darah AB</option>
                </select>
                <input type="text" name="emergency_contact" placeholder="Kontak Darurat Keluarga (Nama - No HP)" class="w-full p-2 border text-xs rounded" required>
                <textarea name="skills" placeholder="Deskripsi Keahlian Khusus Lapangan (Contoh: Evakuasi SAR, Dapur Umum)" class="w-full p-2 border text-xs rounded" rows="2" required></textarea>
                <button type="submit" class="w-full bg-red-600 text-white p-2 rounded font-bold text-xs hover:bg-red-700 shadow">Kirim Data Pendaftaran</button>
            </form>
        </div>
    </main>

</body>
</html>