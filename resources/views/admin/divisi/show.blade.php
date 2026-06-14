@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.divisi.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Detail Divisi</h5>
                    <small class="text-muted">Informasi unit kerja dan anggota tim</small>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.divisi.edit', $divisi->uuid) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                    <i class='bx bx-edit-alt me-1'></i> Edit Divisi
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: DIVISION CARD -->
    <div class="col-lg-4">
        <div class="card-soft text-center p-4 mb-4">
            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem; border: 5px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                {{ strtoupper(substr($divisi->nama_divisi ?? 'D',0,1)) }}
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ $divisi->nama_divisi }}</h5>
            <small class="text-secondary fw-bold" style="font-size: 0.75rem;">UNIT KERJA PERUSAHAAN</small>
            
            <hr class="my-4 opacity-10">

            <div class="row g-2">
                <div class="col-6">
                    <div class="p-3 bg-light rounded-4 text-center">
                        <h4 class="fw-bold text-primary mb-0">{{ $divisi->pembimbing->count() }}</h4>
                        <small class="text-secondary fw-bold" style="font-size: 0.6rem;">PEMBIMBING</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded-4 text-center">
                        <h4 class="fw-bold text-success mb-0">{{ $divisi->pesertaPkl->count() }}</h4>
                        <small class="text-secondary fw-bold" style="font-size: 0.6rem;">PESERTA PKL</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-soft p-4 shadow-sm border-0">
            <h6 class="fw-bold mb-3 d-flex align-items-center">
                <i class='bx bx-time text-primary me-2'></i> History
            </h6>
            <div class="p-3 bg-light rounded-4">
                <small class="text-secondary d-block fw-bold mb-1" style="font-size: 0.65rem;">DIBUAT PADA</small>
                <div class="fw-bold text-dark"><i class='bx bx-calendar me-1'></i> {{ $divisi->created_at ? $divisi->created_at->format('d M Y') : '-' }}</div>
            </div>
        </div>
    </div>

    <!-- RIGHT: MEMBERS -->
    <div class="col-lg-8">
        <!-- PEMBIMBING SECTION -->
        <div class="card-soft p-4 mb-4 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0">Daftar Pembimbing di Divisi Ini</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold small">Internal</span>
            </div>
            <div class="scroll-area pe-2" style="max-height: 250px; overflow-y: auto;">
                @forelse($divisi->pembimbing as $pb)
                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-4 mb-2 border border-white hover-up transition-all">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-white text-primary fw-bold" style="width: 40px; height: 40px; font-size: 1rem; border: 2px solid #eef2f6;">
                            {{ strtoupper(substr($pb->nama ?? 'P',0,1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">{{ $pb->nama }}</div>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $pb->user->email }}</small>
                        </div>
                    </div>
                    <a href="{{ route('admin.pembimbing.show', $pb->uuid) }}" class="btn btn-white btn-sm rounded-circle shadow-xs">
                        <i class='bx bx-chevron-right'></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Belum ada pembimbing di divisi ini.</div>
                @endforelse
            </div>
        </div>

        <!-- PESERTA SECTION -->
        <div class="card-soft p-4 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0">Daftar Peserta PKL di Divisi Ini</h6>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold small">Aktif</span>
            </div>
            <div class="scroll-area pe-2" style="max-height: 350px; overflow-y: auto;">
                @forelse($divisi->pesertaPkl as $p)
                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-4 mb-2 border border-white hover-up transition-all">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle bg-white text-success fw-bold" style="width: 40px; height: 40px; font-size: 1rem; border: 2px solid #eef2f6;">
                            {{ strtoupper(substr($p->user->name ?? 'P',0,1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark small">{{ $p->user->name }}</div>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ $p->asal_institusi }}</small>
                        </div>
                    </div>
                    <a href="{{ route('admin.pesertapkl.show', $p->uuid) }}" class="btn btn-white btn-sm rounded-circle shadow-xs">
                        <i class='bx bx-chevron-right'></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Belum ada peserta di divisi ini.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

</div>

<style>
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.btn-white {
    background: white;
    border: 1px solid #e2e8f0;
    color: #475569;
}
.scroll-area::-webkit-scrollbar {
    width: 4px;
}
.scroll-area::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.hover-up:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05) !important;
}
.transition-all {
    transition: all 0.3s ease;
}
</style>
@endsection
