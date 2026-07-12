<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Indorelawan Tanggap Bencana</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    <nav class="bg-red-600 text-white p-4 shadow-sm">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="#" class="text-xl font-bold tracking-wider">INDORELAWAN</a>
            <div class="space-x-4 text-sm font-semibold">
                <a href="#musibah" class="hover:underline">Cari Aktivitas</a>
                <a href="#pantau" class="hover:underline">Pantau Log</a>
                <a href="{{ route('volunteer.register.form') }}" class="bg-white text-red-600 px-4 py-1.5 rounded font-bold hover:bg-gray-100">Daftar Terdata</a>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTAINER -->
    <main class="max-w-6xl mx-auto p-6 space-y-12">

        @if(session('success'))
            <div class="bg-green-600 text-white p-3 rounded font-semibold text-sm shadow">{{ session('success') }}</div>
        @endif

        <!-- 1. DAFTAR MUSIBAH AKTIF (FOKUS UTAMA) -->
        <section id="musibah" class="space-y-4">
            <h2 class="text-xl font-bold border-b-2 border-red-600 pb-1">🚨 Laporan & Kebutuhan Bencana Terkini</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($disasters as $d)
                    <div class="bg-white border border-gray-200 rounded p-4 shadow-sm flex flex-col justify-between">
                        <div class="space-y-2">
                            <h3 class="font-bold text-gray-900 text-base">{{ $d->title }}</h3>
                            <p class="text-xs text-gray-600">{{ $d->description }}</p>
                            <div class="text-xs text-gray-500 bg-gray-50 p-2 rounded">
                                <p>📍 <b>Lokasi:</b> {{ $d->location }}</p>
                                <p>👥 <b>Kuota Relawan Resmi:</b> {{ $d->joined_volunteers }} / {{ $d->required_volunteers }}</p>
                            </div>
                        </div>

                        <!-- INPUT TUGAS FORM -->
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <details class="group">
                                <summary class="text-xs font-bold text-red-600 cursor-pointer hover:underline">⚡ Ambil Penugasan Resmi (Bagi Yang Sudah Terdaftar)</summary>
                                <form action="{{ route('volunteer.assign') }}" method="POST" class="mt-2 space-y-2 bg-gray-50 p-2 rounded border border-gray-200">
                                    @csrf
                                    <input type="hidden" name="disaster_report_id" value="{{ $d->id }}">
                                    <select name="volunteer_id" class="w-full p-1.5 border text-xs rounded bg-white" required>
                                        <option value="">-- Pilih Nama Relawan Anda --</option>
                                        @foreach($volunteers as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="role_in_field" placeholder="Peran Lapangan (Misal: Logistik, Evakuasi)" class="w-full p-1.5 border text-xs rounded bg-white" required>
                                    <button type="submit" class="w-full bg-red-600 text-white p-1.5 rounded text-xs font-bold hover:bg-red-700">Kunci Penugasan</button>
                                </form>
                            </details>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-6 text-gray-400 italic text-xs bg-white border rounded">Belum ada laporan bencana aktif.</div>
                @endforelse
            </div>
        </section>

        <!-- 2. UPLOAD FILE LAPANGAN -->
        <section class="bg-white border border-gray-200 rounded p-4 shadow-sm">
            <h2 class="text-sm font-bold text-gray-700 mb-2">📸 Upload Bukti / Dokumen Situasi Terkini</h2>
            <form action="{{ route('disaster.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap gap-3 items-end">
                @csrf
                <input type="text" name="title" placeholder="Keterangan Laporan (Contoh: Banjir Posko A)" class="flex-1 p-2 border text-xs rounded" required>
                <input type="file" name="attachment" class="p-1 border text-xs rounded" required>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded text-xs font-bold hover:bg-red-700">Upload Berkas</button>
            </form>
        </section>

        <!-- 3. LIVE SEARCH & FILTER AKTIVITAS -->
        <section id="pantau" class="space-y-4">
            <h2 class="text-xl font-bold border-b-2 border-red-600 pb-1">📊 Log Pemantauan Aktivitas Relawan</h2>
            
            <form action="{{ route('volunteer.dashboard') }}" method="GET" class="flex gap-2 bg-white p-3 rounded border border-gray-200 shadow-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari laporan progres aktivitas..." class="p-2 border text-xs rounded flex-1">
                <select name="min_vote" class="p-2 border text-xs rounded bg-white">
                    <option value="">-- Semua Validasi --</option>
                    <option value="100" {{ request('min_vote') == '100' ? 'selected' : '' }}>&gt; 100 Dukungan</option>
                </select>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded text-xs font-bold hover:bg-red-700">Cari</button>
                @if(request()->filled('search') || request()->filled('min_vote'))
                    <a href="{{ route('volunteer.dashboard') }}" class="bg-gray-200 text-gray-700 px-3 py-2 rounded text-xs font-bold flex items-center">Reset</a>
                @endif
            </form>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                @forelse($logs as $l)
                    <div class="bg-white border border-gray-200 p-3 rounded shadow-sm flex flex-col justify-between">
                        <div>
                            <h4 class="font-bold text-xs text-gray-800 line-clamp-1">{{ $l->log_title }}</h4>
                            <p class="text-[10px] text-gray-500 mt-1 line-clamp-3">{{ $l->activity_detail }}</p>
                        </div>
                        <div class="text-[10px] font-bold text-red-600 mt-2 pt-1 border-t border-gray-100">
                            Validasi: {{ number_format($l->verified_votes) }}
                        </div>
                    </div>
                @empty
                    <div class="col-span-5 text-center py-4 text-gray-400 italic text-xs bg-white border rounded">Data tidak ditemukan.</div>
                @endforelse
            </div>
            <div class="bg-white p-2 rounded border shadow-sm text-xs">{{ $logs->links() }}</div>
        </section>

    </main>

    <footer class="bg-white text-gray-400 text-center text-[10px] py-4 mt-12 border-t">
        <p>© 2026 ProyekRelawan26 - Program Studi Sistem Informasi.</p>
    </footer>

</body>
</html>