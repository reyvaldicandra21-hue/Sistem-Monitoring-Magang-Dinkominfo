@extends('layouts.pembimbing')

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
                    <h4 class="fw-bold text-dark mb-1">Verifikasi Laporan Harian</h4>
                    <p class="text-secondary mb-0 small">Pantau dan berikan penilaian pada laporan kegiatan peserta PKL</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SEARCH & FILTER -->
<div class="card-soft mb-4 border-0 shadow-sm">
    <form action="" method="GET">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-search'></i></span>
                    <input type="text" name="peserta" class="form-control border-start-0 ps-0" placeholder="Cari Nama Peserta..." value="{{ request('peserta') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-calendar'></i></span>
                    <input type="date" name="tanggal" class="form-control border-start-0 ps-0" value="{{ request('tanggal') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-filter-alt'></i></span>
                    <select name="status" class="form-select border-start-0 ps-0">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm flex-grow-1">
                    Filter
                </button>
                <a href="{{ route('pembimbing.laporanharian.index') }}" class="btn btn-light rounded-circle shadow-sm" title="Reset">
                    <i class='bx bx-refresh'></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- LIST -->
<div class="card-soft p-0 overflow-hidden border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small fw-bold" style="letter-spacing: 1px;">PESERTA</th>
                    <th class="py-3 text-secondary small fw-bold" style="letter-spacing: 1px;">KEGIATAN</th>
                    <th class="py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;">TANGGAL</th>
                    <th class="py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;">STATUS</th>
                    <th class="pe-4 py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $laporan)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold" style="width: 40px; height: 40px; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                {{ strtoupper(substr($laporan->pesertaPkl->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $laporan->pesertaPkl->user->name ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-dark small fw-medium text-truncate" style="max-width: 300px;" title="{{ $laporan->kegiatan }}">
                            {{ $laporan->kegiatan }}
                        </div>
                        @if($laporan->dokumentasi->count())
                        <small class="text-primary d-block mt-1" style="font-size: 0.7rem;">
                            <i class='bx bx-image-alt'></i> {{ $laporan->dokumentasi->count() }} Lampiran
                        </small>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="text-dark small fw-bold">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</div>
                    </td>
                    <td class="text-center">
                        @if($laporan->status == 'menunggu')
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill small fw-bold">Menunggu</span>
                        @elseif($laporan->status == 'disetujui')
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small fw-bold">Disetujui</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill small fw-bold">Revisi</span>
                        @endif
                    </td>
                    <td class="text-center pe-4">
                        <a href="{{ route('pembimbing.laporanharian.show', $laporan->id) }}"
                           class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">
                            <i class='bx bx-show me-1'></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <div class="mb-3">
                            <i class='bx bx-file-blank' style="font-size: 4rem; opacity: 0.1;"></i>
                        </div>
                        <h6 class="fw-bold">Tidak Ada Laporan</h6>
                        <p class="small mb-0">Belum ada laporan yang sesuai dengan kriteria filter.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <!-- PAGINATION -->
    <div class="mt-3">
        {{ $laporans->links() }}
    </div>

</div>

</div>

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
.input-group-modern {
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}
.input-group-modern:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}
.input-group-modern .form-control, 
.input-group-modern .form-select {
    background: transparent;
    border: none;
    font-size: 0.9rem;
    padding: 10px 12px;
}
.input-group-modern .form-control:focus, 
.input-group-modern .form-select:focus {
    box-shadow: none;
}
.input-group-modern .input-group-text {
    border: none;
    background: transparent;
}
</style>

@endsection
