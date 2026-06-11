@extends('layouts.pesertapkl')

@section('content')

<div class="container-fluid">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1"><i class='bx bx-file text-primary'></i> Detail Laporan Harian</h4>
        <small class="text-secondary">Lihat rincian laporan yang telah Anda buat</small>
    </div>
    <a href="{{ route('pesertapkl.laporanharian.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
        <i class='bx bx-arrow-back'></i> Kembali
    </a>
</div>

<div class="row g-4">

<!-- ================= LEFT ================= -->
<div class="col-lg-8">

    @if($laporan->status == 'revisi' && $laporan->verifikasiTerakhir)
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
            <h6 class="fw-bold mb-1 text-danger">Laporan Butuh Revisi</h6>
            <p class="mb-2 small text-secondary">
                Catatan revisi: <strong>{{ $cleanCatatan }}</strong>
            </p>
            <a href="{{ route('pesertapkl.laporanharian.edit', $laporan->id) }}" class="btn btn-warning btn-sm fw-bold rounded-pill text-dark px-3 shadow-xs">
                <i class='bx bx-edit-alt'></i> Perbaiki Sekarang
            </a>
        </div>
    </div>
    @endif

    <!-- INFORMASI -->
    <div class="card-soft mb-4 position-relative overflow-hidden">
        <!-- Decoration element -->
        <div class="position-absolute top-0 start-0 w-100 bg-primary" style="height: 5px;"></div>

        <div class="row mb-4 pt-2">
            <div class="col-md-6 mb-3 mb-md-0">
                <small class="text-secondary fw-semibold"><i class='bx bx-calendar'></i> Tanggal Laporan</small>
                <div class="fw-bold fs-5 text-dark mt-1">
                    {{ \Carbon\Carbon::parse($laporan->tanggal)->format('l, d F Y') }}
                </div>
            </div>

            <div class="col-md-6">
                <small class="text-secondary fw-semibold"><i class='bx bx-info-circle'></i> Status Persetujuan</small>
                <div class="mt-2">
                    @if($laporan->status == 'menunggu')
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fs-6"><i class='bx bx-time-five'></i> Menunggu Review</span>
                    @elseif($laporan->status == 'disetujui')
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fs-6"><i class='bx bx-check-circle'></i> Disetujui</span>
                    @else
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fs-6"><i class='bx bx-error-circle'></i> Butuh Revisi</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-top pt-4">
            <!-- KEGIATAN -->
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2"><i class='bx bx-task text-primary me-1'></i> Kegiatan Utama</h6>
                <div class="p-3 bg-light rounded-3 text-secondary" style="line-height: 1.6;">
                    {!! nl2br(e($laporan->kegiatan)) !!}
                </div>
            </div>

            <!-- HASIL (🔥 BARU) -->
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2"><i class='bx bx-check-double text-success me-1'></i> Hasil Pekerjaan</h6>
                <div class="p-3 bg-light rounded-3 text-secondary" style="line-height: 1.6;">
                    {!! nl2br(e($laporan->hasil ?? 'Tidak ada catatan hasil.')) !!}
                </div>
            </div>

            <!-- KENDALA -->
            <div>
                <h6 class="fw-bold text-dark mb-2"><i class='bx bx-error text-danger me-1'></i> Kendala yang Dialami</h6>
                <div class="p-3 bg-light rounded-3 text-secondary border-start border-4 border-danger" style="line-height: 1.6;">
                    {!! nl2br(e($laporan->kendala ?? 'Tidak ada kendala.')) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- ================= DOKUMENTASI ================= -->
    <div class="card-soft">
        <h6 class="fw-bold text-dark mb-3"><i class='bx bx-images text-info me-1'></i> Bukti Dokumentasi</h6>

        @if($laporan->dokumentasi->count())
        <div class="d-flex flex-wrap gap-3">
            @foreach($laporan->dokumentasi as $doc)
            <div class="position-relative overflow-hidden rounded-3 shadow-sm" style="cursor: pointer; width: 120px; height: 120px;" onclick="previewImage('{{ asset('storage/'.$doc->file) }}')">
                <img src="{{ asset('storage/'.$doc->file) }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                <div class="position-absolute bottom-0 start-0 w-100 p-2 bg-dark bg-opacity-50 text-white text-center" style="font-size: 0.7rem;">
                    <i class='bx bx-search-alt'></i> Perbesar
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-4 bg-light rounded-3 border-dashed">
            <i class='bx bx-image-alt fs-1 text-muted mb-2'></i>
            <p class="text-secondary mb-0">Tidak ada dokumentasi yang dilampirkan.</p>
        </div>
        @endif
    </div>

</div>

<!-- ================= RIGHT ================= -->
<div class="col-lg-4">

    <div class="card-soft h-100">
        <h6 class="fw-bold text-dark mb-4"><i class='bx bx-history text-secondary me-1'></i> Log Verifikasi Pembimbing</h6>

        <div class="timeline position-relative ps-3 border-start border-2 border-light">
            @forelse($laporan->verifikasis->sortByDesc('created_at') as $verifikasi)
            <div class="mb-4 position-relative">
                <!-- Timeline Dot -->
                <div class="position-absolute bg-white border border-2 rounded-circle {{ $verifikasi->status == 'disetujui' ? 'border-success' : 'border-danger' }}" style="width: 14px; height: 14px; left: -24px; top: 4px;"></div>
                
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        @if($verifikasi->status == 'disetujui')
                            <span class="badge bg-success text-white px-2 py-1 rounded-pill small">Disetujui</span>
                        @else
                            <span class="badge bg-danger text-white px-2 py-1 rounded-pill small">Direvisi</span>
                        @endif
                    </div>
                    <small class="text-muted"><i class='bx bx-time'></i> {{ $verifikasi->created_at->diffForHumans() }}</small>
                </div>

                <div class="p-3 bg-light rounded-3 small text-secondary">
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
                    <div class="mb-1">
                        <strong class="text-dark">Verifikator:</strong> <span class="text-dark fw-semibold">{{ $verifierName }}</span>
                    </div>
                    <strong class="d-block mb-1 text-dark">Catatan:</strong>
                    {{ $catatan }}
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class='bx bx-message-square-dots fs-1 mb-2 text-light'></i>
                <p class="mb-0">Belum ada tanggapan atau catatan dari pembimbing.</p>
            </div>
            @endforelse
        </div>

    </div>

</div>

</div>

</div>

<!-- ================= MODAL PREVIEW ================= -->


<div id="imgPreviewModal" onclick="this.style.display='none'">
    <div class="position-absolute top-0 end-0 m-4 text-white" style="font-size: 2rem; cursor: pointer;">&times;</div>
    <img id="previewImg">
</div>

<script>
function previewImage(src){
    document.getElementById('previewImg').src = src;
    document.getElementById('imgPreviewModal').style.display = 'flex';
}
</script>

@endsection