@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3 shadow-sm">
                    <i class='bx bx-user'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Manajemen Peserta PKL</h4>
                    <p class="text-secondary mb-0 small">Kelola seluruh data peserta, periode, dan status keaktifan mereka</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0 d-flex gap-2">
                <button
                    type="button"
                    class="btn btn-info text-white rounded-pill px-4 shadow-sm fw-bold py-2"
                    data-bs-toggle="modal"
                    data-bs-target="#modalStatistikTahunan">
                    <i class='bx bx-bar-chart-alt-2 me-1'></i> Statistik Peserta
                </button>
                <a href="{{ route('admin.pesertapkl.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm py-2">
                    <i class='bx bx-plus-circle me-1'></i> Tambah Peserta
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SUMMARY CARDS -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-3">
        <div class="card-soft h-100 p-4 shadow-sm border-0">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">Total</span>
                <i class='bx bx-layer fs-3 text-primary'></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $totalPeserta ?? 0 }}</h3>
            <p class="small text-secondary mb-0">Jumlah total peserta PKL</p>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card-soft h-100 p-4 shadow-sm border-0">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill">Pending</span>
                <i class='bx bx-time-five fs-3 text-warning'></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $pendingCount ?? 0 }}</h3>
            <p class="small text-secondary mb-0">Calon peserta dengan tanggal mulai di masa depan</p>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card-soft h-100 p-4 shadow-sm border-0">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Aktif</span>
                <i class='bx bx-check-circle fs-3 text-success'></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $aktifCount ?? 0 }}</h3>
            <p class="small text-secondary mb-0">Peserta yang sedang menjalani PKL</p>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="card-soft h-100 p-4 shadow-sm border-0">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">Selesai</span>
                <i class='bx bx-flag fs-3 text-secondary'></i>
            </div>
            <h3 class="fw-bold mb-1">{{ $selesaiCount ?? 0 }}</h3>
            <p class="small text-secondary mb-0">Peserta yang periode PKL-nya telah selesai</p>
        </div>
    </div>
</div>

@php
    $baseParams = request()->except(['status', 'page']);
@endphp

<!-- SEARCH & FILTER -->
<div class="card-soft mb-4 border-0 shadow-sm p-4">
    <form action="" method="GET">
        <!-- Keep current status when searching -->
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <div class="row g-3 align-items-end">
            <!-- SEARCH PART -->
            <div class="col-12 col-md-5">
                <label class="form-label fw-bold small text-secondary">CARI PESERTA PKL</label>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-modern flex-grow-1">
                        <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-search'></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Nama, Email, atau Sekolah..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold shadow-sm px-4">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('admin.pesertapkl.index', request()->except('search')) }}" class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;" title="Reset">
                            <i class='bx bx-refresh fs-4'></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- FILTER STATUS PART -->
            <div class="col-12 col-md-7">
                <label class="form-label fw-bold small text-secondary d-block">FILTER STATUS</label>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <a href="{{ route('admin.pesertapkl.index', array_merge($baseParams, ['status' => ''])) }}" class="btn btn-sm {{ empty(request('status')) ? 'btn-primary text-white' : 'btn-outline-secondary' }} rounded-pill px-3">Semua</a>
                    <a href="{{ route('admin.pesertapkl.index', array_merge($baseParams, ['status' => 'pending'])) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-secondary' }} rounded-pill px-3">Pending</a>
                    <a href="{{ route('admin.pesertapkl.index', array_merge($baseParams, ['status' => 'aktif'])) }}" class="btn btn-sm {{ request('status') == 'aktif' ? 'btn-success text-white' : 'btn-outline-secondary' }} rounded-pill px-3">Aktif</a>
                    <a href="{{ route('admin.pesertapkl.index', array_merge($baseParams, ['status' => 'selesai'])) }}" class="btn btn-sm {{ request('status') == 'selesai' ? 'btn-secondary text-white' : 'btn-outline-secondary' }} rounded-pill px-3">Selesai</a>
                    <a href="{{ route('admin.pesertapkl.index', array_merge($baseParams, ['status' => 'unassigned'])) }}" class="btn btn-sm {{ request('status') == 'unassigned' ? 'btn-danger text-white' : 'btn-outline-secondary' }} rounded-pill px-3">Belum Ditempatkan</a>

                    @if(request('status'))
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill ms-auto px-3 py-2 fw-bold" style="font-size: 0.7rem;">
                            Menampilkan: {{ request('status') == 'unassigned' ? 'BELUM DITEMPATKAN' : strtoupper(request('status')) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<!-- STUDENT GRID -->
<div class="row g-4">
    @forelse($pesertas as $peserta)
    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="card-soft h-100 border-0 shadow-sm hover-up transition-all overflow-hidden position-relative">
            <!-- STATUS BADGE -->
            <div class="position-absolute top-0 end-0 p-3">
                @php $status = $peserta->status_aktif; @endphp
                <span class="badge {{ $status=='aktif' ? 'bg-success' : ($status=='pending' ? 'bg-warning' : ($status=='selesai' ? 'bg-secondary' : 'bg-danger')) }} bg-opacity-10 {{ $status=='aktif' ? 'text-success' : ($status=='pending' ? 'text-warning' : ($status=='selesai' ? 'text-secondary' : 'text-danger')) }} rounded-pill small fw-bold" style="font-size: 0.65rem;">
                    {{ strtoupper($status) }}
                </span>
            </div>

            <div class="p-4 text-center">
                <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.5rem; border: 4px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    {{ strtoupper(substr($peserta->user->name ?? 'P',0,1)) }}
                </div>
                <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $peserta->user->name }}">{{ $peserta->user->name }}</h6>
                <p class="text-secondary small mb-3 text-truncate">{{ $peserta->user->email }}</p>

                <div class="p-2 bg-light rounded-4 mb-3">
                    <small class="text-secondary d-block mb-1 fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">INSTITUSI</small>
                    <div class="text-dark small fw-bold text-truncate px-2">{{ $peserta->asal_institusi ?? '-' }}</div>
                </div>

                <!-- DIVISI & PEMBIMBING INFO -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-2 bg-light bg-opacity-75 rounded-3 text-center h-100 border border-white">
                            <small class="text-secondary d-block fw-bold mb-1" style="font-size: 0.55rem; letter-spacing: 0.5px;">DIVISI</small>
                            @if($peserta->divisi)
                                <span class="small fw-bold text-dark text-truncate d-block" title="{{ $peserta->divisi->nama_divisi }}" style="max-width: 100%;">
                                    {{ $peserta->divisi->nama_divisi }}
                                </span>
                            @else
                                <span class="small fw-bold text-danger d-block">
                                    <i class='bx bx-error-circle me-1'></i>Belum Ada
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light bg-opacity-75 rounded-3 text-center h-100 border border-white">
                            <small class="text-secondary d-block fw-bold mb-1" style="font-size: 0.55rem; letter-spacing: 0.5px;">PEMBIMBING</small>
                            @if($peserta->pembimbing)
                                <span class="small fw-bold text-dark text-truncate d-block" title="{{ $peserta->pembimbing->nama }}" style="max-width: 100%;">
                                    {{ $peserta->pembimbing->nama }}
                                </span>
                            @else
                                <span class="small fw-bold text-danger d-block">
                                    <i class='bx bx-error-circle me-1'></i>Belum Ada
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-1 mb-4">
                    @if($peserta->tanggal_mulai && $peserta->tanggal_selesai)
                        <div class="badge bg-white text-secondary border rounded-pill small fw-normal px-3 py-1 shadow-xs">
                            <i class='bx bx-calendar me-1'></i>
                            {{ \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d M') }}
                        </div>
                    @endif
                </div>

                <!-- ACTION: ATUR PENEMPATAN -->
                @if(!$peserta->divisi_id || !$peserta->pembimbing_id)
                    <div class="mb-3">
                        <a href="{{ route('admin.pesertapkl.edit', $peserta->id) }}" class="btn btn-warning btn-sm w-100 rounded-pill fw-bold text-dark shadow-xs transition-all hover-up">
                            <i class='bx bx-user-plus me-1'></i> Atur Penempatan
                        </a>
                    </div>
                @endif

                <!-- ACTIONS -->
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.pesertapkl.show', $peserta->id) }}" class="btn btn-outline-success btn-sm rounded-pill flex-fill fw-bold" title="Detail">
                        <i class='bx bx-show'></i>
                    </a>
                    <a href="{{ route('admin.pesertapkl.edit', $peserta->id) }}" class="btn btn-outline-primary btn-sm rounded-pill flex-fill fw-bold" title="Edit">
                        <i class='bx bx-edit'></i>
                    </a>

                    <form action="{{ route('admin.pesertapkl.destroy', $peserta->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')" class="flex-fill">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm rounded-pill w-100 fw-bold" title="Hapus">
                            <i class='bx bx-trash'></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card-soft text-center py-5">
            <div class="mb-3">
                <i class='bx bx-user-x' style="font-size: 4rem; opacity: 0.1;"></i>
            </div>
            <h6 class="fw-bold">Tidak Menemukan Peserta</h6>
            <p class="text-muted small">Coba cari dengan kata kunci lain atau tambahkan peserta baru.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- PAGINATION -->
<div class="mt-5">
    {{ $pesertas->links() }}
</div>

</div>

<!-- ================= MODAL TAHUN ================= -->
<div class="modal fade" id="modalStatistikTahunan">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 text-white py-3 px-4" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center justify-content-center rounded-3" style="width:38px;height:38px;background:rgba(255,255,255,0.2);">
                        <i class='bx bx-bar-chart-alt-2 fs-5'></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Statistik Peserta Tahunan</h5>
                        <small class="opacity-75" id="labelTahun"></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background:#f8fafc;">
                <div id="listBulan" class="row g-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL BULAN ================= -->
<div class="modal fade" id="modalDetailBulan">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 text-white py-3 px-4" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center justify-content-center rounded-3" style="width:38px;height:38px;background:rgba(255,255,255,0.2);">
                        <i class='bx bx-calendar-event fs-5'></i>
                    </div>
                    <div>
                        <h5 id="judulBulan" class="mb-0 fw-bold">Detail Bulan</h5>
                        <small class="opacity-75">Rincian status peserta PKL</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background:#f8fafc;">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card-soft border-0 shadow-sm p-3 d-flex align-items-center justify-content-between h-100" style="border-left:4px solid #22c55e !important;">
                            <div>
                                <small class="text-secondary fw-bold d-block mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">AKTIF</small>
                                <h3 id="aktifBulan" class="fw-bold text-success mb-0">0</h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:44px;height:44px;background:rgba(34,197,94,0.1);">
                                <i class='bx bx-check-circle fs-4 text-success'></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-soft border-0 shadow-sm p-3 d-flex align-items-center justify-content-between h-100" style="border-left:4px solid #f59e0b !important;">
                            <div>
                                <small class="text-secondary fw-bold d-block mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">PENDING</small>
                                <h3 id="pendingBulan" class="fw-bold text-warning mb-0">0</h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:44px;height:44px;background:rgba(245,158,11,0.1);">
                                <i class='bx bx-time-five fs-4 text-warning'></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-soft border-0 shadow-sm p-3 d-flex align-items-center justify-content-between h-100" style="border-left:4px solid #6b7280 !important;">
                            <div>
                                <small class="text-secondary fw-bold d-block mb-1" style="font-size:0.7rem;letter-spacing:0.5px;">SELESAI</small>
                                <h3 id="selesaiBulan" class="fw-bold text-secondary mb-0">0</h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-center rounded-3" style="width:44px;height:44px;background:rgba(107,114,128,0.1);">
                                <i class='bx bx-flag fs-4 text-secondary'></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-soft border-0 shadow-sm p-3">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bulan-card {
    border: none !important;
    border-radius: 16px !important;
    transition: all 0.25s ease;
    overflow: hidden;
    position: relative;
}
.bulan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(59,130,246,0.15) !important;
}
.bulan-card .card-body {
    padding: 1.25rem !important;
}
.bulan-card .bulan-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 10px auto;
    font-size: 1.2rem;
}
.bulan-card .bulan-total {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.1;
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.bulan-card .bulan-nama {
    font-weight: 600;
    font-size: 0.85rem;
    color: #374151;
    margin-bottom: 4px;
}
.bulan-card .bulan-label {
    font-size: 0.7rem;
    color: #9ca3af;
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* FullCalendar override */
#modalDetailBulan .fc {
    font-size: 0.85rem;
}
#modalDetailBulan .fc .fc-toolbar-title {
    font-size: 1.1rem;
    font-weight: 700;
}
#modalDetailBulan .fc .fc-button-primary {
    background: #3b82f6;
    border-color: #3b82f6;
    border-radius: 8px;
    font-size: 0.8rem;
    padding: 4px 12px;
}
#modalDetailBulan .fc .fc-button-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
}
#modalDetailBulan .fc .fc-daygrid-day-number {
    font-weight: 600;
    font-size: 0.8rem;
}
#modalDetailBulan .fc .fc-event {
    border-radius: 6px;
    border: none;
    padding: 2px 6px;
    font-size: 0.75rem;
    font-weight: 500;
}
</style>

@endsection

@section('scripts')
<script>
document
.getElementById('modalStatistikTahunan')
.addEventListener('show.bs.modal', function(){
    const tahun = new Date().getFullYear();
    const el = document.getElementById('labelTahun');
    if(el) el.innerText = 'Tahun ' + tahun;

    fetch('/admin/pesertapkl/statistik/tahunan')
    .then(res => res.json())
    .then(data => {
        let html = '';
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        const colors = [
            {bg:'rgba(59,130,246,0.08)', icon:'text-primary'},
            {bg:'rgba(16,185,129,0.08)', icon:'text-success'},
            {bg:'rgba(245,158,11,0.08)', icon:'text-warning'},
            {bg:'rgba(239,68,68,0.08)',  icon:'text-danger'},
        ];

        data.forEach((item, i) => {
            const c = colors[i % colors.length];
            html += `
            <div class="col-6 col-md-3">
                <div class="card bulan-card shadow-sm"
                     data-bulan="${item.bulan}"
                     data-nama="${item.nama}"
                     style="cursor:pointer; background:#fff;">
                    <div class="card-body text-center">
                        <div class="bulan-icon" style="background:${c.bg}">
                            <i class='bx bx-calendar ${c.icon}'></i>
                        </div>
                        <div class="bulan-nama">${item.nama}</div>
                        <div class="bulan-total">${item.total}</div>
                        <div class="bulan-label">Peserta</div>
                    </div>
                </div>
            </div>`;
        });
        document.getElementById('listBulan').innerHTML = html;
    });
});

let calendar;
document.addEventListener('click', function(e){
    let card = e.target.closest('.bulan-card');
    if(!card) return;
    let bulan = card.dataset.bulan;
    let nama = card.dataset.nama;
    fetch(`/admin/pesertapkl/statistik/bulan/${bulan}`)
    .then(res => res.json())
    .then(data => {
        document.getElementById('judulBulan').innerText = 'Detail — ' + nama;
        document.getElementById('aktifBulan').innerText = data.aktif;
        document.getElementById('pendingBulan').innerText = data.pending;
        document.getElementById('selesaiBulan').innerText = data.selesai;
        const modal = new bootstrap.Modal(document.getElementById('modalDetailBulan'));
        modal.show();
        setTimeout(() => {
            const calendarEl = document.getElementById('calendar');
            if(calendar) calendar.destroy();

            const currentYear = new Date().getFullYear();
            const formattedMonth = String(bulan).padStart(2, '0');
            const initialDateStr = `${currentYear}-${formattedMonth}-01`;

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: initialDateStr,
                locale: 'id',
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'prev,next'
                },
                height: 'auto',
                events: data.events,
                eventClick: function(info){
                    Swal.fire({
                        icon: 'info',
                        title: info.event.title,
                        html: `
                            <div style="text-align:left;padding:10px 0;">
                                <div style="margin-bottom:8px;"><strong>Divisi:</strong> ${info.event.extendedProps.divisi}</div>
                                <div><strong>Status:</strong> ${info.event.extendedProps.status}</div>
                            </div>
                        `,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            });
            calendar.render();
        }, 300);
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

