@extends('layouts.pesertapkl')

@section('content')

<div class="container-fluid">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1"><i class='bx bx-envelope text-primary'></i> Ajukan Izin / Sakit</h4>
        <small class="text-secondary">Isi formulir ketidakhadiran dengan jelas</small>
    </div>
</div>

<div class="row g-4">

<!-- ================= FORM ================= -->
<div class="col-lg-8">

<div class="card-soft">

<form method="POST" action="{{route('pesertapkl.absen.izin')}}" enctype="multipart/form-data">
@csrf

<div class="row g-4">

<!-- TANGGAL -->
<div class="col-md-6">
    <label class="form-label fw-semibold text-secondary">Tanggal</label>
    <div class="input-group">
        <span class="input-group-text bg-light border-0"><i class='bx bx-calendar'></i></span>
        <input type="date" name="tanggal" class="form-control bg-light border-0" required>
    </div>
</div>

<!-- JENIS -->
<div class="col-md-6">
    <label class="form-label fw-semibold text-secondary">Jenis Pengajuan</label>
    <div class="input-group">
        <span class="input-group-text bg-light border-0"><i class='bx bx-list-ul'></i></span>
        <select name="jenis" class="form-select bg-light border-0" required>
            <option value="">Pilih Jenis</option>
            <option value="izin">Izin</option>
            <option value="sakit">Sakit</option>
        </select>
    </div>
</div>

</div>

<!-- ALASAN -->
<div class="mt-4">
    <label class="form-label fw-semibold text-secondary">Alasan Ketidakhadiran</label>
    <textarea name="alasan" class="form-control bg-light border-0" rows="4" placeholder="Tuliskan alasan secara detail dan jelas..." required></textarea>
</div>

<!-- FILE -->
<div class="mt-4">
    <label class="form-label fw-semibold text-secondary">Upload Bukti (Surat Dokter / Lampiran)</label>
    <div class="input-group">
        <span class="input-group-text bg-light border-0"><i class='bx bx-upload'></i></span>
        <input type="file" name="bukti" class="form-control bg-light border-0">
    </div>
    <small class="text-muted mt-1 d-block"><i class='bx bx-info-circle'></i> Opsional, format: JPG/PNG/PDF</small>
</div>

<!-- BUTTON -->
<div class="mt-5 d-flex gap-3">
    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold shadow-sm">
        <i class='bx bx-send'></i> Kirim Pengajuan
    </button>
    <a href="{{route('pesertapkl.absensi.index')}}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold">
        Batal
    </a>
</div>

</form>

</div>

</div>

<!-- ================= SIDE INFO ================= -->
<div class="col-lg-4">

<div class="card-soft mb-4">
    <h6 class="fw-bold mb-3 d-flex align-items-center"><i class='bx bx-info-circle text-info me-2'></i> Informasi</h6>
    <ul class="text-secondary ps-3 mb-0" style="line-height: 1.8;">
        <li>Isi data dengan benar dan jujur.</li>
        <li>Wajib upload bukti/surat keterangan jika memilih status <b>Sakit</b> (jika ada).</li>
        <li>Pengajuan Anda akan direview oleh Pembimbing untuk disetujui.</li>
    </ul>
</div>

<div class="card-soft border-start border-4 border-warning">
    <h6 class="fw-bold mb-2"><i class='bx bx-bulb text-warning'></i> Tips</h6>
    <p class="text-secondary mb-0 small">
        Gunakan bahasa yang sopan dan alasan yang logis agar pengajuan cepat disetujui oleh pembimbing Anda.
    </p>
</div>

</div>

</div>

</div>

@endsection
