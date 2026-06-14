@extends('layouts.pembimbing')

@section('content')



<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3">
                    <i class='bx bx-calendar-check'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Rekap Absensi Peserta</h4>
                    <p class="text-secondary mb-0 small">Pantau kehadiran dan kedisiplinan peserta PKL bimbingan Anda</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0 d-flex align-items-center gap-2">
                <label class="text-secondary small fw-bold d-none d-md-block">BULAN:</label>
                <input type="month"
                       value="{{ $bulan }}"
                       onchange="location='?bulan='+this.value"
                       class="form-control rounded-pill px-3 border-primary border-opacity-25" style="width: auto;">
            </div>
        </div>
    </div>
</div>

<!-- MINI CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-soft d-flex align-items-center justify-content-between p-3 border-0 shadow-sm">
            <div>
                <small class="text-secondary fw-bold mb-1 d-block">TOTAL PESERTA</small>
                <h4 class="fw-bold text-dark mb-0">{{ $totalPeserta }}</h4>
            </div>
            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                <i class='bx bx-group'></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-soft d-flex align-items-center justify-content-between p-3 border-0 shadow-sm">
            <div>
                <small class="text-secondary fw-bold mb-1 d-block">HADIR HARI INI</small>
                <h4 class="fw-bold text-success mb-0">{{ $hadirHariIni }}</h4>
            </div>
            <div class="icon-box bg-success bg-opacity-10 text-success">
                <i class='bx bx-check-circle'></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-soft d-flex align-items-center justify-content-between p-3 border-0 shadow-sm">
            <div>
                <small class="text-secondary fw-bold mb-1 d-block">IZIN/SAKIT</small>
                <h4 class="fw-bold text-warning mb-0">{{ $izinHariIni }}</h4>
            </div>
            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                <i class='bx bx-envelope'></i>
            </div>
        </div>
    </div>
</div>

<!-- REKAP TABLE -->
<div class="card-soft p-0 overflow-hidden border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small fw-bold" style="letter-spacing: 1px;">PESERTA</th>
                    <th class="text-center py-3 text-secondary small fw-bold">H</th>
                    <th class="text-center py-3 text-secondary small fw-bold">I</th>
                    <th class="text-center py-3 text-secondary small fw-bold">S</th>
                    <th class="text-center py-3 text-secondary small fw-bold">A</th>
                    <th class="text-center py-3 text-secondary small fw-bold">PROSENTASE</th>
                    <th class="text-center pe-4 py-3 text-secondary small fw-bold">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesertas as $peserta)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                {{ strtoupper(substr($peserta->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $peserta->user->name }}</div>
                                <small class="text-muted">{{ $peserta->divisi->nama_divisi ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center"><span class="fw-bold text-success">{{ $peserta->hadir }}</span></td>
                    <td class="text-center"><span class="fw-bold text-info">{{ $peserta->izin }}</span></td>
                    <td class="text-center"><span class="fw-bold text-primary">{{ $peserta->sakit }}</span></td>
                    <td class="text-center"><span class="fw-bold text-danger">{{ $peserta->alpha }}</span></td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <div class="progress flex-grow-1 d-none d-md-flex" style="height: 6px; width: 60px; border-radius: 10px; background: #f1f5f9;">
                                <div class="progress-bar {{ $peserta->persen >= 85 ? 'bg-success' : ($peserta->persen >= 70 ? 'bg-warning' : 'bg-danger') }}" 
                                     style="width: {{ $peserta->persen }}%"></div>
                            </div>
                            <span class="badge {{ $peserta->persen >= 85 ? 'bg-success' : ($peserta->persen >= 70 ? 'bg-warning text-dark' : 'bg-danger') }} rounded-pill" style="font-size: 0.75rem;">
                                {{ $peserta->persen }}%
                            </span>
                        </div>
                    </td>
                    <td class="text-center pe-4">
                        <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold btn-calendar"
                                data-id="{{ $peserta->uuid }}"
                                data-nama="{{ $peserta->user->name }}">
                            <i class='bx bx-calendar'></i> Kalender
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>

<!-- MODAL KALENDER -->
<div class="modal fade" id="modalCalendar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-dark mb-0" id="calTitle">Kalender Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="calendar-header mb-2 fw-bold text-primary small text-uppercase" style="letter-spacing: 1px;">
                    <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
                </div>
                <div id="calendarGrid" class="calendar-grid"></div>
                
                <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2 justify-content-center">
                    <small class="d-flex align-items-center gap-1"><span class="badge rounded-circle p-1 hadir" style="width: 10px; height: 10px;"> </span> Hadir</small>
                    <small class="d-flex align-items-center gap-1"><span class="badge rounded-circle p-1 izin" style="width: 10px; height: 10px;"> </span> Izin</small>
                    <small class="d-flex align-items-center gap-1"><span class="badge rounded-circle p-1 alpha" style="width: 10px; height: 10px;"> </span> Alpha</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #2563eb, #1e40af);">
                <div>
                    <h5 class="fw-bold mb-1">Detail Absensi</h5>
                    <div class="opacity-75 small" id="dTanggal">-- --- ----</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-4">
                    <!-- KIRI: INFO -->
                    <div class="col-md-6">
                        <div class="card-soft h-100 border-0 shadow-sm p-4">
                            <div class="mb-4 text-center text-md-start">
                                <label class="text-secondary small fw-bold mb-2 d-block">STATUS KEHADIRAN</label>
                                <div id="dStatus"></div>
                            </div>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="text-secondary small fw-bold mb-1 d-block">JAM MASUK</label>
                                    <div class="fw-bold text-dark fs-5" id="dMasuk">--:--</div>
                                </div>
                                <div class="col-6">
                                    <label class="text-secondary small fw-bold mb-1 d-block">JAM PULANG</label>
                                    <div class="fw-bold text-dark fs-5" id="dPulang">--:--</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="text-secondary small fw-bold mb-1 d-block">KETERANGAN / ALASAN</label>
                                <div class="bg-light p-3 rounded-3 small text-dark" id="dKeterangan" style="min-height: 50px;">-</div>
                            </div>

                            <div class="mb-4">
                                <label class="text-secondary small fw-bold mb-1 d-block"><i class='bx bx-map-pin text-danger'></i> LOKASI PRESENSI</label>
                                <div id="dMapBtn"></div>
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: FOTO -->
                    <div class="col-md-6">
                        <div class="card-soft h-100 border-0 shadow-sm p-4 d-flex flex-column align-items-center justify-content-center text-center">
                            <label class="text-secondary small fw-bold mb-3 d-block w-100">FOTO / BUKTI PRESENSI</label>
                            <div id="dFotoArea" class="w-100 rounded-4 overflow-hidden border shadow-sm bg-white" style="min-height: 200px; display: flex; align-items: center; justify-content: center;">
                                <i class='bx bx-image text-muted opacity-25' style="font-size: 5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>

let currentData = {};
// ================= KALENDER =================
document.addEventListener('click', function(e){

if(e.target.closest('.btn-calendar')){

let btn = e.target.closest('.btn-calendar');

let id = btn.dataset.id;
let nama = btn.dataset.nama;
let bulan = "{{ $bulan }}";

document.getElementById('calTitle').innerText = "Kalender - " + nama;

fetch(`/pembimbing/absensi/kalender/${id}/${bulan}`)
.then(res=>res.json())
.then(data=>{

let map = {};

data.forEach(item=>{
let d = new Date(item.tanggal);
let fix =
d.getFullYear()+'-'+
String(d.getMonth()+1).padStart(2,'0')+'-'+
String(d.getDate()).padStart(2,'0');

map[fix]=item;
});

let today = new Date();
today.setHours(0,0,0,0);

let days = {{ $days }};
let start = "{{ $start->format('Y-m') }}";

let html='';

for(let i=1;i<=days;i++){

let date = start+'-'+String(i).padStart(2,'0');
let d = new Date(date);
let item = map[date];

let status='future';

            if(item){
                status = (item.status === 'terlambat') ? 'hadir' : item.status;
            }else if(d < today){
status='alpha';
}

html += `<div class="day-box ${status}"
data-id="${id}"
data-tanggal="${date}">
${i}
</div>`;
}

document.getElementById('calendarGrid').innerHTML = html;

new bootstrap.Modal(document.getElementById('modalCalendar')).show();

});

}

});

// ================= DETAIL =================
document.addEventListener('click', function(e){

if(e.target.classList.contains('day-box')){

let id = e.target.dataset.id;
let tanggal = e.target.dataset.tanggal;

fetch(`/pembimbing/absensi/detail/${id}/${tanggal}`)
.then(res => res.json())
.then(data => {

document.getElementById('dTanggal').innerText = tanggal;

if(!data){

document.getElementById('dStatus').innerHTML =
'<span class="badge bg-danger">Alpha</span>';

document.getElementById('dMasuk').innerText = '-';
document.getElementById('dPulang').innerText = '-';
document.getElementById('dKeterangan').innerText = '-';
document.getElementById('dFotoArea').innerHTML = 'Tidak ada';
document.getElementById('dLokasi').innerText = '-';
document.getElementById('dMapBtn').innerHTML = '';

}else{

let statusHtml = '';

        if(data.status == 'hadir' || data.status == 'terlambat'){
            statusHtml = '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">Hadir</span>';
            if(data.status == 'terlambat') document.getElementById('dKeterangan').innerText = 'Hadir (Terlambat)';

        }else if(data.status == 'izin'){
            statusHtml = '<span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold">Izin</span>';
            document.getElementById('dKeterangan').innerText = data.alasan ?? '-';

        }else if(data.status == 'sakit'){
            statusHtml = '<span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill fw-bold">Sakit</span>';
            document.getElementById('dKeterangan').innerText = data.alasan ?? '-';

        }else{
            statusHtml = '<span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold">Alpha</span>';
        }

document.getElementById('dStatus').innerHTML = statusHtml;
document.getElementById('dMasuk').innerText = data.jam_masuk ?? '-';
document.getElementById('dPulang').innerText = data.jam_pulang ?? '-';

// FOTO
let foto = '';

if(data.foto){
foto = `<img src="/storage/${data.foto}" class="img-fluid rounded" style="max-height:250px">`;

}else if(data.bukti){
foto = `<img src="/storage/${data.bukti}" class="img-fluid rounded" style="max-height:250px">`;

}else{
foto = '<small class="text-muted">Tidak ada gambar</small>';
}

document.getElementById('dFotoArea').innerHTML = foto;

        // GPS
        if(data.latitude && data.longitude){
            let lat = data.latitude;
            let lng = data.longitude;

            document.getElementById('dMapBtn').innerHTML = `
                <a href="https://www.google.com/maps?q=${lat},${lng}"
                   target="_blank"
                   class="btn btn-primary btn-sm w-100 rounded-pill fw-bold">
                    <i class='bx bx-map-alt me-1'></i> Lihat di Google Maps
                </a>
            `;

        }else{
            document.getElementById('dMapBtn').innerHTML = '<div class="alert alert-light border-0 py-2 small text-muted text-center mb-0">Lokasi tidak tersedia</div>';
        }

}

new bootstrap.Modal(document.getElementById('modalDetail')).show();

});

}

});

</script>

<style>
.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}
.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    gap: 5px;
}
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 5px;
}
.day-box {
    aspect-ratio: 1/1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    background: #f8fafc;
    color: #64748b;
}
.day-box:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    z-index: 1;
}
.day-box.hadir { background: #dcfce7; color: #166534; }
.day-box.terlambat { background: #fef9c3; color: #854d0e; }
.day-box.izin { background: #ffedd5; color: #9a3412; }
.day-box.sakit { background: #e0f2fe; color: #075985; }
.day-box.alpha { background: #fee2e2; color: #991b1b; }
.day-box.future { opacity: 0.4; cursor: default; }

.badge.hadir { background-color: #dcfce7; }
.badge.terlambat { background-color: #fef9c3; }
.badge.izin { background-color: #ffedd5; }
.badge.alpha { background-color: #fee2e2; }

.table th {
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    color: #64748b;
}
</style>

@endsection
