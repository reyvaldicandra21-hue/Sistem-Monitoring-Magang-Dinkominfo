@extends('layouts.admin')

@section('title', 'Edit Peserta PKL')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.pesertapkl.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Edit Peserta PKL</h5>
                    <small class="text-muted">Perbarui data peserta: <strong>{{ $peserta->user->name ?? '-' }}</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card-soft p-4 p-md-5 shadow-sm border-0 position-relative overflow-hidden">

            @if($errors->any())
                <div class="alert alert-danger rounded-4 mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.pesertapkl.update', $peserta->uuid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 position-relative">
                    <!-- SECTION: AKUN -->
                    <div class="col-12 mb-2">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary" style="width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                <i class='bx bx-key'></i>
                            </div>
                            <h6 class="fw-bold mb-0">Informasi Akun</h6>
                        </div>
                        <hr class="mt-2 opacity-10">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">NAMA LENGKAP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-user'></i></span>
                            <input type="text" name="name" class="form-control border-start-0 p-2"
                                value="{{ old('name', $peserta->user->name ?? '') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">EMAIL AKTIF</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-envelope'></i></span>
                            <input type="email" name="email" class="form-control border-start-0 p-2"
                                value="{{ old('email', $peserta->user->email ?? '') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">PASSWORD BARU <small class="text-muted fw-normal">(Kosongkan jika tidak ingin mengubah)</small></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-lock-alt'></i></span>
                            <input type="password" name="password" class="form-control border-start-0 p-2" placeholder="Minimal 6 karakter">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">JENIS PESERTA</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-category'></i></span>
                            <select name="jenis" class="form-select border-start-0 p-2">
                                <option value="siswa" {{ old('jenis', $peserta->jenis) == 'siswa' ? 'selected' : '' }}>Siswa (SMK)</option>
                                <option value="mahasiswa" {{ old('jenis', $peserta->jenis) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa (PT)</option>
                            </select>
                        </div>
                    </div>

                    <!-- SECTION: AKADEMIK -->
                    <div class="col-12 mt-5 mb-2">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary" style="width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                <i class='bx bx-book-open'></i>
                            </div>
                            <h6 class="fw-bold mb-0">Informasi Akademik &amp; Periode</h6>
                        </div>
                        <hr class="mt-2 opacity-10">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">ASAL INSTITUSI</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-buildings'></i></span>
                            <input type="text" name="asal_institusi" class="form-control border-start-0 p-2"
                                value="{{ old('asal_institusi', $peserta->asal_institusi) }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">JURUSAN</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-brain'></i></span>
                            <input type="text" name="jurusan" class="form-control border-start-0 p-2"
                                value="{{ old('jurusan', $peserta->jurusan) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">TANGGAL MULAI PKL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-calendar-plus'></i></span>
                            <input type="date" name="tanggal_mulai" class="form-control border-start-0 p-2"
                                value="{{ old('tanggal_mulai', optional($peserta->tanggal_mulai)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">TANGGAL SELESAI PKL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-calendar-check'></i></span>
                            <input type="date" name="tanggal_selesai" class="form-control border-start-0 p-2"
                                value="{{ old('tanggal_selesai', optional($peserta->tanggal_selesai)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">NO. HP / WA</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-phone'></i></span>
                            <input type="text" name="no_hp" class="form-control border-start-0 p-2"
                                placeholder="08xxxxxxxxxx" value="{{ old('no_hp', $peserta->no_hp) }}">
                        </div>
                    </div>

                    <!-- SECTION: PENEMPATAN -->
                    <div class="col-12 mt-5 mb-2">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary" style="width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                                <i class='bx bx-map-pin'></i>
                            </div>
                            <h6 class="fw-bold mb-0">Informasi Penempatan &amp; Pembimbing</h6>
                        </div>
                        <hr class="mt-2 opacity-10">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">DIVISI PENEMPATAN</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-buildings'></i></span>
                            <select id="divisiSelect" name="divisi_id" class="form-select border-start-0 p-2">
                                <option value="">Pilih Divisi...</option>
                                @foreach($divisi as $d)
                                    <option value="{{ $d->id }}"
                                        {{ old('divisi_id', $peserta->divisi_id) == $d->id ? 'selected' : '' }}>
                                        {{ $d->nama_divisi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($peserta->historyDivisi && $peserta->historyDivisi->count() > 0)
                            <small class="text-warning mt-1 d-block" style="font-size:0.7rem;">
                                <i class='bx bx-history'></i> Perubahan divisi akan dicatat di riwayat peserta.
                            </small>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-secondary">PEMBIMBING INDUSTRI</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-user-voice'></i></span>
                            <select id="pembimbingSelect" name="pembimbing_id" class="form-select border-start-0 p-2">
                                <option value="">-- Pilih Divisi dahulu --</option>
                                @foreach($pembimbings as $p)
                                    <option
                                        value="{{ $p->id }}"
                                        data-divisi="{{ $p->divisi_id }}"
                                        {{ old('pembimbing_id', $peserta->pembimbing_id) == $p->id ? 'selected' : '' }}
                                        class="opt-pembimbing"
                                    >{{ $p->nama }}{{ $p->jabatan ? ' - '.$p->jabatan : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-muted mt-1 d-block" style="font-size:0.7rem;">Pilih divisi terlebih dahulu untuk menampilkan pembimbing yang tersedia.</small>
                    </div>

                    <div class="col-12 mt-5">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.pesertapkl.index') }}" class="btn btn-light rounded-pill px-5 fw-bold text-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm transition-all hover-up">
                                <i class='bx bx-save me-1'></i> Simpan Perubahan
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const divisiSel = document.getElementById('divisiSelect');
    const pembimbingSel = document.getElementById('pembimbingSelect');
    const allOptions = Array.from(pembimbingSel.querySelectorAll('.opt-pembimbing'));

    // Capture currently selected pembimbing_id (existing value from DB)
    const existingPembimbing = pembimbingSel.value;

    function filterPembimbing(selectedDivisiId) {
        pembimbingSel.innerHTML = '';

        if (!selectedDivisiId) {
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = '-- Pilih Divisi dahulu --';
            pembimbingSel.appendChild(placeholder);
            return;
        }

        const matched = allOptions.filter(opt => opt.dataset.divisi == selectedDivisiId);

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = matched.length > 0 ? 'Pilih Pembimbing...' : '-- Tidak ada pembimbing di divisi ini --';
        pembimbingSel.appendChild(placeholder);

        matched.forEach(opt => {
            const cloned = opt.cloneNode(true);
            pembimbingSel.appendChild(cloned);
        });
    }

    // Initial filter using the currently selected divisi
    filterPembimbing(divisiSel.value);

    // After filtering, restore selected pembimbing (if any)
    if (existingPembimbing) {
        setTimeout(() => {
            pembimbingSel.value = existingPembimbing;
        }, 50);
    }

    divisiSel.addEventListener('change', function() {
        filterPembimbing(this.value);
    });
});
</script>
@endsection
@endsection
