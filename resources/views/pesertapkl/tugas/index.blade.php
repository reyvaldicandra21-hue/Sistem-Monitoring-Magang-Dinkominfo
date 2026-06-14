@extends('layouts.pesertapkl')

@section('content')

<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3">
                    <i class='bx bx-task'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Daftar Tugas</h4>
                    <p class="text-secondary mb-0 small">Pantau dan kerjakan tugas dari pembimbing Anda</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                    <i class='bx bx-info-circle'></i> {{ $tugas->count() }} Tugas Tersedia
                </span>
            </div>
        </div>
    </div>
</div>

<!-- GRID -->
<div class="row g-4">
@forelse($tugas as $item)

@php
    $deadline = \Carbon\Carbon::parse($item->deadline);
    $pengumpulan = $item->pengumpulan->where('peserta_pkl_id', $peserta->id)->first();
    $isSelesai = $pengumpulan ? true : false;
    $isTelat = !$isSelesai && $deadline->isPast();
    $isDekat = !$isSelesai && !$isTelat && $deadline->diffInDays(now()) <= 2;
@endphp

<div class="col-md-6 col-lg-4">
    <div class="card-soft h-100 d-flex flex-column transition-hover">
        
        <!-- STATUS BADGE TOP -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            @if($isSelesai)
                <span class="badge-status status-success">
                    <i class='bx bx-check-circle'></i> Selesai
                </span>
            @elseif($isTelat)
                <span class="badge-status status-danger">
                    <i class='bx bx-time-five'></i> Terlambat
                </span>
            @elseif($isDekat)
                <span class="badge-status status-warning">
                    <i class='bx bx-alarm-exclamation'></i> Deadline Dekat
                </span>
            @else
                <span class="badge-status status-info">
                    <i class='bx bx-hourglass'></i> Menunggu
                </span>
            @endif

            <small class="text-muted fw-medium">{{ $deadline->diffForHumans() }}</small>
        </div>

        <!-- CONTENT -->
        <div class="mb-3">
            <h5 class="fw-bold text-dark mb-2 text-clamp-2" title="{{ $item->judul }}">
                {{ $item->judul }}
            </h5>
            
            @if($item->deskripsi)
            <p class="text-secondary small text-clamp-3 mb-0" style="line-height: 1.6;">
                {{ $item->deskripsi }}
            </p>
            @endif
        </div>

        <div class="mt-auto pt-3">
            <!-- DEADLINE -->
            <div class="d-flex align-items-center text-dark fw-medium small mb-3">
                <i class='bx bx-calendar-event text-primary me-2 fs-5'></i>
                Tenggat: <span class="{{ $isTelat ? 'text-danger fw-bold ms-1' : 'ms-1' }}">{{ $deadline->format('d M Y') }}</span>
            </div>

            <!-- PROGRESS -->
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-secondary">Progress</small>
                <small class="fw-bold {{ $isSelesai ? 'text-success' : 'text-primary' }}">{{ $isSelesai ? '100%' : '0%' }}</small>
            </div>
            <div class="progress mb-4" style="height: 6px; border-radius: 10px; background-color: #f1f5f9;">
                <div class="progress-bar {{ $isSelesai ? 'bg-success' : 'bg-primary' }} rounded-pill" 
                     role="progressbar" 
                     style="width: {{ $isSelesai ? '100%' : '5%' }}" 
                     aria-valuenow="{{ $isSelesai ? '100' : '0' }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100"></div>
            </div>

            <!-- BUTTON -->
            @if(!$isSelesai)
                <a href="{{ route('pesertapkl.tugas.create', $item->uuid) }}" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                   <i class='bx bx-pencil'></i> Kerjakan Tugas
                </a>
            @else
                <a href="{{ route('pesertapkl.tugas.show', $item->uuid) }}" class="btn btn-outline-primary w-100 fw-bold py-2">
                   <i class='bx bx-detail'></i> Lihat Detail
                </a>
            @endif
        </div>

    </div>
</div>

@empty

<div class="col-12">
    <div class="card-soft py-5 text-center">
        <div class="text-muted">
            <div class="mb-3">
                <i class='bx bx-task-x' style="font-size: 4rem; opacity: 0.2;"></i>
            </div>
            <h5 class="fw-bold">Belum Ada Tugas</h5>
            <p class="mb-0">Tenang, belum ada tugas yang diberikan oleh pembimbing Anda.</p>
        </div>
    </div>
</div>

@endforelse
</div>

</div>



@endsection
