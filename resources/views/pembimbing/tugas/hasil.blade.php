@extends('layouts.pembimbing')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('pembimbing.tugas.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Hasil Penugasan</h5>
                    <small class="text-muted">Pantau pengumpulan tugas dari setiap peserta</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    <!-- ================= LEFT: TASK DETAILS ================= -->
    <div class="col-lg-5">
        <div class="card-soft h-100 shadow-sm border-0">
            <div class="p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                        <i class='bx bx-info-circle'></i>
                    </div>
                    <h6 class="fw-bold mb-0">Informasi Tugas</h6>
                </div>

                <div class="mb-4">
                    <h4 class="fw-bold text-dark mb-2">{{ $tugas->judul }}</h4>
                    <div class="p-3 bg-light rounded-4 text-secondary small" style="line-height: 1.6; white-space: pre-line;">
                        {{ $tugas->deskripsi }}
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <small class="text-secondary fw-bold d-block mb-1">DEADLINE</small>
                        <div class="text-dark fw-bold">
                            <i class='bx bx-calendar text-danger me-1'></i> {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}
                        </div>
                    </div>
                    <div class="col-6">
                        @php
                            $totalKumpul = $tugas->pengumpulan->count();
                            $totalPeserta = $tugas->pesertaPkl->count();
                            $percent = $totalPeserta > 0 ? round(($totalKumpul / $totalPeserta) * 100) : 0;
                        @endphp
                        <small class="text-secondary fw-bold d-block mb-1">PROGRES</small>
                        <div class="text-dark fw-bold">
                            <i class='bx bx-chart text-success me-1'></i> {{ $percent }}% Selesai
                        </div>
                    </div>
                </div>

                @if($tugas->file)
                <div class="mt-4 pt-4 border-top">
                    <label class="text-secondary small fw-bold mb-3 d-block">LAMPIRAN INSTRUKSI</label>
                    <div class="p-3 rounded-4 border border-primary border-opacity-10 transition-all hover-light" style="background-color: #f8fafc;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-circle bg-white shadow-sm text-primary flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.2rem;">
                                <i class='bx bxs-file-blank'></i>
                            </div>
                            <div class="overflow-hidden">
                                <div class="fw-bold text-dark small text-truncate" style="max-width: 100%;">Dokumen_Instruksi.pdf</div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Klik tombol untuk melihat file</small>
                            </div>
                        </div>
                        <a href="{{ Storage::url($tugas->file) }}" target="_blank" class="btn btn-primary btn-sm rounded-pill w-100 fw-bold py-2 shadow-sm">
                            <i class='bx bx-show-alt me-1'></i> Lihat File
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ================= RIGHT: SUBMISSIONS ================= -->
    <div class="col-lg-7">
        <div class="card-soft h-100 shadow-sm border-0">
            <div class="p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                        <i class='bx bx-list-check'></i>
                    </div>
                    <h6 class="fw-bold mb-0">Daftar Pengumpulan Peserta</h6>
                </div>

                <div class="list-group list-group-flush border-0">
                    @foreach($tugas->pesertaPkl as $p)
                        @php
                            $pengumpulan = $tugas->pengumpulan->where('peserta_pkl_id', $p->id)->first();
                        @endphp
                        <div class="list-group-item px-0 py-3 border-bottom border-light">
                            <div class="row align-items-center g-3">
                                <!-- LEFT: USER INFO -->
                                <div class="col-12 col-md-8">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold flex-shrink-0" style="width: 45px; height: 45px; font-size: 1rem; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                            {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate mb-1">{{ $p->user->name }}</div>
                                            @if($pengumpulan)
                                                <div class="text-success fw-medium d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                                    <i class='bx bxs-check-circle'></i> 
                                                    <span>Selesai: {{ \Carbon\Carbon::parse($pengumpulan->created_at)->format('d/m/y H:i') }}</span>
                                                </div>
                                            @else
                                                <div class="text-secondary d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                                    <i class='bx bx-time-five'></i> 
                                                    <span>Belum dikumpulkan</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT: ACTION -->
                                <div class="col-12 col-md-4 text-md-end">
                                    @if($pengumpulan && $pengumpulan->file)
                                        <a href="{{ Storage::url($pengumpulan->file) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-4 py-2 fw-bold w-100 w-md-auto shadow-sm transition-all hover-up">
                                            <i class='bx bx-file me-1'></i> Lihat Hasil
                                        </a>
                                    @else
                                        <div class="bg-light text-secondary border rounded-pill px-3 py-1 small fw-bold d-inline-flex align-items-center">
                                            <i class='bx bx-minus-circle me-1'></i> Kosong
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($pengumpulan && $pengumpulan->catatan)
                                <div class="mt-3 ms-md-5 p-3 bg-light rounded-4 small text-secondary border-start border-3 border-primary border-opacity-25" style="font-style: italic;">
                                    <i class='bx bxs-quote-left fs-6 text-primary opacity-25 me-1'></i> {{ $pengumpulan->catatan }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

</div>

<style>
.icon-circle {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.italic {
    font-style: italic;
}
</style>
@endsection
