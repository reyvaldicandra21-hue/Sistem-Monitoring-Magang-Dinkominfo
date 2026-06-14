@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.pembimbing.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Edit Data Pembimbing</h5>
                    <small class="text-muted">Perbarui informasi profil dan penempatan pembimbing</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card-soft p-4 p-md-5 shadow-sm border-0 position-relative overflow-hidden">

            <form action="{{ route('admin.pembimbing.update', $pembimbing->uuid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 position-relative">
                    <div class="col-12 mb-2">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary" style="width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                <i class='bx bx-id-card'></i>
                            </div>
                            <h6 class="fw-bold mb-0">Informasi Profil & Akun</h6>
                        </div>
                        <hr class="mt-2 opacity-10">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">NAMA LENGKAP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-user'></i></span>
                            <input type="text" name="nama" class="form-control border-start-0 p-2" placeholder="Nama Lengkap" value="{{ old('nama', $pembimbing->nama) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">EMAIL KERJA</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-envelope'></i></span>
                            <input type="email" name="email" class="form-control border-start-0 p-2" placeholder="Email Pembimbing" value="{{ old('email', $pembimbing->user->email) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">PASSWORD BARU (OPSIONAL)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-lock-alt'></i></span>
                            <input type="password" name="password" class="form-control border-start-0 p-2" placeholder="Kosongkan jika tidak diubah">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">JABATAN / POSISI</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-briefcase'></i></span>
                            <input type="text" name="jabatan" class="form-control border-start-0 p-2" placeholder="Contoh: Senior Developer" value="{{ old('jabatan', $pembimbing->jabatan) }}">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold small text-secondary">DIVISI PENEMPATAN</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-buildings'></i></span>
                            <select name="divisi_id" class="form-select border-start-0 p-2 fw-bold text-dark">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisi as $d)
                                    <option value="{{ $d->id }}" {{ old('divisi_id', $pembimbing->divisi_id) == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.pembimbing.index') }}" class="btn btn-light rounded-pill px-5 fw-bold text-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm transition-all hover-up">
                                <i class='bx bx-save me-1'></i> Perbarui Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<style>
.hover-up:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.transition-all {
    transition: all 0.3s ease;
}
</style>
@endsection
