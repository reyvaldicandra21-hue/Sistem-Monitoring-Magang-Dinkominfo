@extends('layouts.pesertapkl')

@section('content')

<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3">
                    <i class='bx bx-book-content'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Laporan Harian</h4>
                    <p class="text-secondary mb-0 small">Kelola dan pantau seluruh catatan kegiatan harian Anda</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('pesertapkl.laporanharian.create') }}" class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm">
                    <i class='bx bx-plus me-1'></i> Buat Laporan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- GRID -->
<div class="row g-4">
@forelse($laporans as $laporan)
<div class="col-md-6 col-lg-4">
    <div class="card-soft h-100 d-flex flex-column transition-hover" style="transition: transform 0.2s; border: 1px solid #f1f5f9;">
        <!-- HEADER -->
        <div class="d-flex gap-3 align-items-start mb-3">
            <div class="avatar-circle bg-light text-primary fw-bold" style="width: 45px; height: 45px; font-size: 1.2rem; flex-shrink: 0; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                {{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1 text-clamp-2" title="{{ $laporan->kegiatan }}" style="line-height: 1.5;">
                    {{ $laporan->kegiatan }}
                </h6>
                <small class="text-secondary d-flex align-items-center gap-1">
                    <i class='bx bx-calendar-alt'></i> {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}
                </small>
            </div>
        </div>

        <!-- STATUS -->
        <div class="mb-3">
            @if($laporan->status == 'disetujui')
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small fw-bold"><i class='bx bx-check-circle'></i> Disetujui</span>
            @elseif($laporan->status == 'revisi')
                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill small fw-bold"><i class='bx bx-error-circle'></i> Revisi</span>
            @else
                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill small fw-bold"><i class='bx bx-time-five'></i> Pending</span>
            @endif
        </div>

        <!-- CATATAN REVISI -->
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
        <div class="mb-3 p-2 bg-danger bg-opacity-10 rounded-3 small text-danger text-clamp-3 border-start border-3 border-danger">
            <strong>Catatan Revisi:</strong> {{ $cleanCatatan }}
        </div>
        @endif

        <!-- KENDALA -->
        @if($laporan->kendala)
        <div class="mb-3 p-2 bg-light rounded-3 small text-secondary text-clamp-3 border-start border-3 border-secondary">
            <strong>Kendala:</strong> {{ $laporan->kendala }}
        </div>
        @endif

        <!-- FOTO -->
        @if($laporan->dokumentasi->count())
        <div class="d-flex gap-2 mb-3 flex-wrap">
            @foreach($laporan->dokumentasi->take(4) as $foto)
            <a href="{{ asset('storage/'.$foto->file) }}" target="_blank">
                <img src="{{ asset('storage/'.$foto->file) }}" class="rounded-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
            </a>
            @endforeach

            <!-- jika lebih dari 4 -->
            @if($laporan->dokumentasi->count() > 4)
            <div class="rounded-3 bg-light text-secondary d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 50px; height: 50px; font-size: 0.8rem;">
                +{{ $laporan->dokumentasi->count() - 4 }}
            </div>
            @endif
        </div>
        @endif

        <div class="mt-auto pt-3 border-top d-flex gap-2">
            <a href="{{ route('pesertapkl.laporanharian.show', $laporan->uuid) }}" class="btn btn-light w-100 fw-semibold text-secondary small py-2">
                <i class='bx bx-detail'></i> Detail
            </a>

            @if($laporan->status == 'revisi')
            <a href="{{ route('pesertapkl.laporanharian.edit', $laporan->uuid) }}" class="btn btn-warning w-100 fw-semibold text-dark shadow-sm small py-2">
                <i class='bx bx-edit-alt'></i> Revisi
            </a>
            @endif
        </div>
    </div>
</div>
@empty
<div class="col-12 text-center py-5">
    <div class="text-muted">
        <i class='bx bx-book-open fs-1 mb-3 text-light'></i>
        <h5 class="fw-bold">Belum ada laporan</h5>
        <p>Anda belum membuat laporan harian apapun.</p>
        <a href="{{ route('pesertapkl.laporanharian.create') }}" class="btn btn-primary rounded-pill px-4 mt-2 fw-bold">
            Buat Laporan Pertama
        </a>
    </div>
</div>
@endforelse
</div>

</div>



@endsection
