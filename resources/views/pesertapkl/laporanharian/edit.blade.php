@extends('layouts.pesertapkl')

@section('content')

<div class="container-fluid">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1"><i class='bx bx-edit-alt text-warning'></i> Revisi Laporan</h4>
        <small class="text-secondary">Perbaiki laporan sesuai dengan catatan pembimbing</small>
    </div>
    <a href="{{ route('pesertapkl.laporanharian.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
        <i class='bx bx-arrow-back'></i> Kembali
    </a>
</div>

<div class="row g-4">

<div class="col-lg-8">

@if($laporan->verifikasiTerakhir)
@php
    $cleanCatatan = $laporan->verifikasiTerakhir->catatan_pembimbing ?? '';
    if ($cleanCatatan && str_starts_with($cleanCatatan, '[Verifikasi Admin]')) {
        $cleanCatatan = trim(str_replace('[Verifikasi Admin]', '', $cleanCatatan));
    }
    if (empty($cleanCatatan)) {
        $cleanCatatan = 'Harap lakukan perbaikan laporan.';
    }
@endphp
<div class="alert alert-danger border-0 rounded-3 p-3 mb-4 d-flex align-items-start gap-2 shadow-xs">
    <i class='bx bx-error-circle fs-4 mt-1 text-danger'></i>
    <div>
        <h6 class="fw-bold mb-1 text-danger">Catatan Revisi dari Pembimbing/Admin</h6>
        <p class="mb-0 small text-secondary">
            "{{ $cleanCatatan }}"
        </p>
    </div>
</div>
@endif

<div class="card-soft">

<form action="{{ route('pesertapkl.laporanharian.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<!-- KEGIATAN -->
<div class="mb-4">
    <div class="d-flex justify-content-between">
        <label class="form-label fw-semibold text-secondary">Kegiatan Utama</label>
        <small class="text-muted char-counter" data-target="kegiatan">0 / 10000</small>
    </div>
    <textarea name="kegiatan" id="kegiatan" class="form-control bg-light border-0" rows="3" maxlength="10000" required>{{ old('kegiatan', $laporan->kegiatan) }}</textarea>
    @error('kegiatan')
        <small class="text-danger"><i class='bx bx-error-circle'></i> {{ $message }}</small>
    @enderror
</div>

<!-- HASIL -->
<div class="mb-4">
    <div class="d-flex justify-content-between">
        <label class="form-label fw-semibold text-secondary">Hasil Pekerjaan</label>
        <small class="text-muted char-counter" data-target="hasil">0 / 10000</small>
    </div>
    <textarea name="hasil" id="hasil" class="form-control bg-light border-0" rows="3" maxlength="10000">{{ old('hasil', $laporan->hasil) }}</textarea>
    @error('hasil')
        <small class="text-danger"><i class='bx bx-error-circle'></i> {{ $message }}</small>
    @enderror
</div>

<!-- KENDALA -->
<div class="mb-4">
    <div class="d-flex justify-content-between">
        <label class="form-label fw-semibold text-secondary">Kendala (Jika ada)</label>
        <small class="text-muted char-counter" data-target="kendala">0 / 5000</small>
    </div>
    <textarea name="kendala" id="kendala" class="form-control bg-light border-0" rows="2" maxlength="5000">{{ old('kendala', $laporan->kendala) }}</textarea>
    @error('kendala')
        <small class="text-danger"><i class='bx bx-error-circle'></i> {{ $message }}</small>
    @enderror
</div>

<!-- ================= DOKUMENTASI LAMA ================= -->
@if($laporan->dokumentasi->count())
<div class="mb-4 p-3 bg-light rounded-3">
    <label class="form-label fw-semibold text-secondary"><i class='bx bx-images'></i> Dokumentasi Sebelumnya</label>
    
    <div class="d-flex flex-wrap gap-3 mt-2">
        @foreach($laporan->dokumentasi as $foto)
        <div class="position-relative shadow-sm rounded-3 bg-white p-1">
            <img src="{{ asset('storage/'.$foto->file) }}" class="rounded-3" style="width: 80px; height: 80px; object-fit: cover;">
            
            <!-- CHECKBOX HAPUS -->
            <div class="position-absolute top-0 start-0 m-1 bg-white rounded-circle shadow-sm" style="padding: 2px;">
                <input type="checkbox" name="hapus_dokumentasi[]" value="{{ $foto->id }}" class="form-check-input m-0 border-danger" style="cursor: pointer;" title="Centang untuk menghapus">
            </div>
        </div>
        @endforeach
    </div>
    
    <small class="text-danger mt-2 d-block"><i class='bx bx-error'></i> Centang foto di atas jika Anda ingin menghapusnya.</small>
</div>
@endif

<!-- ================= TAMBAH FOTO ================= -->
<div class="mb-4">
    <label class="form-label fw-semibold text-secondary">Tambah Dokumentasi Baru</label>
    <div class="input-group">
        <span class="input-group-text bg-light border-0"><i class='bx bx-upload'></i></span>
        <input type="file" name="dokumentasi[]" id="uploadFoto" class="form-control bg-light border-0" multiple accept="image/*">
    </div>
    <small class="text-muted mt-1 d-block"><i class='bx bx-info-circle'></i> Maksimal 5 foto (2MB per foto)</small>

    <div id="previewFoto" class="mt-3 d-flex flex-wrap gap-2"></div>
</div>

<!-- BUTTON -->
<div class="mt-5">
    <button type="submit" class="btn btn-warning px-5 py-2 rounded-pill fw-bold text-dark shadow-sm w-100 w-md-auto">
        <i class='bx bx-refresh'></i> Kirim Revisi Laporan
    </button>
</div>

</form>

</div>
</div>

<!-- SIDE -->
<div class="col-lg-4">

<div class="card-soft mb-4">
    <h6 class="fw-bold mb-3 d-flex align-items-center"><i class='bx bx-target-lock text-warning me-2'></i> Tips Revisi</h6>
    <ul class="text-secondary ps-3 mb-0" style="line-height: 1.8;">
        <li>Perbaiki laporan <b>tepat</b> sesuai dengan catatan pembimbing.</li>
        <li>Perjelas hasil pekerjaan yang kurang dipahami pembimbing.</li>
        <li>Tambahkan dokumentasi baru jika bukti sebelumnya dianggap kurang.</li>
    </ul>
</div>

<div class="card-soft border-start border-4 border-info">
    <h6 class="fw-bold mb-2"><i class='bx bx-info-circle text-info'></i> Informasi Status</h6>
    <p class="text-secondary mb-0 small">
        Setelah direvisi dan dikirim, status laporan ini akan otomatis kembali menjadi <b>Pending (Menunggu)</b> untuk ditinjau ulang.
    </p>
</div>

</div>

</div>

</div>

<script>
    document.querySelectorAll('textarea[maxlength]').forEach(textarea => {
        const counter = document.querySelector(`.char-counter[data-target="${textarea.name}"]`);
        const max = textarea.getAttribute('maxlength');

        const updateCounter = () => {
            const length = textarea.value.length;
            counter.innerText = `${length} / ${max}`;
            if (length >= max) {
                counter.classList.add('text-danger');
                counter.classList.remove('text-muted');
            } else {
                counter.classList.remove('text-danger');
                counter.classList.add('text-muted');
            }
        };

        textarea.addEventListener('input', updateCounter);
        updateCounter(); // initial load
    });

    document.getElementById('uploadFoto').addEventListener('change', function(e){
        let container = document.getElementById('previewFoto');
        container.innerHTML = '';
        
        Array.from(e.target.files).forEach(file => {
            let reader = new FileReader();
            reader.onload = function(e){
                let img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '80px';
                img.style.height = '80px';
                img.style.objectFit = 'cover';
                img.classList.add('rounded-3', 'shadow-sm');
                container.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });
</script>

@endsection