<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAMBA - KalbarRelawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body { background-color: #f4f7f6; color: #333; }
        .navbar-custom { background-color: #1e3a8a; }
        .bg-primary-custom { background-color: #2563eb; color: white; }
        .bg-secondary-custom { background-color: #1e293b; color: white; }
        .alert-urgent { background-color: #dc2626; color: white; border: none; }
        .nav-tabs .nav-link.active { background-color: #2563eb; color: white; border-color: #2563eb; }
        .nav-tabs .nav-link { color: #1e3a8a; }
        .card-custom { border: none; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .card-clickable { cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
        .card-clickable:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .action-area { position: relative; z-index: 10; }
        
        .preview-container { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .preview-box { position: relative; width: 70px; height: 70px; border-radius: 4px; overflow: hidden; border: 1px solid #ddd; }
        .preview-box img { width: 100%; height: 100%; object-fit: cover; }
        .btn-remove-preview { position: absolute; top: 2px; right: 2px; background: rgba(220, 53, 69, 0.85); color: white; border: none; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; line-height: 1; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: bold; }
        .btn-remove-preview:hover { background: rgba(220, 53, 69, 1); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">KalbarRelawan</a>
            <div class="ms-auto">
                @auth
                    <span class="text-white me-3">Halo, {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                    </form>
                @else
                    <button class="btn btn-sm btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#loginAdminModal">Login Admin</button>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $urgentBencana = $bencana->filter(function($item) {
                return $item->kategori->is_urgent && $item->status != 'selesai';
            });
        @endphp

        @if($urgentBencana->count() > 0)
            <div class="alert alert-urgent d-flex justify-content-between align-items-center p-3 mb-4 rounded" role="alert">
                <div class="fw-bold">🚨 {{ $urgentBencana->count() }} Kejadian Bencana Mendesak Perlu Penanganan Segera!</div>
                <button class="btn btn-sm btn-light fw-bold text-danger" onclick="document.getElementById('tanggapan-tab').click();">Lihat Detail</button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card card-custom p-4 text-center mb-4 bg-primary-custom">
                    <h5 class="fw-bold text-white">SIAMBA</h5>
                    <p class="small mb-0">Sistem Alokasi Masalah Bencana Alam.</p>
                </div>

                <div class="card card-custom p-3 mb-4">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <h6 class="fw-bold mb-3">Panel Kelola Bencana</h6>
                            <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#laporModal">Tambah Laporan Bencana</button>
                        @else
                            <h6 class="fw-bold mb-3">Aksi Relawan</h6>
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#laporModal">Laporkan Kejadian Bencana di Wilayah Anda</button>
                        @endif
                    @else
                        <h6 class="fw-bold mb-3">Aksi Relawan</h6>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#loginRelawanModal">Laporkan Kejadian Bencana di Wilayah Anda</button>
                    @endauth
                </div>
            </div>

            <div class="col-md-8 mb-4">
                <div class="card card-custom p-4 mb-4">
                    <h5 class="fw-bold mb-3">Filter Data Bencana</h5>
                    <form action="{{ route('home') }}" method="GET" class="row g-2">
                        <div class="col-md-5">
                            <select name="bulan" class="form-select">
                                <option value="">Semua Bulan</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <select name="tahun" class="form-select">
                                @foreach(range(date('Y'), date('Y')-5) as $y)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>

                <div class="card card-custom p-4 mb-4 bg-secondary-custom">
                    <h6 class="fw-bold text-warning mb-3">Statistik Penanganan Bencana Total</h6>
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="fw-bold text-white">{{ $bencana->count() }}</h3>
                            <small class="text-light">Total Kejadian</small>
                        </div>
                        <div class="col-6">
                            <h3 class="fw-bold text-success">{{ $bencana->where('status', 'selesai')->count() }}</h3>
                            <small class="text-light">Selesai Ditangani</small>
                        </div>
                    </div>
                </div>

                <div class="card card-custom p-4 mb-4 bg-dark text-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-info mb-0">Statistik Laporan Bencana oleh Masyarakat</h6>
                        <small class="text-muted">Berdasarkan Data PU</small>
                    </div>
                    <div class="row">
                        <div class="col-md-3 border-end border-secondary">
                            <h2 class="fw-bold mb-0">{{ $bencana->count() }}</h2>
                            <small class="text-light d-block mb-2">Total Laporan</small>
                            <span class="text-muted small">Terakhir diupdate:<br>{{ date('d-m-Y') }}</span>
                        </div>
                        <div class="col-md-4 border-end border-secondary ps-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><strong>{{ $bencana->where('status', 'ditangani')->count() }}</strong> Ditangani</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span><strong>{{ $bencana->where('status', 'pending')->count() }}</strong> Tahap Verifikasi</span>
                            </div>
                        </div>
                        <div class="col-md-5 ps-3">
                            @foreach($kategori as $kat)
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>{{ $kat->nama_kategori }}</span>
                                    <strong>{{ $bencana->where('kategori_id', $kat->id)->count() }}</strong>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs mb-3" id="bencanaTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold" id="terkini-tab" data-bs-toggle="tab" data-bs-target="#terkini" type="button">Info Terkini</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold" id="tanggapan-tab" data-bs-toggle="tab" data-bs-target="#tanggapan" type="button">Tanggapan Darurat</button>
                    </li>
                </ul>

                <div class="tab-content" id="bencanaTabContent">
                    <div class="tab-pane fade show active" id="terkini">
                        @forelse($bencana as $item)
                            <div class="card card-custom card-clickable p-3 mb-3 bg-white" onclick="location.href='{{ route('bencana.show', $item->id) }}'">
                                <div class="row">
                                    <div class="col-md-3">
                                        @if(is_array($item->foto_awal) && count($item->foto_awal) > 0)
                                            <img src="{{ asset('storage/' . $item->foto_awal[0]) }}" class="img-fluid rounded" alt="Foto Kejadian">
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <div class="d-flex justify-content-between">
                                            <h5 class="fw-bold text-primary mb-1">{{ $item->judul_laporan }}</h5>
                                            <span class="badge {{ $item->status == 'pending' ? 'bg-warning' : ($item->status == 'ditangani' ? 'bg-info' : 'bg-success') }} text-dark align-self-start">{{ ucfirst($item->status) }}</span>
                                        </div>
                                        <p class="text-muted small mb-2">
                                            Kategori: <strong>{{ $item->kategori->nama_kategori }}</strong> | 
                                            @if(!empty($item->latitude) && !empty($item->longitude))
                                                <span class="action-area" onclick="event.stopPropagation();">
                                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="text-decoration-none text-primary fw-bold">📍 Lokasi: {{ $item->lokasi }} ↗</a>
                                                </span>
                                            @else
                                                Lokasi: {{ $item->lokasi }}
                                            @endif
                                        </p>
                                        <p class="mb-2">{{ $item->deskripsi }}</p>
                                        <div class="bg-light p-2 rounded small text-muted mb-2">
                                            📅 Kejadian: {{ $item->tanggal_kejadian }} ({{ $item->jam_kejadian }}) | 📤 Dilaporkan oleh: {{ $item->user->name }}
                                        </div>
                                        
                                        <div class="action-area" onclick="event.stopPropagation();">
                                            @if(Auth::check() && Auth::user()->role == 'admin')
                                                <form action="{{ route('bencana.updateStatus', $item->id) }}" method="POST" class="d-inline-block me-2">
                                                    @csrf @method('PATCH')
                                                    <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                        <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="ditangani" {{ $item->status == 'ditangani' ? 'selected' : '' }}>Ditangani</option>
                                                        <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    </select>
                                                </form>
                                            @endif

                                            @if(Auth::check() && (Auth::user()->role == 'admin' || (Auth::id() == $item->user_id && $item->status == 'pending')))
                                                <form action="{{ route('bencana.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4">Tidak ada data bencana pada bulan/tahun ini.</p>
                        @endforelse
                    </div>

                    <div class="tab-pane fade" id="tanggapan">
                        @forelse($bencana->where('kategori.is_urgent', true) as $item)
                            <div class="card card-custom card-clickable p-3 mb-3 border-start border-danger border-4 bg-white" onclick="location.href='{{ route('bencana.show', $item->id) }}'">
                                <div class="row">
                                    <div class="col-md-3">
                                        @if(is_array($item->foto_awal) && count($item->foto_awal) > 0)
                                            <img src="{{ asset('storage/' . $item->foto_awal[0]) }}" class="img-fluid rounded mb-2" alt="Foto Kejadian">
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h5 class="fw-bold text-danger mb-1">{{ $item->judul_laporan }} [URGENT]</h5>
                                        <p class="text-muted small mb-2">Kategori: <strong>{{ $item->kategori->nama_kategori }}</strong></p>
                                        <p class="mb-2">{{ $item->deskripsi }}</p>
                                        <div class="bg-light p-2 rounded small text-muted">
                                            📅 Waktu: {{ $item->tanggal_kejadian }} ({{ $item->jam_kejadian }}) | 
                                            @if(!empty($item->latitude) && !empty($item->longitude))
                                                <span class="action-area" onclick="event.stopPropagation();">
                                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="text-decoration-none text-primary fw-bold">📍 Lokasi: {{ $item->lokasi }} ↗</a>
                                                </span>
                                            @else
                                                📍 Lokasi: {{ $item->lokasi }}
                                            @endif
                                             | Status: <strong>{{ ucfirst($item->status) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4">Aman. Tidak ada bencana urgensi tinggi saat ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL LOGIN ADMIN (SUDAH DIPERBAIKI) -->
    <div class="modal fade" id="loginAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Login Khusus Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <input type="hidden" name="is_admin_login" value="1">
                        <div class="mb-3">
                            <label class="form-label">Email Admin</label>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan email admin..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password..." required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Masuk sebagai Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginRelawanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Portal Relawan SIAMBA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button">Masuk</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="pills-register-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button">Daftar Relawan</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-login">
                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Masuk</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="pills-register">
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Daftar Sebagai Relawan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL LAPOR BENCANA (SUDAH DITAMBAHKAN LOKASI PETA) -->
    <div class="modal fade" id="laporModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Buat Laporan Kejadian Bencana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formLapor" action="{{ route('bencana.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Kategori Bencana</label>
                            <select name="kategori_id" class="form-select" required>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }} {{ $kat->is_urgent ? '[URGENT]' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Judul Laporan</label>
                            <input type="text" name="judul_laporan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Kejadian</label>
                            <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi Detail</label>
                            <div class="input-group">
                                <input type="text" name="lokasi" id="inputLokasiTeks" class="form-control" placeholder="Nama jalan, patokan, atau nama tempat..." required>
                                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalPilihPeta">
                                    🗺️ Pilih di Peta
                                </button>
                            </div>
                            <!-- Input koordinat hidden untuk dikirim ke backend -->
                            <input type="hidden" name="latitude" id="geoLat">
                            <input type="hidden" name="longitude" id="geoLng">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Bukti Awal (Maks 5 Foto, Maks 10MB/Foto)</label>
                            <input type="file" id="inputFotoAwal" class="form-control" accept="image/*" multiple required>
                            <div id="previewAwalContainer" class="preview-container"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Kirim Laporan Resmi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL KHUSUS PETA (UNTUK MENANDAI TITIK KOORDINAT) -->
    <div class="modal fade" id="modalPilihPeta" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title fw-bold">Tandai Lokasi Kejadian (Area Pontianak)</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 position-relative">
                    <button type="button" id="btnGunakanGps" class="btn btn-sm btn-light position-absolute shadow-sm" style="z-index: 1000; top: 10px; right: 10px; border: 1px solid #ccc;">
                        📍 Gunakan Lokasi Sekarang
                    </button>
                    <div id="mapPilih" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="modal-footer py-1">
                <!-- Menggunakan trik data-bs-toggle untuk kembali membuka laporModal secara aman -->
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#laporModal">
                    Selesai & Kunci Lokasi
                </button>
            </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // --- LOGIC IMAGE UPLOAD PREVIEW ---
        let fileListLapor = new DataTransfer();
        const inputFotoAwal = document.getElementById('inputFotoAwal');
        const previewAwalContainer = document.getElementById('previewAwalContainer');
        const formLapor = document.getElementById('formLapor');

        inputFotoAwal.addEventListener('change', function() {
            const files = Array.from(this.files);
            if (fileListLapor.files.length + files.length > 5) {
                alert('Maksimal foto yang bisa diupload adalah 5 foto!');
                this.value = '';
                return;
            }
            files.forEach(file => {
                fileListLapor.items.add(file);
                const reader = new FileReader();
                reader.onload = function(e) {
                    const box = document.createElement('div');
                    box.className = 'preview-box';
                    box.innerHTML = `
                        <img src="${e.target.result}">
                        <button type="button" class="btn-remove-preview" onclick="removeFileLapor('${file.name}', this)">×</button>
                    `;
                    previewAwalContainer.appendChild(box);
                }
                reader.readAsDataURL(file);
            });
            syncInputFiles(inputFotoAwal, fileListLapor);
        });

        function removeFileLapor(fileName, buttonEl) {
            const dt = new DataTransfer();
            for (let i = 0; i < fileListLapor.files.length; i++) {
                if (fileListLapor.files[i].name !== fileName) {
                    dt.items.add(fileListLapor.files[i]);
                }
            }
            fileListLapor = dt;
            syncInputFiles(inputFotoAwal, fileListLapor);
            buttonEl.parentElement.remove();
            if(fileListLapor.files.length === 0) {
                inputFotoAwal.required = true;
            }
        }

        function syncInputFiles(inputEl, dataTransferObj) {
            inputEl.files = dataTransferObj.files;
        }

        formLapor.addEventListener('submit', function(e) {
            const dt = new DataTransfer();
            for (let i = 0; i < fileListLapor.files.length; i++) {
                const file = fileListLapor.files[i];
                const newFile = new File([file], file.name, { type: file.type });
                dt.items.add(newFile);
            }
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'file';
            hiddenInput.name = 'foto_awal[]';
            hiddenInput.multiple = true;
            hiddenInput.style.display = 'none';
            hiddenInput.files = dt.files;
            formLapor.appendChild(hiddenInput);
            inputFotoAwal.removeAttribute('name'); 
        });

        // --- LOGIC LEAFLET MAPS PONTIANAK ---
        let mapInput, markerInput;
        const pusatPontianak = [-0.0263, 109.3425];
        const modalPetaEl = document.getElementById('modalPilihPeta');

        modalPetaEl.addEventListener('shown.bs.modal', function () {
            if (!mapInput) {
                mapInput = L.map('mapPilih').setView(pusatPontianak, 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(mapInput);

                markerInput = L.marker(pusatPontianak, { draggable: true }).addTo(mapInput);
                setKoordinatForm(pusatPontianak[0], pusatPontianak[1]);

                markerInput.on('dragend', function (e) {
                    const pos = markerInput.getLatLng();
                    setKoordinatForm(pos.lat, pos.lng);
                });

                mapInput.on('click', function (e) {
                    markerInput.setLatLng(e.latlng);
                    setKoordinatForm(e.latlng.lat, e.latlng.lng);
                });
            } else {
                mapInput.invalidateSize();
            }
        });

        function setKoordinatForm(lat, lng) {
            document.getElementById('geoLat').value = lat;
            document.getElementById('geoLng').value = lng;
        }

        document.getElementById('btnGunakanGps').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    mapInput.setView([lat, lng], 16);
                    markerInput.setLatLng([lat, lng]);
                    setKoordinatForm(lat, lng);
                }, function() {
                    alert("Gagal membaca GPS. Pastikan izin lokasi browser Anda aktif.");
                });
            } else {
                alert("Browser Anda tidak mendukung deteksi lokasi otomatis.");
            }
        });
    </script>
</body>
</html>