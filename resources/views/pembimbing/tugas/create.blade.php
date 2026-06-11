@extends('layouts.pembimbing')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('pembimbing.tugas.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Buat Tugas Baru</h5>
                    <small class="text-muted">Berikan instruksi penugasan kepada peserta</small>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
    <div class="fw-bold mb-2"><i class='bx bx-error-circle'></i> Mohon perbaiki kesalahan berikut:</div>
    <ul class="mb-0 small">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('pembimbing.tugas.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        <!-- LEFT: MAIN DETAILS -->
        <div class="col-lg-8">
            <div class="card-soft h-100 shadow-sm border-0">
                <div class="p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                            <i class='bx bx-edit-alt'></i>
                        </div>
                        <h6 class="fw-bold mb-0">Detail Penugasan</h6>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold small text-secondary mb-0">JUDUL TUGAS</label>
                            <small class="text-muted char-counter" data-target="judul">0 / 255</small>
                        </div>
                        <input type="text" name="judul" class="form-control rounded-3 p-3 shadow-sm border-light" placeholder="Contoh: Laporan Mingguan Minggu 1" maxlength="255" required>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold small text-secondary mb-0">DESKRIPSI / INSTRUKSI</label>
                            <small class="text-muted char-counter" data-target="deskripsi">0 / 10000</small>
                        </div>
                        <textarea name="deskripsi" class="form-control rounded-3 p-3 shadow-sm border-light" rows="10" placeholder="Tuliskan detail instruksi pengerjaan tugas di sini..." maxlength="10000" required></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: SETTINGS -->
        <div class="col-lg-4">
            <div class="row g-4">
                <!-- PARTICIPANTS -->
                <div class="col-12">
                    <div class="card-soft shadow-sm border-0">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                                    <i class='bx bx-group'></i>
                                </div>
                                <h6 class="fw-bold mb-0">Peserta</h6>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary">PILIH PENERIMA</label>
                                <select id="pesertaSelect" name="peserta[]" multiple required class="form-control">
                                    <option value="all">Pilih Semua Peserta</option>
                                    @foreach($peserta as $p)
                                        <option value="{{ $p->id }}">{{ $p->user->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted mt-2 d-block small">Tugas akan muncul di dashboard peserta yang dipilih.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DEADLINE & ATTACHMENT -->
                <div class="col-12">
                    <div class="card-soft shadow-sm border-0">
                        <div class="p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                                <h6 class="fw-bold mb-0">Pengaturan</h6>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary">DEADLINE</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-time-five'></i></span>
                                    <input type="date" name="deadline" class="form-control border-start-0" required>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold small text-secondary">FILE LAMPIRAN (OPSIONAL)</label>
                                <div class="upload-container text-center p-4 border-2 border-dashed rounded-4 transition-all" id="dropArea">
                                    <input type="file" name="file" id="fileInput" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.png">
                                    <div id="uploadContent">
                                        <i class='bx bx-cloud-upload text-primary mb-2' style="font-size: 2.5rem;"></i>
                                        <p class="small text-muted mb-0">Klik atau drag file ke sini</p>
                                    </div>
                                    <div id="fileInfo" class="d-none">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <i class='bx bxs-file text-primary fs-4'></i>
                                            <span class="small fw-bold text-dark text-truncate" id="fileName" style="max-width: 150px;">-</span>
                                            <button type="button" class="btn btn-sm text-danger p-0" onclick="removeFile()"><i class='bx bx-x fs-4'></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                        <i class='bx bx-paper-plane me-1'></i> Publikasikan Tugas
                    </button>
                    <a href="{{ route('pembimbing.tugas.index') }}" class="btn btn-link w-100 text-secondary text-decoration-none mt-2 small">Batal dan Kembali</a>
                </div>
            </div>
        </div>
    </div>
</form>

</div>

<style>
.icon-circle {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.upload-container {
    background: #f8fafc;
    border-color: #e2e8f0 !important;
    cursor: pointer;
}
.upload-container:hover {
    background: #f1f5f9;
    border-color: #3b82f6 !important;
}
.border-dashed {
    border-style: dashed !important;
}
.ts-control {
    border: 1px solid #dee2e6 !important;
    border-radius: 10px !important;
    padding: 10px !important;
}
</style>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // TomSelect
    let select = new TomSelect("#pesertaSelect", {
        plugins: ['remove_button'],
        placeholder: "Pilih peserta..."
    });

    select.on('change', function(values) {
        if(values.includes("all")) {
            let all = [];
            document.querySelectorAll('#pesertaSelect option').forEach(opt => {
                if(opt.value !== 'all'){
                    all.push(opt.value);
                }
            });
            select.setValue(all);
        }
    });

    // Character Counter
    document.querySelectorAll('[maxlength]').forEach(el => {
        const counter = document.querySelector(`.char-counter[data-target="${el.name}"]`);
        const max = el.getAttribute('maxlength');

        const updateCounter = () => {
            const length = el.value.length;
            if(counter) {
                counter.innerText = `${length} / ${max}`;
                if (length >= max) {
                    counter.classList.add('text-danger');
                    counter.classList.remove('text-muted');
                } else {
                    counter.classList.remove('text-danger');
                    counter.classList.add('text-muted');
                }
            }
        };

        el.addEventListener('input', updateCounter);
        updateCounter();
    });

    // File Upload Interaction
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const uploadContent = document.getElementById('uploadContent');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');

    dropArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            handleFiles(this.files[0]);
        }
    });

    function handleFiles(file) {
        fileName.innerText = file.name;
        uploadContent.classList.add('d-none');
        fileInfo.classList.remove('d-none');
    }

    window.removeFile = function() {
        fileInput.value = '';
        uploadContent.classList.remove('d-none');
        fileInfo.classList.add('d-none');
    }
});
</script>
@endsection
