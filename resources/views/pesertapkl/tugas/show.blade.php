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
                    <h5 class="fw-bold mb-0 text-truncate">Detail & Update Tugas</h5>
                    <small class="text-muted text-truncate d-block">Kelola tugas yang telah kamu kumpulkan</small>
                </div>
            </div>
            <div>
                @if($pengumpulan)
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">
                    <i class='bx bx-check-circle'></i> Sudah Dikumpulkan
                </span>
                @else
                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-semibold">
                    <i class='bx bx-time-five'></i> Belum Dikumpulkan
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

<!-- ================= LEFT: TASK INFO ================= -->
<div class="col-lg-5">
    <div class="card-soft h-100">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                <i class='bx bx-info-circle'></i>
            </div>
            <h5 class="fw-bold mb-0">Informasi Tugas</h5>
        </div>

        <div class="mb-4">
            <label class="text-secondary small fw-bold mb-1">JUDUL TUGAS</label>
            <h5 class="fw-bold text-dark">{{ $tugas->judul }}</h5>
        </div>

        <div class="mb-4">
            <label class="text-secondary small fw-bold mb-1">DESKRIPSI</label>
            <div class="bg-light p-3 rounded-4 text-dark" style="line-height: 1.6; font-size: 0.9rem;">
                {{ $tugas->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="p-3 bg-light rounded-4">
                    <label class="text-secondary small fw-bold mb-1 d-block">DEADLINE</label>
                    <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}</span>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 bg-light rounded-4">
                    <label class="text-secondary small fw-bold mb-1 d-block">STATUS</label>
                    <span class="fw-bold {{ $pengumpulan ? 'text-success' : 'text-warning' }}">
                        {{ $pengumpulan ? 'Selesai' : 'Pending' }}
                    </span>
                </div>
            </div>
        </div>

        @if($tugas->file)
        <div class="mt-4 pt-3 border-top">
            <label class="text-secondary small fw-bold mb-2 d-block">FILE LAMPIRAN / MATERI</label>
            <div class="p-3 bg-light bg-opacity-50 rounded-4 border border-dashed d-flex align-items-center">
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
                <div class="icon-circle bg-white shadow-sm me-3 flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.4rem; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
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
        </div>
        @endif
    </div>
</div>

<!-- ================= RIGHT: SUBMISSION FORM ================= -->
<div class="col-lg-7">
    <div class="card-soft h-100">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                <i class='bx bx-cloud-upload'></i>
            </div>
            <h5 class="fw-bold mb-0">{{ $pengumpulan ? 'Update Pekerjaan' : 'Kumpulkan Pekerjaan' }}</h5>
        </div>

        <form action="{{ route('pesertapkl.tugas.kumpul',$tugas->uuid) }}" method="POST" enctype="multipart/form-data">
        @csrf

            <!-- CURRENT FILE INFO -->
            @if($pengumpulan && $pengumpulan->file)
            @php
                $submissionExtension = pathinfo($pengumpulan->file, PATHINFO_EXTENSION);
                $submissionIconClass = match(strtolower($submissionExtension)) {
                    'pdf' => 'bxs-file-pdf text-danger',
                    'doc', 'docx' => 'bxs-file-doc text-primary',
                    'xls', 'xlsx' => 'bxs-file-export text-success',
                    'zip', 'rar' => 'bxs-file-archive text-warning',
                    'jpg', 'jpeg', 'png' => 'bxs-file-image text-info',
                    default => 'bxs-file-blank text-secondary'
                };
            @endphp
            <div class="p-3 bg-success bg-opacity-10 rounded-4 border border-success border-opacity-20 d-flex align-items-center mb-4">
                <div class="icon-circle bg-white shadow-sm me-3 flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.4rem; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                    <i class='bx {{ $submissionIconClass }}'></i>
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-success small">Sudah Dikumpulkan:</div>
                    <div class="text-dark text-truncate small" title="{{ basename($pengumpulan->file) }}">{{ basename($pengumpulan->file) }}</div>
                </div>
                <a href="{{ asset('storage/'.$pengumpulan->file) }}" target="_blank" class="btn btn-success btn-sm px-3 rounded-pill ms-2 shadow-sm d-flex align-items-center gap-1 py-2 fw-semibold text-white">
                    <i class='bx bx-show'></i> <span>Lihat</span>
                </a>
            </div>
            @endif

            <!-- UPLOAD BOX -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">{{ $pengumpulan ? 'Ganti File (Opsional)' : 'Upload File' }}</label>
                <div class="upload-container" id="dropZone">
                    <input type="file" name="file" id="fileInput" class="d-none">
                    <div class="upload-content text-center py-5 px-3" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon mb-3">
                            <i class='bx bx-cloud-upload text-primary' style="font-size: 3.5rem;"></i>
                        </div>
                        <h6 class="fw-bold mb-1">Klik atau seret file baru ke sini</h6>
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
                <label class="form-label fw-bold text-dark">Catatan</label>
                <textarea name="catatan" class="form-control rounded-4 p-3" rows="4" placeholder="Update catatan pekerjaanmu...">{{ $pengumpulan->catatan ?? '' }}</textarea>
            </div>

            <!-- ACTIONS -->
            <div class="d-grid">
                <button type="submit" class="btn btn-success py-3 fw-bold rounded-4 shadow-sm">
                    <i class='bx bx-check-double me-1'></i> 
                    {{ $pengumpulan ? 'Simpan Perubahan' : 'Kumpulkan Tugas' }}
                </button>
            </div>

        </form>
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
if (dropZone) {
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
}
</script>
@endsection
