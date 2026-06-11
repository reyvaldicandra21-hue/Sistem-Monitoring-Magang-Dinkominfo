@extends('layouts.pesertapkl')

@section('content')

<div class="container-fluid">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1"><i class='bx bx-edit text-primary'></i> Buat Laporan</h4>
        <small class="text-secondary">Isi kegiatan harian kamu dengan detail</small>
    </div>
    <a href="{{ route('pesertapkl.laporanharian.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
        <i class='bx bx-arrow-back'></i> Kembali
    </a>
</div>

<div class="row g-4">

<!-- ================= FORM ================= -->
<div class="col-lg-8">

<div class="card-soft">

<form action="{{ route('pesertapkl.laporanharian.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<!-- KEGIATAN -->
<div class="mb-4">
    <div class="d-flex justify-content-between">
        <label class="form-label fw-semibold text-secondary">Kegiatan Utama</label>
        <small class="text-muted char-counter" data-target="kegiatan">0 / 10000</small>
    </div>
    <textarea name="kegiatan" id="kegiatan" class="form-control bg-light border-0" rows="3" placeholder="Contoh: Membuat desain database..." maxlength="10000" required>{{ old('kegiatan') }}</textarea>
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
    <textarea name="hasil" id="hasil" class="form-control bg-light border-0" rows="3" placeholder="Jelaskan hasil dari kegiatan di atas..." maxlength="10000">{{ old('hasil') }}</textarea>
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
    <textarea name="kendala" id="kendala" class="form-control bg-light border-0" rows="2" placeholder="Kesulitan yang dialami hari ini..." maxlength="5000">{{ old('kendala') }}</textarea>
    @error('kendala')
        <small class="text-danger"><i class='bx bx-error-circle'></i> {{ $message }}</small>
    @enderror
</div>

<!-- FOTO -->
<div class="mb-4">
    <label class="form-label fw-semibold text-secondary">Dokumentasi Kegiatan</label>
    <div class="input-group">
        <span class="input-group-text bg-light border-0"><i class='bx bx-images'></i></span>
        <input type="file" name="dokumentasi[]" id="uploadFoto" class="form-control bg-light border-0" multiple accept="image/*">
    </div>
    <small class="text-muted mt-1 d-block"><i class='bx bx-info-circle'></i> Maksimal 5 foto (2MB per foto)</small>

    <!-- PREVIEW -->
    <div id="previewFoto" class="mt-3 d-flex flex-wrap gap-2"></div>
</div>

<!-- BUTTON -->
<div class="mt-5">
    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold shadow-sm w-100 w-md-auto">
        <i class='bx bx-send'></i> Kirim Laporan
    </button>
</div>

</form>

</div>

</div>

<!-- ================= SIDE INFO ================= -->
<div class="col-lg-4">

<div class="card-soft mb-4">
    <h6 class="fw-bold mb-3 d-flex align-items-center"><i class='bx bx-bulb text-warning me-2'></i> Tips Laporan</h6>
    <ul class="text-secondary ps-3 mb-0" style="line-height: 1.8;">
        <li>Isi kolom kegiatan dengan jelas dan terstruktur.</li>
        <li>Jelaskan <b>hasil riil</b> dari pekerjaan Anda hari ini.</li>
        <li>Sertakan dokumentasi (foto/screenshot) sebagai bukti otentik.</li>
        <li>Tuliskan kendala agar pembimbing mengetahui hambatan Anda.</li>
    </ul>
</div>

<div class="card-soft border-start border-4 border-info">
    <h6 class="fw-bold mb-2"><i class='bx bx-info-circle text-info'></i> Informasi</h6>
    <p class="text-secondary mb-0 small">
        Laporan yang sudah dikirim akan masuk antrean untuk ditinjau dan disetujui oleh pembimbing Anda.
    </p>
</div>

</div>

</div>

</div>

@endsection

@section('scripts')
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

    // Preview Foto (Existing)
    document.getElementById('uploadFoto').addEventListener('change', function() {
        const preview = document.getElementById('previewFoto');
        preview.innerHTML = '';
        [...this.files].forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('rounded-3', 'shadow-sm');
                img.style.width = '80px';
                img.style.height = '80px';
                img.style.objectFit = 'cover';
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection