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
                    <h5 class="fw-bold mb-0">Edit Divisi</h5>
                    <small class="text-muted">Perbarui nama unit kerja atau departemen</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
        <div class="card-soft p-4 p-md-5 shadow-sm border-0 position-relative overflow-hidden">

            <form action="{{ route('admin.divisi.update', $divisi->uuid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 position-relative">
                    <div class="col-12">
                        <label class="form-label fw-bold small text-secondary">NAMA DIVISI / UNIT KERJA</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-hash'></i></span>
                            <input type="text" name="nama_divisi" class="form-control border-start-0 p-3 fw-bold text-dark" placeholder="Contoh: Divisi IT / Produksi" value="{{ old('nama_divisi', $divisi->nama_divisi) }}" required>
                        </div>
                    </div>

                    <div class="col-12 mt-5">
                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.divisi.index') }}" class="btn btn-light rounded-pill px-5 fw-bold text-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm transition-all hover-up">
                                <i class='bx bx-save me-1'></i> Perbarui Divisi
                            </button>
                        </div>
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
