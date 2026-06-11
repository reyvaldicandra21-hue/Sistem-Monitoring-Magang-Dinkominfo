@extends('layouts.pembimbing')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('pembimbing.laporanharian.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Detail Laporan Harian</h5>
                    <small class="text-muted">Informasi lengkap kegiatan dan verifikasi</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: REPORT CONTENT -->
    <div class="col-lg-8">
        <div class="card-soft p-4 p-md-5 mb-4 shadow-sm border-0 position-relative overflow-hidden">

            <div class="d-flex justify-content-between align-items-start mb-4 position-relative">
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 small fw-bold mb-2">LAPORAN KEGIATAN</span>
                    <h4 class="fw-bold text-dark mb-1">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('l, d F Y') }}</h4>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-circle bg-light text-primary fw-bold" style="width: 25px; height: 25px; font-size: 0.6rem;">
                            {{ strtoupper(substr($laporan->pesertaPkl->user->name ?? 'P',0,1)) }}
                        </div>
                        <span class="text-secondary small fw-bold">{{ $laporan->pesertaPkl->user->name }}</span>
                    </div>
                </div>
                <div class="text-end">
                    @php
                        $statusClass = [
                            'menunggu' => 'bg-warning text-warning',
                            'disetujui' => 'bg-success text-success',
                            'revisi' => 'bg-danger text-danger'
                        ];
                    @endphp
                    <span class="badge {{ $statusClass[$laporan->status] ?? 'bg-secondary text-secondary' }} bg-opacity-10 rounded-pill px-4 py-2 fw-bold">
                        {{ strtoupper($laporan->status) }}
                    </span>
                </div>
            </div>

            <hr class="my-4 opacity-10">

            <!-- MAIN CONTENT -->
            <div class="mb-4">
                <label class="fw-bold small text-secondary mb-2 d-flex align-items-center"><i class='bx bx-pencil me-1'></i> DESKRIPSI KEGIATAN</label>
                <div class="p-3 bg-light rounded-4 text-dark border border-white">
                    {!! nl2br(e($laporan->kegiatan)) !!}
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="fw-bold small text-secondary mb-2 d-flex align-items-center"><i class='bx bx-check-circle me-1'></i> HASIL CAPAIAN</label>
                    <div class="p-3 bg-light rounded-4 text-dark border border-white h-100">
                        {{ $laporan->hasil ?? 'Tidak ada hasil spesifik yang dicatat.' }}
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold small text-secondary mb-2 d-flex align-items-center"><i class='bx bx-error-circle me-1'></i> KENDALA</label>
                    <div class="p-3 bg-light rounded-4 text-dark border border-white h-100 {{ $laporan->kendala ? 'text-danger fw-medium' : '' }}">
                        {{ $laporan->kendala ?? 'Tidak ada kendala yang dilaporkan.' }}
                    </div>
                </div>
            </div>

            <!-- DOCUMENTATION -->
            <div>
                <label class="fw-bold small text-secondary mb-2 d-flex align-items-center"><i class='bx bx-image me-1'></i> DOKUMENTASI KEGIATAN</label>
                @if($laporan->dokumentasi->count())
                    <div class="row g-2">
                        @foreach($laporan->dokumentasi as $foto)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="img-box-modern rounded-4 overflow-hidden border cursor-pointer shadow-xs" onclick="previewImage('{{ asset('storage/'.$foto->file) }}')">
                                <img src="{{ asset('storage/'.$foto->file) }}" class="w-100 h-100 object-fit-cover">
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-light rounded-4 text-center text-muted border border-dashed border-2">
                        <i class='bx bx-image-alt fs-2 mb-1'></i>
                        <p class="small mb-0">Belum ada dokumentasi terlampir</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- VERIFICATION FORM -->
        @if($laporan->status == 'menunggu')
        <div class="card-soft p-4 shadow-sm border-0 bg-white">
            <h6 class="fw-bold mb-4 d-flex align-items-center"><i class='bx bx-check-shield text-primary me-2'></i> Form Verifikasi Pembimbing</h6>
            <form id="verifikasiForm" action="{{ route('pembimbing.laporanharian.verifikasi', $laporan->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold small text-secondary">CATATAN VERIFIKASI (WAJIB JIKA REVISI)</label>
                    <textarea name="catatan" class="form-control border-0 bg-light p-3 rounded-4 @error('catatan') is-invalid @enderror" rows="3" placeholder="Tulis masukan atau alasan revisi di sini...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <button type="submit" name="status" value="disetujui" class="btn btn-success w-100 rounded-pill fw-bold py-3 shadow-sm transition-all hover-up">
                            <i class='bx bx-check-double me-1'></i> Setujui Laporan
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="submit" name="status" value="revisi" class="btn btn-warning w-100 rounded-pill fw-bold py-3 shadow-sm transition-all hover-up">
                            <i class='bx bx-refresh me-1'></i> Minta Revisi
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @else
        <div class="alert alert-light border-0 rounded-4 p-4 d-flex align-items-center">
            <i class='bx bx-info-circle fs-3 text-primary me-3'></i>
            <div>
                <h6 class="fw-bold mb-1">Laporan ini sudah diverifikasi</h6>
                <p class="mb-0 small text-muted">Status laporan saat ini adalah <strong>{{ strtoupper($laporan->status) }}</strong>.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- RIGHT: HISTORY -->
    <div class="col-lg-4">
        <div class="card-soft p-4 h-100 shadow-sm border-0 overflow-hidden">
            <h6 class="fw-bold mb-4 d-flex align-items-center"><i class='bx bx-history text-primary me-2'></i> Riwayat Verifikasi</h6>
            
            <div class="timeline-modern">
                @forelse($laporan->verifikasis as $verifikasi)
                <div class="timeline-item-modern pb-4">
                    <div class="timeline-dot-modern {{ $verifikasi->status == 'disetujui' ? 'bg-success' : 'bg-danger' }}"></div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge {{ $verifikasi->status == 'disetujui' ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $verifikasi->status == 'disetujui' ? 'text-success' : 'text-danger' }} rounded-pill small fw-bold" style="font-size: 0.6rem;">
                            {{ strtoupper($verifikasi->status) }}
                        </span>
                        <small class="text-muted" style="font-size: 0.65rem;">{{ $verifikasi->created_at->format('d M, H:i') }}</small>
                    </div>
                    @php
                        $verifierName = 'Pembimbing';
                        $catatan = $verifikasi->catatan_pembimbing;
                        if ($catatan && str_starts_with($catatan, '[Verifikasi Admin]')) {
                            $verifierName = 'Admin';
                            $catatan = trim(str_replace('[Verifikasi Admin]', '', $catatan));
                        } elseif ($verifikasi->pembimbing) {
                            $verifierName = $verifikasi->pembimbing->nama;
                        }
                        if (empty($catatan)) {
                            $catatan = 'Tidak ada catatan tambahan.';
                        }
                    @endphp
                    <p class="small text-dark fw-bold mb-1">Verifikator: <span class="fw-normal text-secondary">{{ $verifierName }}</span></p>
                    <p class="small text-muted mb-2 lh-sm border-start ps-2">{{ $catatan }}</p>
                </div>
                @empty
                <div class="text-center py-5">
                    <div class="icon-circle bg-light text-muted mx-auto mb-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class='bx bx-info-circle fs-3'></i>
                    </div>
                    <p class="text-muted small">Belum ada riwayat verifikasi.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

</div>

<!-- PREVIEW MODAL -->
<div class="preview-modal" id="previewModal" onclick="this.style.display='none'">
    <div class="preview-content rounded-4 overflow-hidden shadow-lg animate__animated animate__zoomIn">
        <img id="previewImg" class="w-100">
    </div>
</div>

<style>
.img-box-modern { height: 120px; transition: all 0.3s ease; }
.img-box-modern:hover { transform: scale(1.05); }
.cursor-pointer { cursor: pointer; }
.shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
.hover-up:hover { transform: translateY(-3px); }
.transition-all { transition: all 0.3s ease; }

.timeline-modern { border-start: 2px solid #f1f5f9; padding-left: 20px; position: relative; }
.timeline-item-modern { position: relative; }
.timeline-dot-modern { 
    position: absolute; left: -27px; top: 5px; 
    width: 12px; height: 12px; border-radius: 50%;
    border: 3px solid white; box-shadow: 0 0 0 2px #f1f5f9;
}

.preview-modal {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(15, 23, 42, 0.9); display: none;
    align-items: center; justify-content: center; z-index: 9999; padding: 20px;
}
.preview-content { max-width: 800px; width: 100%; background: transparent; display: flex; justify-content: center; }
.preview-content img { max-height: 90vh; object-fit: contain; }
</style>

<script>
function previewImage(src){
    document.getElementById('previewModal').style.display = 'flex';
    document.getElementById('previewImg').src = src;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verifikasiForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitter = e.submitter;
            if (submitter && submitter.value === 'revisi') {
                const catatan = form.querySelector('textarea[name="catatan"]');
                if (!catatan.value.trim()) {
                    e.preventDefault();
                    catatan.classList.add('is-invalid');
                    
                    let feedback = form.querySelector('.invalid-feedback');
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback mt-2';
                        catatan.parentNode.appendChild(feedback);
                    }
                    feedback.textContent = 'Catatan revisi wajib diisi jika Anda memilih opsi Revisi.';
                    catatan.focus();
                } else {
                    catatan.classList.remove('is-invalid');
                }
            }
        });
    }
});
</script>
@endsection
