@extends('layouts.pembimbing')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('pembimbing.peserta.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Detail Profil Peserta</h5>
                    <small class="text-muted">Informasi lengkap dan progres bimbingan</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: PROFILE CARD -->
    <div class="col-lg-4">
        <div class="card-soft text-center p-4 mb-4">
            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem; border: 5px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                {{ strtoupper(substr($peserta->user->name ?? 'P',0,1)) }}
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ $peserta->user->name }}</h5>
            <p class="text-secondary mb-4">{{ $peserta->user->email }}</p>
            
            <div class="d-grid gap-2">
                <div class="p-3 bg-light rounded-4 text-start">
                    <small class="text-secondary fw-bold d-block mb-1">DIVISI</small>
                    <div class="fw-bold text-primary"><i class='bx bx-briefcase-alt-2 me-1'></i> {{ $peserta->divisi->nama_divisi ?? '-' }}</div>
                </div>
                <div class="p-3 bg-light rounded-4 text-start">
                    <small class="text-secondary fw-bold d-block mb-1">TANGGAL PKL</small>
                    <div class="fw-bold text-dark small"><i class='bx bx-calendar-plus me-1 text-success'></i> {{ \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d M Y') }}</div>
                    <div class="fw-bold text-dark small mt-1"><i class='bx bx-calendar-check me-1 text-danger'></i> {{ \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        <div class="card-soft p-4">
            <h6 class="fw-bold mb-3 d-flex align-items-center">
                <i class='bx bx-info-circle text-primary me-2'></i> Informasi Akademik
            </h6>
            <div class="mb-3">
                <small class="text-secondary d-block">Institusi / Asal</small>
                <div class="fw-bold text-dark">{{ $peserta->asal_institusi }}</div>
            </div>
            <div class="mb-3">
                <small class="text-secondary d-block">Jurusan</small>
                <div class="fw-bold text-dark">{{ $peserta->jurusan }}</div>
            </div>
            <div class="mb-0">
                <small class="text-secondary d-block">Nomor HP / WhatsApp</small>
                <div class="fw-bold text-dark"><i class='bx bxl-whatsapp text-success me-1'></i> {{ $peserta->no_hp ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- RIGHT: ACTIVITY LOGS -->
    <div class="col-lg-8">
        <!-- STATS ROW -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card-soft p-3 border-0 shadow-sm text-center">
                    <h3 class="fw-bold text-primary mb-0">{{ $peserta->absensi->where('status', 'hadir')->count() }}</h3>
                    <small class="text-secondary fw-bold">TOTAL HADIR</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-soft p-3 border-0 shadow-sm text-center">
                    <h3 class="fw-bold text-success mb-0">{{ $peserta->laporanHarian->where('status', 'disetujui')->count() }}</h3>
                    <small class="text-secondary fw-bold">LAPORAN DISETUJUI</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-soft p-3 border-0 shadow-sm text-center">
                    <h3 class="fw-bold text-warning mb-0">{{ $peserta->laporanHarian->where('status', 'menunggu')->count() }}</h3>
                    <small class="text-secondary fw-bold">LAPORAN PENDING</small>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- ABSENSI LIST -->
            <div class="col-md-6">
                <div class="card-soft h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="fw-bold mb-0">Riwayat Absensi</h6>
                        <i class='bx bx-time-five text-primary fs-5'></i>
                    </div>
                    <div class="scroll-area pe-2" style="max-height: 400px; overflow-y: auto;">
                        @forelse($peserta->absensi as $a)
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-4 mb-2 border border-white">
                            <div>
                                <div class="fw-bold small text-dark">{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</div>
                                <small class="text-muted"><i class='bx bx-time me-1'></i>{{ $a->jam_masuk ?? '--:--' }}</small>
                            </div>
                            <span class="badge {{ $a->status == 'hadir' ? 'bg-success' : ($a->status == 'terlambat' ? 'bg-warning' : 'bg-danger') }} bg-opacity-10 {{ $a->status == 'hadir' ? 'text-success' : ($a->status == 'terlambat' ? 'text-warning' : 'text-danger') }} rounded-pill small fw-bold" style="font-size: 0.65rem;">
                                {{ strtoupper($a->status) }}
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <p class="text-muted small mb-0">Belum ada data absensi</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- LAPORAN LIST -->
            <div class="col-md-6">
                <div class="card-soft h-100">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="fw-bold mb-0">Laporan Harian</h6>
                        <i class='bx bx-book-content text-primary fs-5'></i>
                    </div>
                    <div class="scroll-area pe-2" style="max-height: 400px; overflow-y: auto;">
                        @forelse($peserta->laporanHarian as $l)
                        <div class="p-3 bg-light rounded-4 mb-2 border border-white position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <small class="fw-bold text-primary">{{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}</small>
                                <span class="badge {{ $l->status == 'disetujui' ? 'bg-success' : ($l->status == 'revisi' ? 'bg-danger' : 'bg-warning') }} bg-opacity-10 {{ $l->status == 'disetujui' ? 'text-success' : ($l->status == 'revisi' ? 'text-danger' : 'text-warning') }} rounded-pill small fw-bold" style="font-size: 0.6rem;">
                                    {{ strtoupper($l->status) }}
                                </span>
                            </div>
                            <div class="text-dark small text-truncate fw-medium">{{ $l->kegiatan }}</div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <p class="text-muted small mb-0">Belum ada laporan</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- TUGAS LIST -->
        <div class="card-soft mt-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0">Tugas yang Diberikan</h6>
                <i class='bx bx-task text-primary fs-5'></i>
            </div>
            <div class="row g-3">
                @forelse($tugas as $t)
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-4 border border-white">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="fw-bold text-dark text-truncate me-2" style="max-width: 150px;">{{ $t->judul }}</div>
                            <small class="text-danger fw-bold" style="font-size: 0.65rem;">
                                <i class='bx bx-calendar-event'></i> {{ \Carbon\Carbon::parse($t->deadline)->format('d M') }}
                            </small>
                        </div>
                        <p class="text-secondary small mb-0 text-truncate-2">{{ $t->deskripsi }}</p>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted small mb-0">Tidak ada tugas</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

</div>

<style>
.avatar-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.scroll-area::-webkit-scrollbar {
    width: 4px;
}
.scroll-area::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
</style>
@endsection
