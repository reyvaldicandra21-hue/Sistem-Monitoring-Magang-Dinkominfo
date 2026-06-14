@extends('layouts.pembimbing')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('pembimbing.penilaian.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Input Nilai Sikap</h5>
                    <small class="text-muted">Peserta: <span class="fw-bold text-primary">{{ $peserta->user->name }}</span></small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="card-soft p-4 p-md-5 shadow-sm border-0 position-relative overflow-hidden">

            <form action="{{ route('pembimbing.penilaian.update',$peserta->uuid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 position-relative">
                    <div class="col-12 mb-2">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="icon-circle bg-primary bg-opacity-10 text-primary" style="width: 35px; height: 35px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                <i class='bx bx-edit-alt'></i>
                            </div>
                            <h6 class="fw-bold mb-0">Formulir Penilaian (Skala 0-100)</h6>
                        </div>
                    </div>

                    {{-- DISIPLIN --}}
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-4">
                            <label class="form-label fw-bold small text-secondary">DISIPLIN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-timer'></i></span>
                                <input type="number" name="disiplin" class="form-control border-start-0 p-2 fw-bold" min="0" max="100" value="{{ old('disiplin',$penilaian->disiplin) }}" required>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">Ketepatan waktu dan kepatuhan aturan.</small>
                        </div>
                    </div>

                    {{-- TANGGUNG JAWAB --}}
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-4">
                            <label class="form-label fw-bold small text-secondary">TANGGUNG JAWAB</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-check-shield'></i></span>
                                <input type="number" name="tanggung_jawab" class="form-control border-start-0 p-2 fw-bold" min="0" max="100" value="{{ old('tanggung_jawab',$penilaian->tanggung_jawab) }}" required>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">Kesungguhan dalam menyelesaikan tugas.</small>
                        </div>
                    </div>

                    {{-- KERJASAMA --}}
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-4">
                            <label class="form-label fw-bold small text-secondary">KERJASAMA</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-group'></i></span>
                                <input type="number" name="kerjasama" class="form-control border-start-0 p-2 fw-bold" min="0" max="100" value="{{ old('kerjasama',$penilaian->kerjasama) }}" required>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">Kemampuan bekerja dalam tim.</small>
                        </div>
                    </div>

                    {{-- ETIKA --}}
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-4">
                            <label class="form-label fw-bold small text-secondary">ETIKA</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-heart'></i></span>
                                <input type="number" name="etika" class="form-control border-start-0 p-2 fw-bold" min="0" max="100" value="{{ old('etika',$penilaian->etika) }}" required>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">Sopan santun dan perilaku profesional.</small>
                        </div>
                    </div>

                    {{-- INISIATIF --}}
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-4">
                            <label class="form-label fw-bold small text-secondary">INISIATIF</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-bulb'></i></span>
                                <input type="number" name="inisiatif" class="form-control border-start-0 p-2 fw-bold" min="0" max="100" value="{{ old('inisiatif',$penilaian->inisiatif) }}" required>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.65rem;">Kreativitas dan kemandirian dalam bekerja.</small>
                        </div>
                    </div>

                    {{-- CATATAN --}}
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-4">
                            <label class="form-label fw-bold small text-secondary">CATATAN EVALUASI</label>
                            <textarea name="catatan" class="form-control rounded-3 p-3 shadow-sm border-white" rows="4" placeholder="Tuliskan catatan tambahan mengenai performa peserta...">{{ old('catatan',$penilaian->catatan) }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-4 text-center">
                        <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm transition-all hover-up">
                            <i class='bx bx-save me-1'></i> Simpan Penilaian Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<style>
.hover-up:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.transition-all {
    transition: all 0.3s ease;
}
</style>
@endsection
