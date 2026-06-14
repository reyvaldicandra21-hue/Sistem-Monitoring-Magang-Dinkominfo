@extends('layouts.pesertapkl')

@section('content')



<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center overflow-hidden">
                <a href="{{ route('pesertapkl.tugas.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-2 me-md-3 flex-shrink-0" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div class="text-truncate">
                    <h5 class="fw-bold mb-0 text-truncate">Kumpulkan Tugas</h5>
                    <small class="text-muted text-truncate d-block">Upload hasil pekerjaan terbaikmu</small>
                </div>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                    <i class='bx bx-time-five'></i> Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

<!-- ================= FORM AREA ================= -->
<div class="col-lg-8">
    <div class="card-soft h-100">
        
        <!-- INFO TUGAS -->
        <div class="bg-light p-4 rounded-4 mb-4 border-start border-primary border-4">
            <h5 class="fw-bold text-dark mb-2">{{ $tugas->judul }}</h5>
            <div class="d-flex flex-wrap gap-3 mt-3">
                <div class="d-flex align-items-center small text-secondary">
                    <i class='bx bx-calendar me-1 text-primary'></i>
                    Tenggat: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}
                </div>
            </div>
            @if($tugas->file)
            <div class="p-3 bg-white rounded-4 border border-dashed d-flex align-items-center mt-3 shadow-sm">
                @php
                    $extension = pathinfo($tugas->file, PATHINFO_EXTENSION);
                    $iconClass = match(strtolower($extension)) {
                        'pdf' => 'bxs-file-pdf text-danger',
                        'doc', 'docx' => 'bxs-file-doc text-primary',
                        'xls', 'xlsx' => 'bxs-file-export text-success',
                        'zip', 'rar' => 'bxs-file-archive text-warning',
                        'jpg', 'jpeg', 'png' => 'bxs-file-image text-info',
                        default => 'bxs-file-blank text-secondary'
                    };
                @endphp
                <div class="icon-circle bg-light me-3 flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.4rem; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                    <i class='bx {{ $iconClass }}'></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-dark small text-truncate" title="{{ basename($tugas->file) }}">{{ basename($tugas->file) }}</div>
                    <small class="text-muted d-block" style="font-size: 0.75rem;">Materi dari Pembimbing ({{ strtoupper($extension) }})</small>
                </div>
                <a href="{{ asset('storage/'.$tugas->file) }}" target="_blank" class="btn btn-primary btn-sm px-3 rounded-pill ms-2 shadow-sm d-flex align-items-center gap-1 py-2 fw-semibold">
                    <i class='bx bx-download'></i> <span>Unduh</span>
                </a>
            </div>
            @endif
        </div>

        <form method="POST" action="{{ route('pesertapkl.tugas.kumpul', $tugas->uuid) }}" enctype="multipart/form-data">
        @csrf

            <!-- UPLOAD BOX -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">File Pekerjaan</label>
                <div class="upload-container" id="dropZone">
                    <input type="file" name="file" id="fileInput" class="d-none" required>
                    <div class="upload-content text-center py-5 px-3" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon mb-3">
                            <i class='bx bx-cloud-upload text-primary' style="font-size: 3.5rem;"></i>
                        </div>
                        <h6 class="fw-bold mb-1">Klik atau seret file ke sini</h6>
                        <p class="text-muted small mb-0">PDF, DOC, XLS, ZIP, JPG, PNG (Maks 5MB)</p>
                    </div>
                    <div id="fileInfo" class="d-none p-3 bg-primary bg-opacity-10 rounded-3 m-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-primary text-white me-3">
                                <i class='bx bxs-file'></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-bold text-dark text-truncate" id="fileName">Nama File.pdf</div>
                                <small class="text-secondary" id="fileSize">0 KB</small>
                            </div>
                            <button type="button" class="btn btn-link text-danger p-0" onclick="resetFile()">
                                <i class='bx bx-x fs-4'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CATATAN -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" class="form-control rounded-4 p-3" rows="4" placeholder="Tuliskan pesan atau catatan singkat untuk pembimbing..."></textarea>
            </div>

            <!-- ACTIONS -->
            <div class="d-grid d-md-flex gap-3">
                <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-4 shadow-sm order-md-2">
                    <i class='bx bx-send me-1'></i> Kumpulkan Sekarang
                </button>
                <a href="{{ route('pesertapkl.tugas.index') }}" class="btn btn-light px-5 py-3 fw-bold rounded-4 order-md-1">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<!-- ================= SIDEBAR INFO ================= -->
<div class="col-lg-4">
    
    <!-- TIPS -->
    <div class="card-soft mb-4 border-0" style="background: linear-gradient(145deg, #ffffff 0%, #f8faff 100%);">
        <div class="d-flex align-items-center mb-3">
            <div class="icon-circle bg-info bg-opacity-10 text-info me-2">
                <i class='bx bx-bulb'></i>
            </div>
            <h6 class="fw-bold mb-0">Tips Mengumpulkan</h6>
        </div>
        <ul class="list-unstyled mb-0">
            <li class="d-flex mb-3">
                <i class='bx bx-check-circle text-success me-2 mt-1'></i>
                <small class="text-secondary">Pastikan format file sesuai dengan yang diminta pembimbing.</small>
            </li>
            <li class="d-flex mb-3">
                <i class='bx bx-check-circle text-success me-2 mt-1'></i>
                <small class="text-secondary">Gunakan nama file yang jelas (Contoh: Tugas_Desain_Budi.pdf).</small>
            </li>
            <li class="d-flex mb-0">
                <i class='bx bx-check-circle text-success me-2 mt-1'></i>
                <small class="text-secondary">Jangan lupa tambahkan catatan jika ada kendala saat mengerjakan.</small>
            </li>
        </ul>
    </div>

    <!-- STATUS TUGAS -->
    <div class="card-soft">
        <div class="d-flex align-items-center mb-3">
            <div class="icon-circle bg-primary bg-opacity-10 text-primary me-2">
                <i class='bx bx-info-circle'></i>
            </div>
            <h6 class="fw-bold mb-0">Informasi Penting</h6>
        </div>
        <p class="text-secondary small mb-3">
            Tugas yang telah dikumpulkan akan langsung masuk ke dashboard pembimbing untuk dinilai. Anda masih dapat memperbarui file sebelum dinilai oleh pembimbing.
        </p>
        <div class="alert alert-warning border-0 rounded-4 mb-0 py-2">
            <div class="d-flex align-items-center">
                <i class='bx bx-error-circle fs-4 me-2'></i>
                <small class="fw-bold">Periksa kembali file Anda!</small>
            </div>
        </div>
    </div>

</div>

</div>

</div>



@endsection

@section('scripts')
<script>
const fileInput = document.getElementById('fileInput');
const fileInfo = document.getElementById('fileInfo');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const uploadContent = document.querySelector('.upload-content');
const dropZone = document.getElementById('dropZone');

fileInput.addEventListener('change', function() {
    updateFileInfo(this.files[0]);
});

function updateFileInfo(file) {
    if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
        fileInfo.classList.remove('d-none');
        uploadContent.classList.add('d-none');
    }
}

function resetFile() {
    fileInput.value = '';
    fileInfo.classList.add('d-none');
    uploadContent.classList.remove('d-none');
}

// Drag and Drop
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults (e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.add('border-primary');
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.remove('border-primary');
    }, false);
});

dropZone.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    fileInput.files = files;
    updateFileInfo(files[0]);
});
</script>
@endsection
