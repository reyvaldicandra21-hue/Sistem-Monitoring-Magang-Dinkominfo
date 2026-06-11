@extends('layouts.pesertapkl')

@section('title', 'Dashboard')

@section('content')




<div class="container-fluid">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Welcome, {{ $user->name }} 👋</h4>
</div>

<div class="row g-4">

<!-- LEFT COLUMN -->
<div class="col-lg-8">

    <!-- HERO STATUS BANNER -->
    <div class="hero-banner mb-4">
        <small class="text-muted d-block mb-1">Status Hari Ini</small>
        
        <h3 class="fw-bold mb-1">
            {{ $statusAbsensi }}
        </h3>
        
        <small class="text-muted d-block mb-3">
            <i class='bx bx-time-five align-middle'></i> Jam Masuk: {{ $jamMasuk ?? '-' }}
        </small>

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('pesertapkl.absensi.index') }}" class="btn btn-light rounded-pill px-4 fw-semibold shadow-sm">
                <i class='bx bx-camera align-middle me-1'></i> Absen Sekarang
            </a>

            <a href="{{ route('pesertapkl.laporanharian.index') }}" class="btn btn-outline-light rounded-pill px-4 fw-semibold">
                <i class='bx bx-edit-alt align-middle me-1'></i> Tulis Laporan
            </a>
        </div>
    </div>

    <!-- MINI CARDS (STATISTICS) -->
    <div class="row g-3 mb-4">

        <div class="col-md-4 col-6">
            <div class="card-soft card-mini">
                <div>
                    <small class="text-secondary fw-semibold">Laporan</small>
                    <h4 class="mb-0 fw-bold text-dark">{{ count($laporan ?? []) }}</h4>
                </div>
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class='bx bx-file fs-4'></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-6">
            <div class="card-soft card-mini">
                <div>
                    <small class="text-secondary fw-semibold">Tugas</small>
                    <h4 class="mb-0 fw-bold text-dark">{{ count($tugas ?? []) }}</h4>
                </div>
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class='bx bx-task fs-4'></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="card-soft card-mini">
                <div>
                    <small class="text-secondary fw-semibold">Status Laporan</small>
                    <h5 class="mb-0 fw-bold text-dark text-wrap-safe">{{ $statusLaporan ?? '-' }}</h5>
                </div>
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class='bx bx-check-circle fs-4'></i>
                </div>
            </div>
        </div>

    </div>

    <!-- LAPORAN TERBARU -->
    <div class="card-soft">
        <h6 class="fw-bold mb-3 d-flex align-items-center">
            <i class='bx bx-history text-primary me-2 fs-5'></i> Laporan Terbaru
        </h6>

        <div class="custom-scroll">
            @forelse($laporan ?? [] as $l)
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">

                <!-- LEFT -->
                <div class="d-flex gap-3 align-items-center">
                    <!-- AVATAR -->
                    <div class="avatar-circle bg-light text-primary fw-bold" style="width: 40px; height: 40px; font-size: 1.2rem;">
                        {{ strtoupper(substr($user->name ?? 'U',0,1)) }}
                    </div>

                    <!-- TEXT -->
                    <div style="max-width:250px;">
                        <strong class="d-block text-truncate text-dark" title="{{ $l->kegiatan ?? '-' }}">
                            {{ $l->kegiatan ?? '-' }}
                        </strong>
                        <small class="text-secondary">
                            <i class='bx bx-calendar-alt align-middle'></i> {{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}
                        </small>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="text-end">
                    @if($l->status == 'disetujui')
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Disetujui</span>
                    @elseif($l->status == 'revisi')
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Revisi</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Proses</span>
                    @endif
                </div>

            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class='bx bx-folder-open fs-1 mb-2 text-light'></i>
                <p class="mb-0">Belum ada laporan yang disubmit.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

<!-- RIGHT COLUMN -->
<div class="col-lg-4">

    <!-- TUGAS AKTIF -->
    <div class="card-soft d-flex flex-column" style="height: calc(100vh - 130px);">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">
                <i class='bx bx-notepad text-warning me-2 fs-5 align-middle'></i> Tugas Aktif
            </h6>
            <span class="badge bg-light text-dark">{{ count($tugas ?? []) }}</span>
        </div>

        <div class="mt-3 custom-scroll-tugas" style="flex: 1;">
            @forelse($tugas ?? [] as $t)
            <div class="task-card">
                <strong class="d-block text-dark mb-1">{{ $t->judul }}</strong>
                <small class="text-secondary d-flex align-items-center gap-1">
                    <i class='bx bx-time'></i> Deadline: <span class="fw-medium">{{ \Carbon\Carbon::parse($t->deadline)->format('d M Y') }}</span>
                </small>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class='bx bx-check-double fs-1 mb-2 text-light'></i>
                <p class="mb-0">Tidak ada tugas aktif saat ini.</p>
                <small>Bagus! Anda sudah menyelesaikan semuanya.</small>
            </div>
            @endforelse
        </div>
    </div>

</div>

</div>

</div>

@endsection