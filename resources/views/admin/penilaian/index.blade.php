@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3 shadow-sm">
                    <i class='bx bx-star'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Rekap Penilaian Peserta</h4>
                    <p class="text-secondary mb-0 small">Pantau nilai sikap dan performa seluruh peserta PKL</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                    <i class='bx bx-group me-1'></i> Semua Peserta
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FILTER -->
<div class="card-soft mb-4 border-0 shadow-sm">
    <form action="" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold small text-secondary">CARI NAMA</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-search'></i></span>
                    <input type="text" name="nama" class="form-control border-start-0 ps-0" placeholder="Nama peserta..." value="{{ request('nama') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-secondary">INSTITUSI</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-buildings'></i></span>
                    <input type="text" name="institusi" class="form-control border-start-0 ps-0" placeholder="Asal institusi..." value="{{ request('institusi') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold small text-secondary">PREDIKAT</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-medal'></i></span>
                    <select name="predikat" class="form-select border-start-0 ps-0">
                        <option value="">Semua</option>
                        <option value="A" {{ request('predikat') == 'A' ? 'selected' : '' }}>A (Sangat Baik)</option>
                        <option value="B" {{ request('predikat') == 'B' ? 'selected' : '' }}>B (Baik)</option>
                        <option value="C" {{ request('predikat') == 'C' ? 'selected' : '' }}>C (Cukup)</option>
                        <option value="D" {{ request('predikat') == 'D' ? 'selected' : '' }}>D (Kurang)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2 h-100 pb-1">
                    <button type="submit" class="btn btn-primary flex-fill rounded-pill fw-bold shadow-sm py-2">Filter</button>
                    @if(request('nama') || request('institusi') || request('predikat'))
                        <a href="{{ route('admin.penilaian.index') }}" class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;" title="Reset">
                            <i class='bx bx-refresh fs-4'></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<!-- TABLE -->
<div class="card-soft p-0 overflow-hidden border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small fw-bold" style="letter-spacing: 1px; width: 50px;">NO</th>
                    <th class="py-3 text-secondary small fw-bold" style="letter-spacing: 1px;">PESERTA & INSTITUSI</th>
                    <th class="py-3 text-secondary small fw-bold text-center" title="Disiplin">D</th>
                    <th class="py-3 text-secondary small fw-bold text-center" title="Tanggung Jawab">TJ</th>
                    <th class="py-3 text-secondary small fw-bold text-center" title="Kerjasama">K</th>
                    <th class="py-3 text-secondary small fw-bold text-center" title="Etika">E</th>
                    <th class="py-3 text-secondary small fw-bold text-center" title="Inisiatif">I</th>
                    <th class="py-3 text-secondary small fw-bold text-center">NILAI AKHIR</th>
                    <th class="pe-4 py-3 text-secondary small fw-bold text-center">PREDIKAT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesertas as $key => $peserta)
                @php $p = $peserta->penilaian; @endphp
                <tr>
                    <td class="ps-4 text-secondary small fw-bold">
                        {{ $pesertas->firstItem() + $key }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold" style="width: 38px; height: 38px; font-size: 0.85rem;">
                                {{ strtoupper(substr($peserta->user->name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $peserta->user->name }}</div>
                                <small class="text-muted d-block">{{ $peserta->asal_institusi ?? '-' }}</small>
                                @if($peserta->pembimbing)
                                    <small class="text-primary" style="font-size: 0.65rem;"><i class='bx bx-user-voice'></i> {{ $peserta->pembimbing->nama }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-center fw-bold text-secondary">{{ $p->disiplin ?? '-' }}</td>
                    <td class="text-center fw-bold text-secondary">{{ $p->tanggung_jawab ?? '-' }}</td>
                    <td class="text-center fw-bold text-secondary">{{ $p->kerjasama ?? '-' }}</td>
                    <td class="text-center fw-bold text-secondary">{{ $p->etika ?? '-' }}</td>
                    <td class="text-center fw-bold text-secondary">{{ $p->inisiatif ?? '-' }}</td>
                    <td class="text-center">
                        @if(isset($p->nilai_akhir))
                            @php $nilai = $p->nilai_akhir; @endphp
                            <span class="badge {{ $nilai >= 85 ? 'bg-success' : ($nilai >= 70 ? 'bg-warning' : 'bg-danger') }} bg-opacity-10 {{ $nilai >= 85 ? 'text-success' : ($nilai >= 70 ? 'text-warning' : 'text-danger') }} px-3 py-2 rounded-pill fw-bold" style="font-size: 0.8rem;">
                                {{ number_format($nilai, 0) }}
                            </span>
                        @else
                            <span class="text-muted small">Belum Dinilai</span>
                        @endif
                    </td>
                    <td class="pe-4 text-center">
                        @if(isset($p->predikat))
                            @php
                                $predikatClass = [
                                    'A' => 'bg-success text-success',
                                    'B' => 'bg-primary text-primary',
                                    'C' => 'bg-warning text-warning',
                                    'D' => 'bg-danger text-danger',
                                ];
                            @endphp
                            <span class="badge {{ $predikatClass[$p->predikat] ?? 'bg-secondary text-secondary' }} bg-opacity-10 rounded-pill px-3 py-2 fw-bold" style="font-size: 0.85rem;">
                                {{ $p->predikat }}
                            </span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="mb-2">
                            <i class='bx bx-notepad' style="font-size: 3rem; opacity: 0.1;"></i>
                        </div>
                        <h6 class="fw-bold text-muted">Belum ada data peserta</h6>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- LEGEND -->
<div class="mt-4 card-soft p-3 border-0 shadow-sm bg-light bg-opacity-50">
    <div class="d-flex align-items-center gap-4 flex-wrap">
        <small class="text-secondary fw-bold">KETERANGAN:</small>
        <small class="text-muted"><span class="fw-bold text-primary">D</span>: Disiplin</small>
        <small class="text-muted"><span class="fw-bold text-primary">TJ</span>: Tanggung Jawab</small>
        <small class="text-muted"><span class="fw-bold text-primary">K</span>: Kerjasama</small>
        <small class="text-muted"><span class="fw-bold text-primary">E</span>: Etika</small>
        <small class="text-muted"><span class="fw-bold text-primary">I</span>: Inisiatif</small>
        <small class="text-muted ms-auto">
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">A</span> ≥ 86 &nbsp;
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2">B</span> ≥ 76 &nbsp;
            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2">C</span> ≥ 66 &nbsp;
            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">D</span> < 66
        </small>
    </div>
</div>

<!-- PAGINATION -->
<div class="mt-4">
    {{ $pesertas->links() }}
</div>

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
    border-color: #4f46e5;
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
}
.input-group-modern .form-control,
.input-group-modern .form-select {
    background: transparent; border: none;
    font-size: 0.9rem; padding: 8px 12px;
}
.input-group-modern .form-control:focus,
.input-group-modern .form-select:focus { box-shadow: none; }
.input-group-modern .input-group-text {
    border: none; background: transparent;
}
</style>
@endsection
