@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-4 d-flex flex-column flex-md-row justify-content-between align-items-center shadow-sm border-0 position-relative overflow-hidden">
            <div class="d-flex align-items-center gap-3 position-relative">
                <div class="avatar-circle bg-primary text-white shadow-sm" style="width: 55px; height: 55px; font-size: 1.5rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-0">Selamat Datang, {{ auth()->user()->name }}!</h4>
                    <p class="text-secondary mb-0 small">Sistem Monitoring PKL - Panel Administrasi Utama</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0 position-relative">
                <span class="badge bg-light text-primary rounded-pill px-4 py-2 border shadow-xs fw-bold">
                    <i class='bx bx-calendar me-1'></i> {{ now()->translatedFormat('d F Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- BANNER PERINGATAN PENEMPATAN -->
<div class="row mb-4 d-none" id="unassignedAlert">
    <div class="col-12">
        <div class="card bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-4 p-3 shadow-xs">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-circle bg-warning text-dark fs-4" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class='bx bx-error-circle'></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Penempatan Belum Lengkap!</h6>
                        <p class="text-secondary mb-0 small">Ada <span id="unassignedCount" class="fw-bold text-danger">0</span> peserta yang belum mendapatkan Divisi atau Pembimbing.</p>
                    </div>
                </div>
                <a href="{{ route('admin.pesertapkl.index', ['status' => 'unassigned']) }}" class="btn btn-warning btn-sm text-dark fw-bold rounded-pill px-3 shadow-xs">
                    Atur Penempatan <i class='bx bx-right-arrow-alt align-middle ms-1'></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- STATS WIDGETS -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card-soft p-4 shadow-sm border-0 hover-up transition-all h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="icon-circle bg-info bg-opacity-10 text-info" style="width: 50px; height: 50px;">
                    <i class='bx bx-user-circle fs-4'></i>
                </div>
                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1 small fw-bold">AKTIF</span>
            </div>
            <h6 class="text-secondary fw-bold small mb-1">TOTAL PENGGUNA</h6>
            <h3 class="fw-bold text-dark mb-0" id="totalUser">0</h3>
            <div class="mt-2 text-muted small">Semua akun terdaftar</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-soft p-4 shadow-sm border-0 hover-up transition-all h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="icon-circle bg-warning bg-opacity-10 text-warning" style="width: 50px; height: 50px;">
                    <i class='bx bx-file fs-4'></i>
                </div>
                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1 small fw-bold">UPDATE</span>
            </div>
            <h6 class="text-secondary fw-bold small mb-1">LAPORAN MASUK</h6>
            <h3 class="fw-bold text-dark mb-0" id="totalLaporan">0</h3>
            <div class="mt-2 text-muted small">Total laporan harian</div>
        </div>
    </div>

<div class="col-md-4">
    <div class="card-soft p-4 shadow-sm border-0 hover-up transition-all h-100">

        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="icon-circle bg-primary bg-opacity-10 text-primary"
                 style="width:50px;height:50px;">
                <i class='bx bx-group fs-4'></i>
            </div>

            <span id="bulanSekarang"
                  class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 small fw-bold">
                BULAN INI
            </span>
        </div>

        <div class="row align-items-center">

            <!-- KIRI -->
            <div class="col-4">
                <h6 class="text-secondary fw-bold small mb-1">
                    PESERTA PKL
                </h6>

                <h2 class="fw-bold text-dark mb-0"
                    id="totalPesertaBulanan">
                    0
                </h2>

                <div class="mt-2 text-muted small">
                    Total bulan ini
                </div>
            </div>

            <!-- KANAN -->
            <div class="col-8">

                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-bold text-primary">
                            Aktif
                        </span>

                        <span id="aktifBulanan"
                              class="fw-bold text-dark">
                            0 Peserta
                        </span>
                    </div>

                    <div class="progress rounded-pill bg-light"
                         style="height:6px;">
                        <div id="aktifProgress"
                             class="progress-bar bg-primary">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-bold text-warning">
                            Pending
                        </span>

                        <span id="pendingBulanan"
                              class="fw-bold text-dark">
                            0 Peserta
                        </span>
                    </div>

                    <div class="progress rounded-pill bg-light"
                         style="height:6px;">
                        <div id="pendingProgress"
                             class="progress-bar bg-warning">
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
</div>
</div>

<!-- CHARTS ROW -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-soft p-4 shadow-sm border-0 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">Tren Aktivitas Laporan (7 Hari)</h6>
                <div class="icon-circle bg-light text-primary" style="width: 35px; height: 35px;"><i class='bx bx-trending-up'></i></div>
            </div>
            <div class="chart-container" style="height: 300px; position: relative;">
                <canvas id="chartWeekly"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-soft p-4 shadow-sm border-0 h-100">
            <h6 class="fw-bold mb-4">Statistik Kehadiran Hari Ini</h6>
            <div class="chart-container mb-4" style="height: 200px; position: relative;">
                <canvas id="chartAbsensi"></canvas>
            </div>
            <div id="absensiStats" class="row g-2 mt-auto">
                <!-- Data will be injected here -->
            </div>
        </div>
    </div>
</div>

<!-- DATA LISTS ROW -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-soft p-4 shadow-sm border-0 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">Peserta Baru (Minggu Ini)</h6>
                <a href="{{ route('admin.pesertapkl.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold border">Lihat Semua</a>
            </div>
            <div class="table-responsive scroll-area" style="max-height: 150px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light sticky-top" style="z-index: 1;">
                        <tr class="small text-secondary fw-bold">
                            <th class="ps-3 py-3 border-0">PESERTA</th>
                            <th class="py-3 border-0">DIVISI</th>
                            <th class="py-3 border-0">INSTITUSI</th>
                            <th class="pe-3 py-3 border-0 text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="listPesertaBottom">
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted small">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-soft p-4 shadow-sm border-0 h-100">
            <h6 class="fw-bold mb-4">Distribusi Divisi</h6>
            <div id="listDivisi" class="scroll-area pe-2" style="max-height: 150px; overflow-y: auto;">
                <small class="text-muted">Memuat data distribusi...</small>
            </div>
        </div>
    </div>
</div>

</div>



@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartAbsensi;
let chartWeekly;

function setText(id, value){
    const el = document.getElementById(id);
    if(el) el.innerText = value ?? 0;
}

function loadDashboard(){
    fetch('/admin/dashboard/data')
    .then(res => res.json())
    .then(data => {
        setText('totalUser', data.total_user);
        setText('totalPeserta', data.total_peserta);
        setText('totalLaporan', data.total_laporan);

        // UNASSIGNED ALERT BANNER
        const alertEl = document.getElementById('unassignedAlert');
        if (alertEl) {
            if (data.unassigned_count > 0) {
                alertEl.classList.remove('d-none');
                setText('unassignedCount', data.unassigned_count);
            } else {
                alertEl.classList.add('d-none');
            }
        }


        // CARD PESERTA BULAN INI
const totalBulanan =
    (data.monthly_current_active || 0) +
    (data.monthly_current_pending || 0);

const aktifPercent =
    totalBulanan > 0
        ? ((data.monthly_current_active || 0) / totalBulanan) * 100
        : 0;

const pendingPercent =
    totalBulanan > 0
        ? ((data.monthly_current_pending || 0) / totalBulanan) * 100
        : 0;

setText(
    'bulanSekarang',
    (data.monthly_current_label || 'Bulan Ini').toUpperCase()
);

setText('totalPesertaBulanan', totalBulanan);

setText(
    'aktifBulanan',
    `${data.monthly_current_active || 0} Peserta`
);

setText(
    'pendingBulanan',
    `${data.monthly_current_pending || 0} Peserta`
);

const aktifProgress = document.getElementById('aktifProgress');
if (aktifProgress) {
    aktifProgress.style.width = aktifPercent + '%';
}

const pendingProgress = document.getElementById('pendingProgress');
if (pendingProgress) {
    pendingProgress.style.width = pendingPercent + '%';
}

        // DIVISI LIST
        const listDivisi = document.getElementById('listDivisi');
        if(listDivisi && data.divisi_list){
            let divHtml = '';
            data.divisi_list.forEach(d => {
                let percent = data.total_peserta > 0 ? (d.jumlah / data.total_peserta * 100) : 0;
                divHtml += `
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1 fw-bold text-dark">
                        <span>${d.nama}</span>
                        <span>${d.jumlah} Peserta</span>
                    </div>
                    <div class="progress rounded-pill bg-light" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: ${percent}%"></div>
                    </div>
                </div>`;
            });
            listDivisi.innerHTML = divHtml || '<small class="text-muted">Belum ada data divisi</small>';
        }

        // PESERTA LIST
        const listPeserta = document.getElementById('listPesertaBottom');
        if(listPeserta && data.peserta_list){
            let pHtml = '';
            data.peserta_list.forEach(p => {
                pHtml += `
                <tr>
                    <td class="ps-3 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold" style="width: 30px; height: 30px; font-size: 0.7rem;">
                                ${p.nama ? p.nama.charAt(0) : 'P'}
                            </div>
                            <span class="fw-bold text-dark small">${p.nama}</span>
                        </div>
                    </td>
                    <td><small class="text-muted fw-bold">${p.divisi}</small></td>
                    <td><small class="text-secondary">${p.sekolah}</small></td>
                    <td class="pe-3 text-center"><span class="badge bg-success bg-opacity-10 text-success rounded-pill small fw-bold" style="font-size: 0.6rem;">AKTIF</span></td>
                </tr>`;
            });
            listPeserta.innerHTML = pHtml || '<tr><td colspan="4" class="text-center py-4 text-muted small">Tidak ada peserta baru dalam 7 hari terakhir.</td></tr>';
        }

        // CHART ABSENSI
        const ctxAbsensi = document.getElementById('chartAbsensi');
        if(ctxAbsensi){
            if(chartAbsensi) chartAbsensi.destroy();
            chartAbsensi = new Chart(ctxAbsensi, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir','Alpha','Izin','Sakit'],
                    datasets: [{
                        data: [data.hadir || 0, data.alpha || 0, data.izin || 0, data.sakit || 0],
                        backgroundColor: ['#007bff', '#ef4444', '#f59e0b', '#3b82f6'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { display: false } }
                }
            });

            // Stats Legend
            const statsGrid = document.getElementById('absensiStats');
            statsGrid.innerHTML = `
                <div class="col-6"><div class="p-2 bg-light rounded-3 text-center"><small class="text-secondary d-block fw-bold" style="font-size: 0.6rem;">HADIR</small><span class="fw-bold text-primary">${data.hadir || 0}</span></div></div>
                <div class="col-6"><div class="p-2 bg-light rounded-3 text-center"><small class="text-secondary d-block fw-bold" style="font-size: 0.6rem;">ALPHA</small><span class="fw-bold text-danger">${data.alpha || 0}</span></div></div>
                <div class="col-6"><div class="p-2 bg-light rounded-3 text-center"><small class="text-secondary d-block fw-bold" style="font-size: 0.6rem;">IZIN</small><span class="fw-bold text-warning">${data.izin || 0}</span></div></div>
                <div class="col-6"><div class="p-2 bg-light rounded-3 text-center"><small class="text-secondary d-block fw-bold" style="font-size: 0.6rem;">SAKIT</small><span class="fw-bold text-info">${data.sakit || 0}</span></div></div>
            `;
        }

        // CHART WEEKLY
        const ctxWeekly = document.getElementById('chartWeekly');
        if(ctxWeekly && data.chart_weekly){
            if(chartWeekly) chartWeekly.destroy();
            chartWeekly = new Chart(ctxWeekly, {
                type: 'line',
                data: {
                    labels: data.chart_weekly.labels,
                    datasets: [{
                        label: 'Laporan',
                        data: data.chart_weekly.data,
                        borderColor: '#3b82f6',
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
                            return gradient;
                        },
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false }, ticks: { stepSize: 1, color: '#94a3b8' } },
                        x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                    }
                }
            });
        }
    })
    .catch(err => console.error('ERROR DASHBOARD:', err));
}

loadDashboard();
setInterval(loadDashboard, 30000); // 30s
</script>
@endsection
