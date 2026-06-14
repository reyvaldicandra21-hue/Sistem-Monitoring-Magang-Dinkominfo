@extends('layouts.pembimbing')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3 shadow-sm">
                    <i class='bx bx-group'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Daftar Peserta Bimbingan</h4>
                    <p class="text-secondary mb-0 small">Pantau progres dan kelola peserta PKL di bawah bimbingan Anda</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                    <i class='bx bx-user me-1'></i> {{ $peserta->total() }} Peserta Aktif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SEARCH & FILTER -->
<div class="card-soft mb-4 border-0 shadow-sm">
    <form action="" method="GET">
        <div class="row g-3 justify-content-start">
            <div class="col-md-5">
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-search'></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama Peserta atau Email..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm ms-2">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('pembimbing.peserta.index') }}" class="btn btn-light rounded-circle shadow-sm ms-2" title="Reset">
                            <i class='bx bx-refresh'></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<!-- STUDENT GRID -->
<div class="row g-4">
    @forelse($peserta as $p)
    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="card-soft h-100 border-0 shadow-sm hover-up transition-all overflow-hidden">
            <div class="position-absolute top-0 end-0 p-3">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small fw-bold" style="font-size: 0.65rem;">
                    {{ $p->divisi->nama_divisi ?? 'Umum' }}
                </span>
            </div>
            <div class="p-4 text-center">
                <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold mx-auto mb-3" style="width: 70px; height: 70px; font-size: 1.5rem; border: 4px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    {{ strtoupper(substr($p->user->name ?? 'P',0,1)) }}
                </div>
                <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $p->user->name }}">{{ $p->user->name }}</h6>
                <p class="text-secondary small mb-3 text-truncate">{{ $p->user->email }}</p>
                
                <div class="p-2 bg-light rounded-4 mb-4">
                    <small class="text-secondary d-block mb-1 fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">ASAL INSTITUSI</small>
                    <div class="text-dark small fw-bold text-truncate px-2">{{ $p->asal_institusi ?? '-' }}</div>
                </div>

                <a href="{{ route('pembimbing.peserta.show', $p->uuid) }}" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm py-2">
                    <i class='bx bx-show me-1'></i> Detail Progres
                </a>
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
            <p class="text-muted small">Coba cari dengan kata kunci lain atau periksa filter Anda.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- PAGINATION -->
<div class="mt-5">
    {{ $peserta->links() }}
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
</style>
@endsection
