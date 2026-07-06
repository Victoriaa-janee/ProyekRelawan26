<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PantauBencana Pontianak</title>
    <!-- Tailwind CSS CDN untuk mempermudah styling sementara -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-serif min-h-screen">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <span class="font-bold text-xl tracking-wider">SI-SIAGA PONTIANAK</span>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#" class="px-3 py-2 rounded-md text-sm font-medium bg-blue-700">Beranda</a>
                        <a href="#" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-500">Lapor Bencana</a>
                        <a href="#" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-500">Edukasi</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-white border-b border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Sistem Informasi & Tanggap Bencana Wilayah Pontianak
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Saling bantu, saling jaga. Dapatkan informasi terkini mengenai situasi bencana dan aksi kemanusiaan di Kota Khatulistiwa.
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Sekilas Info / Stats Ringkas -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-red-500">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Laporan Aktif Hari Ini</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">2</dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-yellow-500">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Wilayah Waspada (Siaga)</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">Pontianak Barat</dd>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Kebutuhan Logistik Mendesak</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">Air Bersih & Makanan</dd>
                </div>
            </div>
        </div>

        <!-- Daftar Kejadian Bencana Terbaru -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-4">
                <h2 class="text-xl font-bold text-gray-900">Laporan Kejadian Terkini</h2>
                <span class="text-sm text-blue-600 font-medium">Lingkup: Kota Pontianak</span>
            </div>

            <!-- List Data Temuan (Dummy) -->
            <div class="space-y-4">
                <!-- Contoh Kejadian 1 -->
                <div class="p-4 bg-red-50 rounded-md border border-red-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-red-600 text-white text-xs font-semibold rounded">Darurat</span>
                            <h3 class="text-lg font-bold text-gray-900">Banjir Rob / Pasang Air Laut</h3>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Lokasi: Jl. Gajah Mada, Pontianak Selatan (Ketinggian air sekitar 30-50 cm)</p>
                        <p class="text-xs text-gray-400 mt-2">Dilaporkan: 10 menit yang lalu oleh Warga</p>
                    </div>
                    <div>
                        <a href="#" class="inline-block px-4 py-2 bg-red-600 text-white text-sm font-medium rounded hover:bg-red-700 transition">Lihat Detail</a>
                    </div>
                </div>

                <!-- Contoh Kejadian 2 -->
                <div class="p-4 bg-yellow-50 rounded-md border border-yellow-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-yellow-500 text-white text-xs font-semibold rounded">Waspada</span>
                            <h3 class="text-lg font-bold text-gray-900">Pohon Tumbang Akibat Angin Kencang</h3>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Lokasi: Sekitar Jl. Ahmad Yani, Pontianak Tenggara (Menghambat sebagian akses jalan)</p>
                        <p class="text-xs text-gray-400 mt-2">Dilaporkan: 1 jam yang lalu oleh Relawan</p>
                    </div>
                    <div>
                        <a href="#" class="inline-block px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded hover:bg-yellow-600 transition">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm">
            &copy; 2026 Proyek Kemanusiaan Mahasiswa - Siaga Bencana Pontianak.
        </div>
    </footer>

</body>
</html>