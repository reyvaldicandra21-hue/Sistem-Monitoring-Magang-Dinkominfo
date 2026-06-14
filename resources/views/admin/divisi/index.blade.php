@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3 shadow-sm">
                    <i class='bx bx-buildings'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Manajemen Divisi Perusahaan</h4>
                    <p class="text-secondary mb-0 small">Kelola unit kerja dan struktur organisasi penempatan peserta PKL</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('admin.divisi.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm py-2">
                    <i class='bx bx-plus-circle me-1'></i> Tambah Divisi
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SEARCH & FILTER -->
<div class="card-soft mb-4 border-0 shadow-sm">
    <form action="" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label fw-bold small text-secondary">CARI DIVISI</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-search'></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama Divisi..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2 h-100 pb-1">
                    <button type="submit" class="btn btn-primary flex-fill rounded-pill fw-bold shadow-sm py-2">Cari Data</button>
                    @if(request('search'))
                        <a href="{{ route('admin.divisi.index') }}" class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;" title="Reset">
                            <i class='bx bx-refresh fs-4'></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<!-- DIVISION GRID -->
<div class="row g-4">
    @forelse($divisis as $d)
    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="card-soft h-100 border-0 shadow-sm hover-up transition-all overflow-hidden position-relative">

            <div class="p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="icon-circle bg-primary bg-opacity-10 text-primary" style="width: 45px; height: 45px; font-size: 1.2rem;">
                        <i class='bx bx-hash'></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $d->nama_divisi }}</h6>
                        <small class="text-secondary small fw-bold">DIVISI UNIT</small>
                    </div>
                </div>
                
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-4 text-center">
                            <h5 class="fw-bold text-primary mb-0">{{ $d->pembimbing_count }}</h5>
                            <small class="text-secondary fw-bold" style="font-size: 0.6rem;">PEMBIMBING</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-4 text-center">
                            <h5 class="fw-bold text-success mb-0">{{ $d->peserta_pkl_count }}</h5>
                            <small class="text-secondary fw-bold" style="font-size: 0.6rem;">PESERTA</small>
                        </div>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.divisi.show', $d->uuid) }}" class="btn btn-outline-success btn-sm rounded-pill flex-fill fw-bold" title="Detail">
                        <i class='bx bx-show'></i>
                    </a>
                    <a href="{{ route('admin.divisi.edit', $d->uuid) }}" class="btn btn-outline-primary btn-sm rounded-pill flex-fill fw-bold" title="Edit">
                        <i class='bx bx-edit'></i>
                    </a>
                    <form action="{{ route('admin.divisi.destroy', $d->uuid) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')" class="flex-fill">
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
                <i class='bx bx-buildings' style="font-size: 4rem; opacity: 0.1;"></i>
            </div>
            <h6 class="fw-bold">Tidak Menemukan Divisi</h6>
            <p class="text-muted small">Coba cari dengan kata kunci lain atau tambahkan divisi baru.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- PAGINATION -->
<div class="mt-5">
    {{ $divisis->links() }}
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
.input-group-modern .form-control:focus {
    box-shadow: none;
}
.input-group-modern .input-group-text {
    border: none;
    background: transparent;
}
.hover-up:hover {
    transform: translateY(-5px);
}
.transition-all {
    transition: all 0.3s ease;
}
.opacity-05 {
    opacity: 0.05;
}
</style>
@endsection
