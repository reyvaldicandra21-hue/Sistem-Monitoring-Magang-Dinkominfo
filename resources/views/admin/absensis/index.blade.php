@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3 shadow-sm">
                    <i class='bx bx-calendar-check'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Monitoring Presensi Peserta</h4>
                    <p class="text-secondary mb-0 small">Rekapitulasi kehadiran harian dan persentase partisipasi peserta PKL</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('admin.absensis.export') }}?bulan={{ $bulan }}" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm py-2">
                    <i class='bx bx-download me-1'></i> Export Rekap Excel
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card-soft mb-4 border-0 shadow-sm">
    <form action="" method="GET">
        <div class="row g-3 align-items-end justify-content-between">

            <!-- FILTER -->
            <div class="col-auto">
                <label class="form-label fw-bold small text-secondary">
                    PERIODE BULAN
                </label>

                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary">
                        <i class='bx bx-calendar'></i>
                    </span>

                    <input
                        type="month"
                        name="bulan"
                        class="form-control border-start-0 ps-0 fw-bold"
                        value="{{ $bulan }}"
                        onchange="this.form.submit()">
                </div>
            </div>


        </div>
    </form>
</div>
<!-- REKAP TABLE -->
<div class="card-soft p-0 overflow-hidden shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="small text-secondary fw-bold">
                    <th class="ps-4 py-3">PESERTA</th>
                    <th class="py-3 text-center">HADIR</th>
                    <th class="py-3 text-center">IZIN</th>
                    <th class="py-3 text-center">SAKIT</th>
                    <th class="py-3 text-center">ALPHA</th>
                    <th class="py-3 text-center">PARTISIPASI</th>
                    <th class="pe-4 py-3 text-end">DETAIL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesertas as $peserta)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold" style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($peserta->user->name ?? 'P',0,1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark small">{{ $peserta->user->name }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $peserta->user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex flex-column align-items-center">
                            <span class="text-success fw-bold">{{ $peserta->hadir }}</span>
                            @if($peserta->terlambat > 0)
                                <small class="text-warning fw-bold" style="font-size: 0.65rem;">+{{ $peserta->terlambat }} Telat</small>
                            @endif
                        </div>
                    </td>
                    <td class="text-center text-info fw-bold">
                        {{ $peserta->izin }}
                    </td>
                    <td class="text-center text-warning fw-bold">
                        {{ $peserta->sakit }}
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $peserta->alpha > 3 ? 'bg-danger' : 'bg-light text-danger' }} rounded-pill px-3 py-1 fw-bold">
                            {{ $peserta->alpha }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="progress rounded-pill bg-light" style="height: 10px; width: 100px; margin: 0 auto 5px auto;">
                            <div class="progress-bar {{ $peserta->persen >= 85 ? 'bg-success' : ($peserta->persen >= 70 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $peserta->persen }}%"></div>
                        </div>
                        <small class="fw-bold {{ $peserta->persen >= 85 ? 'text-success' : ($peserta->persen >= 70 ? 'text-warning' : 'text-danger') }}">{{ $peserta->persen }}%</small>
                    </td>
                    <td class="pe-4 text-end">
                        <button class="btn btn-outline-primary btn-sm rounded-pill fw-bold btn-calendar px-3"
                                data-id="{{ $peserta->id }}"
                                data-nama="{{ $peserta->user->name }}">
                            <i class='bx bx-calendar me-1'></i> Kalender
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted small">Belum ada data presensi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

<!-- ================= MODAL KALENDER ================= -->
<div class="modal fade" id="modalCalendar">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden rounded-4 shadow-lg">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class='bx bx-calendar fs-4'></i>
                    <h5 id="calTitle" class="mb-0 fw-bold"></h5>
                </div>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- HEADER HARI -->
                <div class="calendar-header mb-3">
                    <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
                </div>

                <!-- GRID TANGGAL -->
                <div id="calendarGrid" class="calendar-grid gap-2"></div>

                <!-- LEGEND -->
                <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top justify-content-center">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small">Hadir</span>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1 small">Izin</span>
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1 small">Sakit</span>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1 small">Alpha</span>
                    <span class="badge bg-light text-secondary rounded-pill px-2 py-1 small border">Belum</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal fade" id="modalDetail">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header bg-primary text-white border-0">
                <div class="d-flex align-items-center gap-2">
                    <i class='bx bx-info-circle fs-4'></i>
                    <div>
                        <h5 class="mb-0 fw-bold">Detail Absensi</h5>
                        <small id="dTanggal" class="opacity-75"></small>
                    </div>
                </div>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- KIRI -->
                    <div class="col-md-6">
                        <div class="d-flex flex-column gap-3">
                            <div class="p-3 bg-light rounded-4 border border-white">
                                <small class="text-secondary fw-bold d-block mb-1">STATUS KEHADIRAN</small>
                                <div id="dStatus"></div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4 border border-white text-center">
                                        <small class="text-secondary fw-bold d-block mb-1">MASUK</small>
                                        <div id="dMasuk" class="fw-bold text-dark fs-5">-</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-4 border border-white text-center">
                                        <small class="text-secondary fw-bold d-block mb-1">PULANG</small>
                                        <div id="dPulang" class="fw-bold text-dark fs-5">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 bg-light rounded-4 border border-white">
                                <small class="text-secondary fw-bold d-block mb-1">KETERANGAN / ALASAN</small>
                                <div id="dKeterangan" class="text-dark small">-</div>
                            </div>
                            <div class="p-3 bg-light rounded-4 border border-white">
                                <small class="text-secondary fw-bold d-block mb-1">KOORDINAT LOKASI</small>
                                <div id="dLokasi" class="text-dark small mb-2">-</div>
                                <div id="dMapBtn"></div>
                            </div>
                        </div>
                    </div>
                    <!-- KANAN -->
                    <div class="col-md-6 text-center">
                        <div class="p-2 bg-light rounded-4 border border-white h-100 d-flex flex-column align-items-center justify-content-center">
                            <small class="text-secondary fw-bold d-block mb-2">FOTO BUKTI / SELFIE</small>
                            <div id="dFotoArea" class="mt-2 w-100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
.icon-box {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: white;
}
.shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
.calendar-header {
    display: grid; grid-template-columns: repeat(7, 1fr);
    text-align: center; font-weight: bold; font-size: 0.75rem; color: #64748b;
}
.calendar-grid {
    display: grid; grid-template-columns: repeat(7, 1fr);
    text-align: center;
}
.day-box {
    aspect-ratio: 1; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-weight: bold; font-size: 0.85rem; cursor: pointer;
    transition: all 0.2s ease;
}
.day-box:hover { transform: scale(1.1); filter: brightness(0.9); }
.day-box.future { background: #f8fafc; color: #cbd5e1; border: 1px dashed #e2e8f0; }
.day-box.hadir { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
.day-box.terlambat { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); position: relative; }
.day-box.terlambat::after { content: ''; position: absolute; top: 4px; right: 4px; width: 6px; height: 6px; background: #f59e0b; border-radius: 50%; }
.day-box.izin { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
.day-box.sakit { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
.day-box.alpha { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
</style>

@endsection

@section('scripts')
<script>
let currentData = {};

document.addEventListener('click', function(e){
    if(e.target.closest('.btn-calendar')){
        let btn = e.target.closest('.btn-calendar');
        let id = btn.dataset.id;
        let nama = btn.dataset.nama;
        let bulan = "{{ $bulan }}";

        document.getElementById('calTitle').innerText = "Presensi - " + nama;

        fetch(`/admin/absensi/kalender/${id}/${bulan}`)
        .then(res => res.json())
        .then(data => {
            currentData = {};
            data.forEach(item => {
                let d = new Date(item.tanggal);
                let fixDate = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
                currentData[fixDate] = item;
            });

            let today = new Date(); today.setHours(0,0,0,0);
            let startDate = new Date("{{ $start->format('Y-m-d') }}");
            let firstDay = startDate.getDay();
            firstDay = (firstDay === 0) ? 6 : firstDay - 1; // Start from Monday

            let days = {{ $days }};
            let startBase = "{{ $start->format('Y-m') }}";
            let html = '';

            for(let i=0; i<firstDay; i++){ html += `<div></div>`; }

            for(let i=1; i<=days; i++){
                let date = startBase + '-' + String(i).padStart(2,'0');
                let d = new Date(date);
                let item = currentData[date];
                let statusClass = 'future';

                if(item){
                    statusClass = item.status;
                }else{
                    if(d.getTime() < today.getTime()){
                        statusClass = 'alpha';
                    }else{
                        statusClass = 'future';
                    }
                }

                html += `<div class="day-box ${statusClass}" data-id="${id}" data-tanggal="${date}">${i}</div>`;
            }

            document.getElementById('calendarGrid').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalCalendar')).show();
        });
    }
});

document.addEventListener('click', function(e){
    if(e.target.classList.contains('day-box')){
        let id = e.target.dataset.id;
        let tanggal = e.target.dataset.tanggal;

        fetch(`/admin/absensi/detail/${id}/${tanggal}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('dTanggal').innerText = moment(tanggal).format('dddd, D MMMM YYYY');

            if(!data){
                document.getElementById('dStatus').innerHTML = '<span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">ALPHA (TANPA KETERANGAN)</span>';
                document.getElementById('dMasuk').innerText = '-';
                document.getElementById('dPulang').innerText = '-';
                document.getElementById('dKeterangan').innerText = 'Tidak ada rekaman presensi pada tanggal ini.';
                document.getElementById('dFotoArea').innerHTML = '<i class="bx bx-image-alt fs-1 opacity-10"></i>';
                document.getElementById('dLokasi').innerText = 'Data GPS tidak tersedia';
                document.getElementById('dMapBtn').innerHTML = '';
            }else{
                let statusHtml = '';
                let ket = data.alasan ?? '-';

                if(data.status == 'hadir'){
                    statusHtml = '<span class="badge bg-success rounded-pill px-3 py-2 fw-bold">HADIR TEPAT WAKTU</span>';
                }else if(data.status == 'terlambat'){
                    statusHtml = '<span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-bold">HADIR (TERLAMBAT)</span>';
                }else if(data.status == 'izin'){
                    statusHtml = '<span class="badge bg-primary rounded-pill px-3 py-2 fw-bold">IZIN</span>';
                }else if(data.status == 'sakit'){
                    statusHtml = '<span class="badge bg-info rounded-pill px-3 py-2 fw-bold">SAKIT</span>';
                }else{
                    statusHtml = '<span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">ALPHA</span>';
                }

                document.getElementById('dStatus').innerHTML = statusHtml;
                document.getElementById('dMasuk').innerText = data.jam_masuk ?? '-';
                document.getElementById('dPulang').innerText = data.jam_pulang ?? '-';
                document.getElementById('dKeterangan').innerText = ket;

                let foto = '';
                if(data.foto || data.bukti){
                    let path = data.foto || data.bukti;
                    foto = `<img src="/storage/${path}" class="img-fluid rounded-4 shadow-sm" style="max-height:300px; object-fit:cover;">`;
                }else{
                    foto = '<i class="bx bx-image-alt fs-1 opacity-10"></i><p class="small text-muted">Foto tidak tersedia</p>';
                }
                document.getElementById('dFotoArea').innerHTML = foto;

                if(data.latitude && data.longitude){
                    let lat = data.latitude; let lng = data.longitude;
                    document.getElementById('dLokasi').innerText = `${lat}, ${lng}`;
                    document.getElementById('dMapBtn').innerHTML = `<a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" class="btn btn-primary w-100 rounded-pill fw-bold"><i class='bx bx-map-pin me-1'></i> Lihat Lokasi di Maps</a>`;
                }else{
                    document.getElementById('dLokasi').innerText = 'Data GPS tidak tersedia';
                    document.getElementById('dMapBtn').innerHTML = '';
                }
            }
            new bootstrap.Modal(document.getElementById('modalDetail')).show();
        });
    }
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/id.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
