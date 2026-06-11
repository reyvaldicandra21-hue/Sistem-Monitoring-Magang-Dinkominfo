@extends('layouts.pembimbing')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3 shadow-sm">
                    <i class='bx bx-task'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Manajemen Tugas Peserta</h4>
                    <p class="text-secondary mb-0 small">Berikan instruksi dan pantau progres pengerjaan tugas</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('pembimbing.tugas.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                    <i class='bx bx-plus me-1'></i> Buat Tugas Baru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- MINI CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-soft d-flex align-items-center justify-content-between p-3 border-0 shadow-sm">
            <div>
                <small class="text-secondary fw-bold mb-1 d-block">TOTAL TUGAS</small>
                <h4 class="fw-bold text-dark mb-0">{{ $stats['total'] }}</h4>
            </div>
            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                <i class='bx bx-list-ul'></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-soft d-flex align-items-center justify-content-between p-3 border-0 shadow-sm">
            <div>
                <small class="text-secondary fw-bold mb-1 d-block">SELESAI</small>
                <h4 class="fw-bold text-success mb-0">{{ $stats['selesai'] }}</h4>
            </div>
            <div class="icon-box bg-success bg-opacity-10 text-success">
                <i class='bx bx-check-double'></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-soft d-flex align-items-center justify-content-between p-3 border-0 shadow-sm">
            <div>
                <small class="text-secondary fw-bold mb-1 d-block">DALAM PROGRES</small>
                <h4 class="fw-bold text-warning mb-0">{{ $stats['berjalan'] }}</h4>
            </div>
            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                <i class='bx bx-loader-alt bx-spin-hover'></i>
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
                    <input type="text" name="judul" class="form-control border-start-0 ps-0" placeholder="Cari Judul Tugas..." value="{{ request('judul') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-calendar'></i></span>
                    <input type="date" name="deadline" class="form-control border-start-0 ps-0" value="{{ request('deadline') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-user'></i></span>
                    <input type="text" name="peserta" class="form-control border-start-0 ps-0" placeholder="Cari Nama Peserta..." value="{{ request('peserta') }}">
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm flex-grow-1">
                    Filter
                </button>
                <a href="{{ route('pembimbing.tugas.index') }}" class="btn btn-light rounded-circle shadow-sm" title="Reset">
                    <i class='bx bx-refresh'></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- TASK GRID -->
<div class="row g-4">
    @forelse($tugas as $item)
    @php
        $deadline = \Carbon\Carbon::parse($item->deadline);
        $percent = $item->total_peserta > 0 ? round(($item->total_kumpul / $item->total_peserta) * 100) : 0;
    @endphp
    <div class="col-md-6 col-xl-4">
        <div class="card-soft h-100 border-0 shadow-sm hover-up transition-all">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="flex-grow-1 me-2">
                        <h5 class="fw-bold text-dark mb-1 text-truncate-2" style="min-height: 48px;">{{ $item->judul }}</h5>
                        <small class="text-muted d-block mt-1">
                            <i class='bx bx-calendar-event me-1'></i> Deadline: <span class="fw-bold {{ $deadline->isPast() ? 'text-danger' : 'text-primary' }}">{{ $deadline->format('d M Y') }}</span>
                        </small>
                    </div>
                    @if($item->status == 'selesai')
                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill small fw-bold">SELESAI</span>
                    @elseif($deadline->isPast())
                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill small fw-bold">EXPIRED</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill small fw-bold">AKTIF</span>
                    @endif
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-secondary fw-bold">PROGRES PENGUMPULAN</small>
                        <small class="fw-bold text-primary">{{ $item->total_kumpul }}/{{ $item->total_peserta }}</small>
                    </div>
                    <div class="progress rounded-pill" style="height: 8px;">
                        <div class="progress-bar progress-bar-animated {{ $percent == 100 ? 'bg-success' : 'bg-primary' }}" 
                             role="progressbar" style="width: {{ $percent }}%"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <small class="text-secondary fw-bold d-block mb-2">PESERTA DITUGASKAN</small>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($item->pesertaPkl->take(3) as $p)
                            <div class="d-flex align-items-center bg-light rounded-pill pe-3 shadow-sm border border-white transition-all hover-up" style="padding: 2px;">
                                <div class="avatar-circle bg-primary text-white" style="width: 24px; height: 24px; font-size: 0.65rem;">
                                    {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                </div>
                                <span class="ms-2 fw-bold text-dark" style="font-size: 0.7rem;">{{ explode(' ', trim($p->user->name))[0] }}</span>
                            </div>
                        @endforeach
                        @if($item->pesertaPkl->count() > 3)
                            <div class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill d-flex align-items-center px-3 shadow-sm border border-white" style="font-size: 0.7rem;">
                                +{{ $item->pesertaPkl->count() - 3 }} Lainnya
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="p-3 bg-light d-flex gap-2 border-top">
                <a href="{{ route('pembimbing.tugas.hasil', $item->id) }}" class="btn btn-white btn-sm flex-grow-1 fw-bold rounded-pill shadow-sm">
                    <i class='bx bx-show me-1'></i> Hasil
                </a>
                <a href="{{ route('pembimbing.tugas.edit', $item->id) }}" class="btn btn-white btn-sm px-3 rounded-circle shadow-sm" title="Edit">
                    <i class='bx bx-edit-alt text-warning'></i>
                </a>
                <form action="{{ route('pembimbing.tugas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus tugas ini?')" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-white btn-sm px-3 rounded-circle shadow-sm" title="Hapus">
                        <i class='bx bx-trash text-danger'></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card-soft text-center py-5">
            <div class="mb-3">
                <i class='bx bx-task-x' style="font-size: 4rem; opacity: 0.1;"></i>
            </div>
            <h6 class="fw-bold">Belum Ada Tugas</h6>
            <p class="text-muted small">Klik tombol 'Buat Tugas Baru' untuk memulai.</p>
        </div>
    </div>
    @endforelse
</div>

<!-- PAGINATION -->
<div class="mt-4">
    {{ $tugas->links() }}
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
.input-group-modern .form-control {
    background: transparent;
    border: none;
    font-size: 0.9rem;
    padding: 10px 12px;
}
.input-group-modern .form-control:focus {
    box-shadow: none;
}
.input-group-modern .input-group-text {
    border: none;
    background: transparent;
}
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.hover-up:hover {
    transform: translateY(-5px);
}
.transition-all {
    transition: all 0.3s ease;
}
.btn-white {
    background: white;
    border: 1px solid #e2e8f0;
    color: #475569;
}
.btn-white:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}
</style>
@endsection
