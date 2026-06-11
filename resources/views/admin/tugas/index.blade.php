@extends('layouts.admin')

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
                    <h4 class="fw-bold text-dark mb-1">Monitoring Penugasan</h4>
                    <p class="text-secondary mb-0 small">Pantau distribusi tugas dan status pengumpulan dari seluruh peserta PKL</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SEARCH & FILTER -->
<div class="card-soft mb-4 border-0 shadow-sm">
    <form action="" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold small text-secondary">JUDUL TUGAS</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-search'></i></span>
                    <input type="text" name="judul" class="form-control border-start-0 ps-0" placeholder="Cari judul tugas..." value="{{ request('judul') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-secondary">DEADLINE</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-calendar'></i></span>
                    <input type="date" name="deadline" class="form-control border-start-0 ps-0" value="{{ request('deadline') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-secondary">CARI PESERTA</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-user'></i></span>
                    <input type="text" name="peserta" class="form-control border-start-0 ps-0" placeholder="Nama Peserta..." value="{{ request('peserta') }}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2 h-100 pb-1">
                    <button type="submit" class="btn btn-primary flex-fill rounded-pill fw-bold shadow-sm py-2">Filter</button>
                    @if(request('judul') || request('deadline') || request('peserta'))
                        <a href="{{ route('admin.tugas.index') }}" class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;" title="Reset">
                            <i class='bx bx-refresh fs-4'></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<!-- TASKS TABLE -->
<div class="card-soft p-0 overflow-hidden shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="small text-secondary fw-bold">
                    <th class="ps-4 py-3">JUDUL TUGAS</th>
                    <th class="py-3">DEADLINE</th>
                    <th class="py-3 text-center">PENGUMPULAN</th>
                    <th class="py-3 text-center">STATUS PROGRES</th>
                    <th class="pe-4 py-3 text-end">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tugas as $t)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-dark mb-0">{{ $t->judul }}</div>
                        <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $t->deskripsi }}</small>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="small fw-bold text-dark">{{ \Carbon\Carbon::parse($t->deadline)->format('d M Y') }}</span>
                            @php
                                $isOverdue = \Carbon\Carbon::parse($t->deadline)->isPast() && $t->status_tugas != 'selesai';
                            @endphp
                            @if($isOverdue)
                                <small class="text-danger fw-bold" style="font-size: 0.65rem;"><i class='bx bx-alarm-exclamation'></i> TERLEWAT</small>
                            @else
                                <small class="text-secondary" style="font-size: 0.65rem;">{{ \Carbon\Carbon::parse($t->deadline)->diffForHumans() }}</small>
                            @endif
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="fw-bold text-dark">{{ $t->total_kumpul }} / {{ $t->total_peserta }}</div>
                        <small class="text-muted" style="font-size: 0.65rem;">PESERTA</small>
                    </td>
                    <td class="text-center">
                        @php
                            $badgeClass = [
                                'belum' => 'bg-secondary text-secondary',
                                'sebagian' => 'bg-warning text-warning',
                                'selesai' => 'bg-success text-success'
                            ];
                            $statusLabel = [
                                'belum' => 'BELUM ADA',
                                'sebagian' => 'SEBAGIAN',
                                'selesai' => 'SELESAI'
                            ];
                        @endphp
                        <span class="badge {{ $badgeClass[$t->status_tugas] ?? 'bg-secondary text-secondary' }} bg-opacity-10 rounded-pill px-3 py-1 small fw-bold">
                            {{ $statusLabel[$t->status_tugas] ?? strtoupper($t->status_tugas) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('admin.tugas.hasil', $t->id) }}" class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-3 shadow-xs">
                            <i class='bx bx-show-alt me-1'></i> Lihat Hasil
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted small">Tidak ada data penugasan ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION -->
<div class="mt-4">
    {{ $tugas->links() }}
</div>

</div>


@endsection
