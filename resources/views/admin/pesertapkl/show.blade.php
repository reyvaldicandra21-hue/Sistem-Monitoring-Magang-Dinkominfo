@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.pesertapkl.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Detail Profil Peserta</h5>
                    <small class="text-muted">Informasi lengkap dan status penempatan peserta</small>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.pesertapkl.edit', $pesertapkl->uuid) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class='bx bx-edit-alt me-1'></i> Edit Profil
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: PROFILE CARD -->
    <div class="col-lg-4">
        <div class="card-soft text-center p-4 mb-4">
            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem; border: 5px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                {{ strtoupper(substr($pesertapkl->user->name ?? 'P',0,1)) }}
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ $pesertapkl->user->name }}</h5>
            <p class="text-secondary mb-3">{{ $pesertapkl->user->email }}</p>

            <div class="mb-4">
                @php $status = $pesertapkl->status_aktif; @endphp
                <span class="badge {{ $status=='aktif' ? 'bg-success' : ($status=='pending' ? 'bg-warning' : ($status=='selesai' ? 'bg-secondary' : 'bg-danger')) }} bg-opacity-10 {{ $status=='aktif' ? 'text-success' : ($status=='pending' ? 'text-warning' : ($status=='selesai' ? 'text-secondary' : 'text-danger')) }} rounded-pill px-3 py-2 fw-bold" style="font-size: 0.75rem;">
                    STATUS: {{ strtoupper($status) }}
                </span>
            </div>

            <div class="d-grid gap-2">
                <div class="p-3 bg-light rounded-4 text-start">
                    <small class="text-secondary fw-bold d-block mb-1">TANGGAL PKL</small>
                    <div class="fw-bold text-dark small"><i class='bx bx-calendar-plus me-1 text-success'></i> {{ $pesertapkl->tanggal_mulai ? \Carbon\Carbon::parse($pesertapkl->tanggal_mulai)->format('d M Y') : '-' }}</div>
                    <div class="fw-bold text-dark small mt-1"><i class='bx bx-calendar-check me-1 text-danger'></i> {{ $pesertapkl->tanggal_selesai ? \Carbon\Carbon::parse($pesertapkl->tanggal_selesai)->format('d M Y') : '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card-soft p-4">
            <h6 class="fw-bold mb-3 d-flex align-items-center">
                <i class='bx bx-info-circle text-primary me-2'></i> Informasi Akademik
            </h6>
            <div class="mb-3">
                <small class="text-secondary d-block">Institusi / Asal</small>
                <div class="fw-bold text-dark">{{ $pesertapkl->asal_institusi }}</div>
            </div>
            <div class="mb-3">
                <small class="text-secondary d-block">Jurusan</small>
                <div class="fw-bold text-dark">{{ $pesertapkl->jurusan ?? '-' }}</div>
            </div>
            <div class="mb-0">
                <small class="text-secondary d-block">No. HP / WhatsApp</small>
                <div class="fw-bold text-dark">
                    @if($pesertapkl->no_hp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesertapkl->no_hp) }}" target="_blank" class="text-success text-decoration-none">
                            <i class='bx bxl-whatsapp me-1'></i>{{ $pesertapkl->no_hp }}
                        </a>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- QUICK STATS -->
        @php
            $today = \Carbon\Carbon::today();
            $allAbsensi = $pesertapkl->absensi;
            $totalHadir = $allAbsensi->whereIn('status', ['hadir','terlambat'])->count();
            $totalIzin  = $allAbsensi->where('status','izin')->count();
            $totalSakit = $allAbsensi->where('status','sakit')->count();

            // Hitung alpha: hari aktif yang tidak ada absensinya
            $alpha = 0;
            if($pesertapkl->tanggal_mulai) {
                $start = \Carbon\Carbon::parse($pesertapkl->tanggal_mulai);
                $end   = $pesertapkl->tanggal_selesai
                    ? \Carbon\Carbon::parse($pesertapkl->tanggal_selesai)->min($today)
                    : $today;
                for($d = $start->copy(); $d->lte($end); $d->addDay()) {
                    $tgl = $d->format('Y-m-d');
                    $ada = $allAbsensi->first(fn($a) => \Carbon\Carbon::parse($a->tanggal)->format('Y-m-d') === $tgl);
                    if(!$ada) $alpha++;
                }
            }
        @endphp
        <div class="row g-3 mt-1">
            <div class="col-6">
                <div class="card-soft p-3 text-center border-0 shadow-sm">
                    <h3 class="fw-bold text-primary mb-0">{{ $totalHadir }}</h3>
                    <small class="text-secondary fw-bold" style="font-size:0.65rem;">HADIR</small>
                </div>
            </div>
            <div class="col-6">
                <div class="card-soft p-3 text-center border-0 shadow-sm">
                    <h3 class="fw-bold text-info mb-0">{{ $totalIzin }}</h3>
                    <small class="text-secondary fw-bold" style="font-size:0.65rem;">IZIN</small>
                </div>
            </div>
            <div class="col-6">
                <div class="card-soft p-3 text-center border-0 shadow-sm">
                    <h3 class="fw-bold {{ $alpha > 3 ? 'text-danger' : 'text-warning' }} mb-0">{{ $alpha }}</h3>
                    <small class="text-secondary fw-bold" style="font-size:0.65rem;">ALPHA</small>
                </div>
            </div>
            <div class="col-6">
                <div class="card-soft p-3 text-center border-0 shadow-sm">
                    <h3 class="fw-bold text-success mb-0">{{ $pesertapkl->laporanHarian->count() }}</h3>
                    <small class="text-secondary fw-bold" style="font-size:0.65rem;">LAPORAN</small>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: ASSIGNMENT INFO -->
    <div class="col-lg-8">
        <div class="card-soft p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0">Status Penempatan & Pembimbing</h6>
            </div>

            <div class="row g-3">
                <div class="col-md-12">
                    <div class="p-3 bg-light rounded-4 border border-white h-100">
                        <small class="text-secondary fw-bold d-block mb-2">PEMBIMBING INDUSTRI</small>
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-circle bg-white text-primary shadow-xs" style="width: 32px; height: 32px; font-size: 1rem;"><i class='bx bx-user-voice'></i></div>
                            <div class="fw-bold text-dark">{{ $pesertapkl->pembimbing->nama ?? 'Belum Ditentukan' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="p-3 bg-light rounded-4 border border-white">
                        <small class="text-secondary fw-bold d-block mb-2">DIVISI PENEMPATAN</small>
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-circle bg-white text-primary shadow-xs" style="width: 32px; height: 32px; font-size: 1rem;"><i class='bx bx-briefcase-alt-2'></i></div>
                            <div class="fw-bold text-dark">{{ $pesertapkl->divisi->nama_divisi ?? 'Belum Ditentukan' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- TABBED HISTORY: LAPORAN & TUGAS -->
        <div class="card-soft p-4 mt-4">
            <ul class="nav nav-tabs nav-tabs-custom mb-3" id="historyTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-secondary" id="laporan-tab" data-bs-toggle="tab" data-bs-target="#laporan-panel" type="button" role="tab">
                        <i class='bx bx-file me-1'></i> Riwayat Laporan ({{ $pesertapkl->laporanHarian->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-secondary" id="tugas-tab" data-bs-toggle="tab" data-bs-target="#tugas-panel" type="button" role="tab">
                        <i class='bx bx-task me-1'></i> Riwayat Tugas ({{ $pesertapkl->tugas->count() }})
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="historyTabsContent">
                <!-- PANEL LAPORAN -->
                <div class="tab-pane fade show active" id="laporan-panel" role="tabpanel">
                    @if($pesertapkl->laporanHarian->count() > 0)
                        <div class="table-responsive scroll-area" style="max-height: 220px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light sticky-top" style="z-index: 1;">
                                    <tr class="small text-secondary fw-bold">
                                        <th class="ps-3 py-3 border-0">TANGGAL</th>
                                        <th class="py-3 border-0">KEGIATAN & HASIL</th>
                                        <th class="py-3 border-0">DIVERIFIKASI OLEH</th>
                                        <th class="pe-3 py-3 border-0 text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesertapkl->laporanHarian as $lh)
                                        @php
                                            $v = $lh->verifikasiTerakhir;
                                        @endphp
                                        <tr>
                                            <td class="ps-3 py-3 small fw-bold text-dark" style="white-space: nowrap;">
                                                {{ $lh->tanggal ? $lh->tanggal->translatedFormat('d M Y') : '-' }}
                                            </td>
                                            <td class="py-3 small text-dark">
                                                <div class="fw-bold mb-1 text-truncate" style="max-width: 200px;" title="{{ $lh->kegiatan }}">{{ $lh->kegiatan }}</div>
                                                <div class="text-secondary text-truncate" style="max-width: 200px;" title="{{ $lh->hasil }}">{{ $lh->hasil }}</div>
                                            </td>
                                            <td class="py-3 small text-dark">
                                                @if($v && $v->pembimbing)
                                                    <span class="fw-bold text-primary">{{ $v->pembimbing->nama }}</span>
                                                    @if($v->catatan_pembimbing)
                                                        <br><small class="text-muted italic">"{{ $v->catatan_pembimbing }}"</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted italic">-</span>
                                                @endif
                                            </td>
                                            <td class="pe-3 py-3 text-center">
                                                @if($lh->status == 'disetujui')
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold" style="font-size: 0.65rem;">DISETUJUI</span>
                                                @elseif($lh->status == 'revisi')
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill fw-bold" style="font-size: 0.65rem;">REVISI</span>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill fw-bold" style="font-size: 0.65rem;">MENUNGGU</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted small">
                            <i class='bx bx-file fs-1 opacity-25 mb-2'></i>
                            <p class="mb-0 fw-bold">Belum ada laporan harian</p>
                        </div>
                    @endif
                </div>

                <!-- PANEL TUGAS -->
                <div class="tab-pane fade" id="tugas-panel" role="tabpanel">
                    @if($pesertapkl->tugas->count() > 0)
                        <div class="table-responsive scroll-area" style="max-height: 220px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light sticky-top" style="z-index: 1;">
                                    <tr class="small text-secondary fw-bold">
                                        <th class="ps-3 py-3 border-0">JUDUL TUGAS</th>
                                        <th class="py-3 border-0">DEADLINE</th>
                                        <th class="py-3 border-0">DIBERIKAN OLEH</th>
                                        <th class="pe-3 py-3 border-0 text-center">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesertapkl->tugas as $t)
                                        @php
                                            $p = $t->pengumpulan->first();
                                        @endphp
                                        <tr>
                                            <td class="ps-3 py-3 small text-dark">
                                                <div class="fw-bold mb-1 text-truncate" style="max-width: 180px;" title="{{ $t->judul }}">{{ $t->judul }}</div>
                                                <div class="text-secondary text-truncate" style="max-width: 180px;" title="{{ $t->deskripsi }}">{{ $t->deskripsi }}</div>
                                            </td>
                                            <td class="py-3 small text-danger fw-bold" style="white-space: nowrap;">
                                                {{ $t->deadline ? \Carbon\Carbon::parse($t->deadline)->translatedFormat('d M Y') : '-' }}
                                            </td>
                                            <td class="py-3 small text-dark">
                                                <span class="fw-bold text-primary">{{ $t->pembimbing->nama ?? '-' }}</span>
                                            </td>
                                            <td class="pe-3 py-3 text-center">
                                                @if($p)
                                                    @if($p->status == 'terlambat')
                                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill fw-bold" style="font-size: 0.65rem;">TERLAMBAT</span>
                                                    @else
                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold" style="font-size: 0.65rem;">DIKUMPULKAN</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill fw-bold" style="font-size: 0.65rem;">BELUM KUMPUL</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted small">
                            <i class='bx bx-task fs-1 opacity-25 mb-2'></i>
                            <p class="mb-0 fw-bold">Belum ada tugas yang diberikan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- HISTORY DIVISI -->
        <div class="card-soft p-4 mt-4">
            <h6 class="fw-bold mb-3 d-flex align-items-center">
                <i class='bx bx-history text-primary me-2'></i> Riwayat Perubahan Divisi
            </h6>

            @if($pesertapkl->historyDivisi->count() > 0)
                <div class="scroll-area pe-2" style="max-height: 80px; overflow-y: auto;">
                    @foreach($pesertapkl->historyDivisi as $history)
                    <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom: 1px solid #e9ecef;">
                        <div class="flex-shrink-0">
                            <div class="timeline-dot bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class='bx bx-transfer-alt'></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 pt-1">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-2">
                                <div>
                                    <p class="fw-bold text-dark mb-1">
                                        @if($history->divisiLama)
                                            <span class="badge bg-danger bg-opacity-10 text-danger">{{ $history->divisiLama->nama_divisi }}</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Belum Ada Divisi</span>
                                        @endif
                                        <i class='bx bx-chevron-right text-muted'></i>
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ $history->divisiBaru->nama_divisi ?? 'Belum Ada Divisi' }}</span>
                                    </p>
                                    @if($history->keterangan)
                                    <p class="text-secondary small mb-0">{{ $history->keterangan }}</p>
                                    @endif
                                </div>
                                <small class="text-muted fw-bold">
                                    <i class='bx bx-time me-1'></i>
                                    {{ $history->tanggal_perubahan->format('d M Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-light border border-light py-4 text-center mb-0">
                    <i class='bx bx-info-circle text-muted' style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                    <p class="text-muted fw-bold mb-0">Belum ada riwayat perubahan divisi</p>
                </div>
            @endif
        </div>

        @if($pesertapkl->laporanHarian->count() > 0)
        <div class="mt-4 text-end">
            <a href="{{ route('admin.laporanharian.buku', $pesertapkl->uuid) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class='bx bxs-file-pdf me-2'></i> Download Buku Laporan
            </a>
        </div>
        @endif
    </div>
</div>

</div>

<style>
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.nav-tabs-custom {
    border-bottom: 2px solid #f1f5f9;
}
.nav-tabs-custom .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    background: transparent;
    padding: 12px 20px;
    transition: all 0.2s ease;
}
.nav-tabs-custom .nav-link:hover {
    border-bottom: 2px solid #cbd5e1;
    color: var(--bs-primary) !important;
}
.nav-tabs-custom .nav-link.active {
    color: #3b82f6 !important;
    border-bottom: 2px solid #3b82f6;
}
</style>
@endsection
