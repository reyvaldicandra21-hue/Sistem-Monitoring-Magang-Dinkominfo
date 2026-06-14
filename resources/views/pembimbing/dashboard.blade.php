@extends('layouts.pembimbing')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3 shadow-sm">
                    <i class='bx bx-home-alt'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Halo, Pembimbing 👋</h4>
                    <p class="text-secondary mb-0 small">Selamat datang kembali! Berikut ringkasan aktivitas peserta hari ini.</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <div class="badge bg-light text-dark px-3 py-2 rounded-pill shadow-sm border">
                    <i class='bx bx-calendar me-1 text-primary'></i> {{ date('d M Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MINI CARDS ================= -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-soft d-flex align-items-center justify-content-between p-4 border-0 shadow-sm hover-up transition-all">
            <div>
                <small class="text-secondary fw-bold mb-1 d-block">TOTAL PESERTA</small>
                <h3 class="fw-bold text-primary mb-0">{{ $totalPeserta }}</h3>
                <small class="text-primary fw-medium"><i class='bx bx-user-plus'></i> Bimbingan Aktif</small>
            </div>
            <div class="icon-circle bg-primary bg-opacity-10 text-primary" style="width: 50px; height: 50px; font-size: 1.5rem;">
                <i class='bx bx-group'></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-soft d-flex align-items-center justify-content-between p-4 border-0 shadow-sm hover-up transition-all">
            <div>
                <small class="text-secondary fw-bold mb-1 d-block">HADIR HARI INI</small>
                <h3 class="fw-bold text-success mb-0">{{ $hadir }}</h3>
                @php $percentHadir = $totalPeserta > 0 ? round(($hadir / $totalPeserta) * 100) : 0; @endphp
                <small class="text-success fw-medium"><i class='bx bx-trending-up'></i> {{ $percentHadir }}% Partisipasi</small>
            </div>
            <div class="icon-circle bg-success bg-opacity-10 text-success" style="width: 50px; height: 50px; font-size: 1.5rem;">
                <i class='bx bx-check-double'></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-soft d-flex align-items-center justify-content-between p-4 border-0 shadow-sm hover-up transition-all">
            <div>
                <small class="text-secondary fw-bold mb-1 d-block">TUGAS BERJALAN</small>
                <h3 class="fw-bold text-warning mb-0">{{ $tugas }}</h3>
                <small class="text-warning fw-medium"><i class='bx bx-time-five'></i> Dalam Progres</small>
            </div>
            <div class="icon-circle bg-warning bg-opacity-10 text-warning" style="width: 50px; height: 50px; font-size: 1.5rem;">
                <i class='bx bx-task'></i>
            </div>
        </div>
    </div>
</div>

<!-- ================= CHARTS & ACTIVITY ================= -->
<div class="row g-4">
    <!-- LEFT COLUMN -->
    <div class="col-lg-8">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card-soft h-100 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="fw-bold text-dark mb-0">Status Verifikasi Laporan</h6>
                        <i class='bx bx-pie-chart-alt text-primary fs-5'></i>
                    </div>
                    <div class="chart-box" style="height: 250px;">
                        <canvas id="chartLaporan"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-soft h-100 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="fw-bold text-dark mb-0">Tren Laporan (7 Hari)</h6>
                        <i class='bx bx-line-chart text-primary fs-5'></i>
                    </div>
                    <div class="chart-box" style="height: 250px;">
                        <canvas id="chartWeekly"></canvas>
                    </div>
                </div>
            </div>
            <!-- LATEST REPORTS -->
            <div class="col-12">
                <div class="card-soft shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="fw-bold text-dark mb-0">Laporan Terbaru Masuk</h6>
                        <a href="{{ route('pembimbing.laporanharian.index') }}" class="btn btn-white btn-sm px-2 rounded-circle shadow-sm"><i class='bx bx-chevron-right'></i></a>
                    </div>
                    <div class="scroll-area pe-2" style="max-height: 100px; overflow-y: auto;">
                        @forelse($laporan as $l)
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-4 border border-white hover-up transition-all cursor-pointer" onclick="window.location='{{ route('pembimbing.laporanharian.show', $l->uuid) }}'">
                            <div class="d-flex align-items-center gap-3 overflow-hidden">
                                <div class="avatar-circle bg-white text-primary fw-bold flex-shrink-0" style="width: 40px; height: 40px; font-size: 0.9rem; border: 2px solid #eef2f6;">
                                    {{ strtoupper(substr(optional($l->pesertaPkl->user)->name ?? 'P',0,1)) }}
                                </div>
                                <div class="overflow-hidden">
                                    <strong class="d-block text-truncate text-dark small mb-1">{{ optional($l->pesertaPkl->user)->name ?? '-' }}</strong>
                                    <small class="text-muted text-truncate d-block" style="font-size: 0.75rem;">
                                        {{ \Illuminate\Support\Str::limit($l->kegiatan, 50) }}
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-white text-primary border border-primary border-opacity-10 rounded-pill small d-none d-md-inline-block">{{ \Carbon\Carbon::parse($l->tanggal)->format('d/m/Y') }}</span>
                                <div class="text-primary fs-5">
                                    <i class='bx bx-right-top-arrow-circle'></i>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <p class="text-muted small">Tidak ada laporan terbaru</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="col-lg-4">
        <!-- ABSENSI STATUS -->
        <div class="card-soft mb-4 shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold text-dark mb-0">Status Absensi Hari Ini</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small px-3">Real-time</span>
            </div>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-4 border border-white shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-circle bg-primary text-white" style="width: 35px; height: 35px; font-size: 1rem;"><i class='bx bx-check'></i></div>
                        <span class="fw-bold text-dark small">Hadir</span>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3">{{ $hadir }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-4 border border-white shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-circle bg-warning text-white" style="width: 35px; height: 35px; font-size: 1rem;"><i class='bx bx-envelope'></i></div>
                        <span class="fw-bold text-dark small">Izin / Sakit</span>
                    </div>
                    <span class="badge bg-warning rounded-pill px-3">{{ $izin + $sakit }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-4 border border-white shadow-sm">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-circle bg-danger text-white" style="width: 35px; height: 35px; font-size: 1rem;"><i class='bx bx-x'></i></div>
                        <span class="fw-bold text-dark small">Belum Absen</span>
                    </div>
                    <span class="badge bg-danger rounded-pill px-3">{{ $belumAbsen }}</span>
                </div>
            </div>
        </div>

        <!-- PESERTA RINGKAS -->
        <div class="card-soft shadow-sm">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold text-dark mb-0">Peserta Bimbingan</h6>
                <a href="{{ route('pembimbing.peserta.index') }}" class="btn btn-white btn-sm px-2 rounded-circle shadow-sm"><i class='bx bx-chevron-right'></i></a>
            </div>
            <div class="scroll-area pe-2" style="max-height: 100px; overflow-y: auto;">
                @forelse($pesertaBimbingan as $p)
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded-4 border border-white hover-up transition-all cursor-pointer" onclick="window.location='{{ route('pembimbing.peserta.show', $p->uuid) }}'">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <div class="avatar-circle bg-white text-primary fw-bold flex-shrink-0" style="width: 35px; height: 35px; font-size: 0.8rem; border: 2px solid #eef2f6;">
                            {{ strtoupper(substr($p->user->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <strong class="d-block text-truncate small text-dark">{{ $p->user->name }}</strong>
                            <small class="text-muted text-truncate d-block" style="font-size: 0.65rem;">{{ $p->divisi->nama_divisi ?? '-' }}</small>
                        </div>
                    </div>
                    <div class="text-primary fs-5">
                        <i class='bx bx-right-top-arrow-circle'></i>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <p class="text-muted small">Belum ada peserta</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

</div>



@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartLaporan');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Menunggu','Disetujui','Revisi'],
        datasets: [{
            data: [{{ $menunggu }}, {{ $disetujui }}, {{ $revisi }}],
            backgroundColor: ['#f59e0b', '#3b82f6', '#ef4444'],
            borderWidth: 0,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { size: 11 } } }
        }
    }
});

const ctxWeekly = document.getElementById('chartWeekly');
const gradient = ctxWeekly.getContext('2d').createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

new Chart(ctxWeekly, {
    type: 'line',
    data: {
        labels: {!! json_encode($weeklyLabels) !!},
        datasets: [{
            label: 'Laporan Masuk',
            data: {!! json_encode($weeklyData) !!},
            borderColor: '#3b82f6',
            borderWidth: 3,
            backgroundColor: gradient,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3b82f6',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { stepSize: 1, font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});
</script>
@endsection
