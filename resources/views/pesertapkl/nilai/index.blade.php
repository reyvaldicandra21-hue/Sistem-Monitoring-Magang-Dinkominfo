@extends('layouts.pesertapkl')

@section('content')



<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center overflow-hidden position-relative">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row position-relative" style="z-index: 1;">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3">
                    <i class='bx bx-award'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Hasil Penilaian PKL</h4>
                    <p class="text-secondary mb-0 small">Lihat pencapaian dan evaluasi dari pembimbing Anda</p>
                </div>
            </div>
            <!-- Decorative circle -->
            <div class="position-absolute end-0 top-0 translate-middle-y" style="width: 150px; height: 150px; background: rgba(59, 130, 246, 0.05); border-radius: 50%; margin-right: -50px;"></div>
        </div>
    </div>
</div>

@if($penilaian)

<div class="row g-4">
    
    <!-- ================= NILAI AKHIR CARD ================= -->
    <div class="col-lg-4">
        <div class="card-blue h-100 d-flex flex-column align-items-center justify-content-center py-5 text-center shadow-lg">
            <div class="mb-2 opacity-75 fw-bold letter-spacing-1">NILAI AKHIR</div>
            <div class="score-display fw-bold mb-2">{{ number_format($penilaian->nilai_akhir, 0) }}</div>
            <div class="badge bg-white text-primary px-4 py-2 rounded-pill fw-bold fs-5 shadow-sm">
                PREDIKAT: {{ $penilaian->predikat }}
            </div>
            
            <div class="mt-5 w-100 px-4">
                <div class="d-flex justify-content-between small mb-1 opacity-75">
                    <span>Performance Rating</span>
                    <span>{{ number_format($penilaian->nilai_akhir, 0) }}%</span>
                </div>
                <div class="progress bg-white bg-opacity-20" style="height: 8px; border-radius: 10px;">
                    <div class="progress-bar bg-white" style="width: {{ $penilaian->nilai_akhir }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= DETAIL NILAI GRID ================= -->
    <div class="col-lg-8">
        <div class="card-soft h-100">
            <h5 class="fw-bold text-dark mb-4">Detail Aspek Penilaian</h5>
            
            <div class="row g-3">
                @php
                    $aspek = [
                        ['label' => 'Kedisiplinan', 'value' => $penilaian->disiplin, 'icon' => 'bx-time', 'color' => 'primary'],
                        ['label' => 'Tanggung Jawab', 'value' => $penilaian->tanggung_jawab, 'icon' => 'bx-shield-check', 'color' => 'success'],
                        ['label' => 'Kerja Sama', 'value' => $penilaian->kerjasama, 'icon' => 'bx-group', 'color' => 'info'],
                        ['label' => 'Etika & Sopan Santun', 'value' => $penilaian->etika, 'icon' => 'bx-user-voice', 'color' => 'warning'],
                        ['label' => 'Inisiatif', 'value' => $penilaian->inisiatif, 'icon' => 'bx-rocket', 'color' => 'danger'],
                    ];
                @endphp

                @foreach($aspek as $item)
                <div class="col-md-6 col-xl-4">
                    <div class="p-3 border rounded-4 transition-hover bg-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-circle bg-{{ $item['color'] }} bg-opacity-10 text-{{ $item['color'] }} me-2">
                                <i class='bx {{ $item['icon'] }}'></i>
                            </div>
                            <span class="fw-bold text-secondary small">{{ $item['label'] }}</span>
                        </div>
                        <div class="d-flex align-items-end justify-content-between">
                            <h3 class="fw-bold text-dark mb-0">{{ $item['value'] ?? '0' }}</h3>
                            <div class="small fw-bold {{ $item['value'] >= 80 ? 'text-success' : ($item['value'] >= 70 ? 'text-warning' : 'text-danger') }}">
                                {{ $item['value'] >= 80 ? 'Sangat Baik' : ($item['value'] >= 70 ? 'Baik' : 'Cukup') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- CATATAN SECTION -->
            <div class="mt-5 pt-4 border-top">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle bg-light text-secondary me-2">
                        <i class='bx bx-message-dots'></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Catatan Pembimbing</h6>
                </div>
                <div class="p-4 bg-light rounded-4 position-relative overflow-hidden">
                    <i class='bx bxs-quote-alt-left position-absolute text-primary opacity-10' style="font-size: 3rem; top: -10px; left: -5px;"></i>
                    <p class="text-secondary mb-0 position-relative" style="line-height: 1.8; font-style: italic;">
                        "{{ $penilaian->catatan ?? 'Tidak ada catatan evaluasi spesifik dari pembimbing.' }}"
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>

@else

<div class="row">
    <div class="col-12">
        <div class="card-soft py-5 text-center">
            <div class="mb-3">
                <i class='bx bx-file-blank' style="font-size: 5rem; opacity: 0.1;"></i>
            </div>
            <h5 class="fw-bold text-dark">Belum Ada Penilaian</h5>
            <p class="text-secondary mb-0 mx-auto" style="max-width: 400px;">
                Nilai Anda sedang diproses oleh pembimbing. Silakan periksa kembali nanti setelah masa PKL berakhir atau setelah pembimbing melakukan evaluasi.
            </p>
        </div>
    </div>
</div>

@endif

</div>



@endsection
