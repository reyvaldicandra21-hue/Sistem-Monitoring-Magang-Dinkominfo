@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3 shadow-sm">
                    <i class='bx bx-book-content'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Monitoring Laporan Harian</h4>
                    <p class="text-secondary mb-0 small">Pantau progres kegiatan harian dan status verifikasi seluruh peserta PKL</p>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-primary rounded-pill fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalDownloadBuku">
                    <i class='bx bxs-file-pdf me-2'></i> Download Buku Laporan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SEARCH & FILTER -->
<div class="card-soft mb-4 border-0 shadow-sm">
    <form action="" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold small text-secondary">CARI PESERTA</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-search'></i></span>
                    <input type="text" name="peserta" class="form-control border-start-0 ps-0" placeholder="Nama Peserta..." value="{{ request('peserta') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-secondary">TANGGAL LAPORAN</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-calendar'></i></span>
                    <input type="date" name="tanggal" class="form-control border-start-0 ps-0" value="{{ request('tanggal') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-secondary">STATUS VERIFIKASI</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-check-circle'></i></span>
                    <select name="status" class="form-select border-start-0 ps-0">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="revisi" {{ request('status') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2 h-100 pb-1">
                    <button type="submit" class="btn btn-primary flex-fill rounded-pill fw-bold shadow-sm py-2">Filter</button>
                    @if(request('peserta') || request('tanggal') || request('status'))
                        <a href="{{ route('admin.laporanharian.index') }}" class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;" title="Reset">
                            <i class='bx bx-refresh fs-4'></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<!-- REPORTS TABLE -->
<div class="card-soft p-0 overflow-hidden shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="small text-secondary fw-bold">
                    <th class="ps-4 py-3">PESERTA</th>
                    <th class="py-3">TANGGAL</th>
                    <th class="py-3">KEGIATAN</th>
                    <th class="py-3 text-center">STATUS</th>
                    <th class="pe-4 py-3 text-end">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $l)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                {{ strtoupper(substr($l->pesertaPkl->user->name ?? 'P',0,1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark small text-truncate" style="max-width: 150px;">{{ $l->pesertaPkl->user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="small fw-bold text-dark">{{ \Carbon\Carbon::parse($l->tanggal)->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div class="text-muted small text-truncate" style="max-width: 300px;" title="{{ $l->kegiatan }}">
                            {{ $l->kegiatan }}
                        </div>
                    </td>
                    <td class="text-center">
                        @php
                            $badgeClass = [
                                'menunggu' => 'bg-warning text-warning',
                                'disetujui' => 'bg-success text-success',
                                'revisi' => 'bg-danger text-danger'
                            ];
                        @endphp
                        <span class="badge {{ $badgeClass[$l->status] ?? 'bg-secondary text-secondary' }} bg-opacity-10 rounded-pill px-3 py-1 small fw-bold">
                            {{ strtoupper($l->status) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.laporanharian.show', $l->id) }}" class="btn btn-outline-primary btn-sm rounded-circle shadow-xs" title="Detail">
                                <i class='bx bx-show'></i>
                            </a>
                            <a href="{{ route('admin.laporanharian.download', $l->id) }}" class="btn btn-outline-success btn-sm rounded-circle shadow-xs" title="Download PDF Harian">
                                <i class='bx bx-download'></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted small">Tidak ada laporan harian ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION -->
<div class="mt-4">
    {{ $laporans->links() }}
</div>

<!-- MODAL DOWNLOAD BUKU LAPORAN -->
<div class="modal fade" id="modalDownloadBuku" tabindex="-1" aria-labelledby="modalDownloadBukuLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalDownloadBukuLabel">Pilih Peserta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-secondary small mb-3">Pilih peserta untuk mengunduh seluruh rekapitulasi laporan hariannya dalam format PDF.</p>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Peserta PKL</label>
                    <select id="selectPesertaBuku" class="form-select">
                        <option value="">-- Pilih Peserta --</option>
                        @foreach($pesertaList as $p)
                            <option value="{{ $p->id }}">{{ $p->user->name }} ({{ $p->asal_institusi }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger rounded-pill px-4 fw-bold" onclick="downloadBuku()">
                    <i class='bx bxs-file-pdf me-1'></i> Download PDF
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function downloadBuku() {
    const pesertaId = document.getElementById('selectPesertaBuku').value;
    if (!pesertaId) {
        alert('Silakan pilih peserta terlebih dahulu!');
        return;
    }
    // Redirect ke route download buku
    const url = `/admin/laporan-harian/peserta/${pesertaId}/buku`;
    window.location.href = url;
    
    // Opsional: Tutup modal setelah klik
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalDownloadBuku'));
    if(modal) {
        modal.hide();
    }
}
</script>

</div>

<style>
.icon-box {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: white;
}
.input-group-modern {
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 4px;
    transition: all 0.3s ease;
}
.input-group-modern:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}
.input-group-modern .form-control {
    background: transparent;
    border: none;
    font-size: 0.9rem;
    padding: 8px 12px;
}
.input-group-modern .form-control:focus { box-shadow: none; }
.input-group-modern .input-group-text { border: none; background: transparent; }
.shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
</style>
@endsection
