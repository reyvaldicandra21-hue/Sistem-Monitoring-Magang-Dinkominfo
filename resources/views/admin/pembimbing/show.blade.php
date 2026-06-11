@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.pembimbing.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Detail Profil Pembimbing</h5>
                    <small class="text-muted">Informasi lengkap dan daftar bimbingan aktif</small>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.pembimbing.edit', $pembimbing->id) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
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
                {{ strtoupper(substr($pembimbing->nama ?? 'P',0,1)) }}
            </div>
            <h5 class="fw-bold text-dark mb-1">{{ $pembimbing->nama }}</h5>
            <p class="text-secondary mb-3">{{ $pembimbing->user->email }}</p>
            
            <div class="mb-4">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold" style="font-size: 0.75rem;">
                    PEMBIMBING INDUSTRI
                </span>
            </div>

            <div class="d-grid gap-2">
                <div class="p-3 bg-light rounded-4 text-start">
                    <small class="text-secondary fw-bold d-block mb-1">DIVISI PENGAMPU</small>
                    <div class="fw-bold text-primary"><i class='bx bx-briefcase-alt-2 me-1'></i> {{ $pembimbing->divisi->nama_divisi ?? 'Belum Ditentukan' }}</div>
                </div>
                <div class="p-3 bg-light rounded-4 text-start">
                    <small class="text-secondary fw-bold d-block mb-1">JABATAN</small>
                    <div class="fw-bold text-dark"><i class='bx bx-user-pin me-1 text-info'></i> {{ $pembimbing->jabatan ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="card-soft p-4 shadow-sm text-center">
            <div class="icon-circle bg-primary bg-opacity-10 text-primary mx-auto mb-2" style="width: 40px; height: 40px; font-size: 1.2rem;">
                <i class='bx bx-group'></i>
            </div>
            <h3 class="fw-bold text-dark mb-0">{{ $pembimbing->pesertaPkls->count() }}</h3>
            <small class="text-secondary fw-bold">TOTAL BIMBINGAN</small>
        </div>
    </div>

    <!-- RIGHT: STUDENTS LIST -->
    <div class="col-lg-8">
        <div class="card-soft h-100 p-4 shadow-sm border-0">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0">Daftar Peserta di Bawah Bimbingan</h6>
                <span class="badge bg-light text-primary rounded-pill px-3 py-1 fw-bold small border">Aktif</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="small text-secondary fw-bold">
                            <th class="ps-0 py-3">NAMA PESERTA</th>
                            <th class="py-3">INSTITUSI</th>
                            <th class="py-3 text-center">STATUS</th>
                            <th class="pe-0 py-3 text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembimbing->pesertaPkls as $peserta)
                        <tr>
                            <td class="ps-0">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                        {{ strtoupper(substr($peserta->user->name ?? 'P',0,1)) }}
                                    </div>
                                    <div class="fw-bold text-dark small">{{ $peserta->user->name }}</div>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">{{ $peserta->asal_institusi }}</small>
                            </td>
                            <td class="text-center">
                                @php $status = $peserta->status_aktif; @endphp
                                <span class="badge {{ $status=='aktif' ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 {{ $status=='aktif' ? 'text-success' : 'text-secondary' }} rounded-pill small fw-bold" style="font-size: 0.6rem;">
                                    {{ strtoupper($status) }}
                                </span>
                            </td>
                            <td class="pe-0 text-end">
                                <a href="{{ route('admin.pesertapkl.show', $peserta->id) }}" class="btn btn-outline-primary btn-sm rounded-circle shadow-xs">
                                    <i class='bx bx-chevron-right'></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted small">Belum ada peserta yang di-assign.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>

<style>
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
</style>
@endsection
