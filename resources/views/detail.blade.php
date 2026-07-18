<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - SIAMBA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; color: #333; }
        .navbar-custom { background-color: #1e3a8a; }
        .card-custom { border: none; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .img-preview-clickable { cursor: pointer; transition: opacity 0.2s; }
        .img-preview-clickable:hover { opacity: 0.8; }
        
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
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">KalbarRelawan</a>
            <div class="ms-auto">
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light">Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto mb-4">
                
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

                <div class="card card-custom p-4 bg-white mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bold text-primary mb-0">{{ $item->judul_laporan }}</h3>
                        <span class="badge {{ $item->status == 'pending' ? 'bg-warning' : ($item->status == 'ditangani' ? 'bg-info' : 'bg-success') }} text-dark p-2">{{ ucfirst($item->status) }}</span>
                    </div>

                    <p class="text-muted">
                        Kategori: <strong>{{ $item->kategori->nama_kategori }}</strong> 
                        @if($item->kategori->is_urgent)
                            <span class="text-danger fw-bold">[URGENT]</span>
                        @endif
                    </p>

                    <h6 class="fw-bold mb-2">Foto Bukti Awal:</h6>
                    <div class="row g-2 mb-4">
                        @if(is_array($item->foto_awal))
                            @foreach($item->foto_awal as $foto)
                                <div class="col-6 col-sm-4">
                                    <img src="{{ asset('storage/' . $foto) }}" class="img-fluid rounded object-fit-cover w-100 img-preview-clickable" style="height: 150px;" onclick="viewFullImage(this.src)">
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <h5 class="fw-bold">Deskripsi Kejadian</h5>
                    <p class="mb-4">{{ $item->deskripsi }}</p>

                    <h5 class="fw-bold">Informasi Tambahan</h5>
                    <div class="bg-light p-3 rounded mb-4">
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <strong>📍 Lokasi Detail:</strong><br>{{ $item->lokasi }}
                            </div>
                            <div class="col-sm-6 mb-2">
                                <strong>📤 Dilaporkan Oleh:</strong><br>{{ $item->user->name }}
                            </div>
                            <div class="col-sm-6">
                                <strong>📅 Tanggal Kejadian:</strong><br>{{ $item->tanggal_kejadian }}
                            </div>
                            <div class="col-sm-6">
                                <strong>⏰ Jam Kejadian:</strong><br>{{ $item->jam_kejadian }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-custom p-4 bg-white mb-4">
                    <h5 class="fw-bold mb-3">📸 Situasi Aksi Relawan Lapangan</h5>

                    @auth
                        <form id="formDokumentasi" action="{{ route('bencana.storeDokumentasi', $item->id) }}" method="POST" enctype="multipart/form-data" class="mb-4 p-3 border rounded bg-light">
                            @csrf
                            <h6 class="fw-bold mb-2 small text-secondary">Upload Update Kondisi Lokasi</h6>
                            <div class="mb-2">
                                <input type="file" id="inputFotoDoc" class="form-control form-control-sm" accept="image/*" multiple>
                                <small class="text-muted" style="font-size: 11px;">Upload Maksimal 10 foto.</small>
                                <div id="previewDocContainer" class="preview-container"></div>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="keterangan" class="form-control form-control-sm" placeholder="Tambahkan keterangan singkat situasi di sana...">
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                        </form>
                    @else
                        <div class="alert alert-warning small py-2 text-center">Silakan login di halaman beranda untuk ikut mengunggah dokumentasi lapangan.</div>
                    @endauth

                    <div class="row g-3">
                        @forelse($item->dokumentasiRelawan as $doc)
                            @php
                                $hasImages = is_array($doc->foto_dokumentasi) && count($doc->foto_dokumentasi) > 0;
                            @endphp
                            <div class="col-12">
                                <div class="border rounded bg-light {{ $hasImages ? 'p-3' : 'py-2 px-3' }}">
                                    <p class="mb-1 fw-bold small text-primary">Update Situasi:</p>
                                    <p class="{{ $hasImages ? 'mb-2' : 'mb-1' }} small">{{ $doc->keterangan ?? 'Tidak ada keterangan.' }}</p>
                                    
                                    @if($hasImages)
                                        <div class="row g-2 mb-2">
                                            @foreach($doc->foto_dokumentasi as $fDoc)
                                                <div class="col-4 col-sm-3">
                                                    <img src="{{ asset('storage/' . $fDoc) }}" class="img-fluid rounded object-fit-cover w-100 img-preview-clickable" style="height: 100px;" onclick="viewFullImage(this.src)">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <hr class="my-1">
                                    <small class="text-muted d-block" style="font-size: 11px;">Oleh: {{ $doc->user->name }} | {{ $doc->created_at->format('d-m-Y H:i') }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center small py-3 mb-0">Belum ada dokumentasi aksi dari relawan di lokasi kejadian ini.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 text-end">
                <button type="button" class="btn-close btn-close-white ms-auto mb-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0 text-center">
                    <img id="lightboxImage" src="" class="img-fluid rounded shadow" style="max-height: 85vh;">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewFullImage(src) {
            document.getElementById('lightboxImage').src = src;
            var myModal = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
            myModal.show();
        }

        let fileListDoc = new DataTransfer();
        const inputFotoDoc = document.getElementById('inputFotoDoc');
        const previewDocContainer = document.getElementById('previewDocContainer');
        const formDokumentasi = document.getElementById('formDokumentasi');

        if(inputFotoDoc) {
            inputFotoDoc.addEventListener('change', function() {
                const files = Array.from(this.files);
                
                if (fileListDoc.files.length + files.length > 5) {
                    alert('Maksimal foto yang bisa diupload adalah 5 foto!');
                    this.value = '';
                    return;
                }

                files.forEach(file => {
                    fileListDoc.items.add(file);
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const box = document.createElement('div');
                        box.className = 'preview-box';
                        box.innerHTML = `
                            <img src="${e.target.result}">
                            <button type="button" class="btn-remove-preview" onclick="removeFileDoc('${file.name}', this)">×</button>
                        `;
                        previewDocContainer.appendChild(box);
                    }
                    reader.readAsDataURL(file);
                });

                inputFotoDoc.files = fileListDoc.files;
            });
        }

        function removeFileDoc(fileName, buttonEl) {
            const dt = new DataTransfer();
            for (let i = 0; i < fileListDoc.files.length; i++) {
                if (fileListDoc.files[i].name !== fileName) {
                    dt.items.add(fileListDoc.files[i]);
                }
            }
            fileListDoc = dt;
            inputFotoDoc.files = fileListDoc.files;
            buttonEl.parentElement.remove();
        }

        if(formDokumentasi) {
            formDokumentasi.addEventListener('submit', function(e) {
                if (fileListDoc.files.length > 0) {
                    const dt = new DataTransfer();
                    for (let i = 0; i < fileListDoc.files.length; i++) {
                        const file = fileListDoc.files[i];
                        const newFile = new File([file], file.name, { type: file.type });
                        dt.items.add(newFile);
                    }
                    
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'file';
                    hiddenInput.name = 'foto_dokumentasi[]';
                    hiddenInput.multiple = true;
                    hiddenInput.style.display = 'none';
                    hiddenInput.files = dt.files;
                    
                    formDokumentasi.appendChild(hiddenInput);
                }
                inputFotoDoc.removeAttribute('name');
            });
        }
    </script>
</body>
</html>