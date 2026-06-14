@extends('layouts.pembimbing')

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
                    <h4 class="fw-bold text-dark mb-1">Penilaian Sikap Peserta</h4>
                    <p class="text-secondary mb-0 small">Berikan evaluasi terhadap perilaku dan performa peserta selama PKL</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <div class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold">
                    <i class='bx bx-edit-alt me-1'></i> Input Nilai Akhir
                </div>
            </div>
        </div>
    </div>
</div>

<!-- GRADING TABLE -->
<div class="card-soft p-0 overflow-hidden border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary small fw-bold" style="letter-spacing: 1px; width: 50px;">NO</th>
                    <th class="py-3 text-secondary small fw-bold" style="letter-spacing: 1px;">PESERTA & INSTITUSI</th>
                    <th class="py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;" title="Disiplin">D</th>
                    <th class="py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;" title="Tanggung Jawab">TJ</th>
                    <th class="py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;" title="Kerjasama">K</th>
                    <th class="py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;" title="Etika">E</th>
                    <th class="py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;" title="Inisiatif">I</th>
                    <th class="py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;">NILAI AKHIR</th>
                    <th class="pe-4 py-3 text-secondary small fw-bold text-center" style="letter-spacing: 1px;">AKSI</th>
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
                                {{ strtoupper(substr($peserta->user->name ?? 'P',0,1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $peserta->user->name }}</div>
                                <small class="text-muted d-block">{{ $peserta->asal_institusi }}</small>
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
                        <a href="{{ route('pembimbing.penilaian.edit', $peserta->uuid) }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm transition-all hover-up">
                            <i class='bx bx-edit-alt me-1'></i> {{ isset($p->nilai_akhir) ? 'Update' : 'Input' }}
                        </a>
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
    </div>
</div>

<!-- PAGINATION -->
<div class="mt-4">
    {{ $pesertas->links() }}
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
.hover-up:hover {
    transform: translateY(-2px);
}
.transition-all {
    transition: all 0.3s ease;
}
</style>
@endsection
