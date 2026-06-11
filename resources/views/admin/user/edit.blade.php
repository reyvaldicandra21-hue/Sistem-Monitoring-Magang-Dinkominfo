@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 d-flex flex-row justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class='bx bx-left-arrow-alt fs-4'></i>
                </a>
                <div>
                    <h5 class="fw-bold mb-0">Edit Pengguna</h5>
                    <small class="text-muted">Perbarui profil dan hak akses pengguna</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- LEFT: MAIN INFO -->
    <div class="col-lg-7">
        <div class="card-soft p-4 p-md-5 shadow-sm border-0 h-100">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="icon-circle bg-primary bg-opacity-10 text-primary" style="width: 35px; height: 35px; border-radius: 10px;">
                    <i class='bx bx-user'></i>
                </div>
                <h6 class="fw-bold mb-0">Informasi Profil</h6>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label fw-bold small text-secondary">NAMA LENGKAP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-user'></i></span>
                            <input type="text" name="name" class="form-control border-start-0 p-3 fw-bold text-dark" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold small text-secondary">ALAMAT EMAIL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-envelope'></i></span>
                            <input type="email" name="email" class="form-control border-start-0 p-3 fw-bold text-dark" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold small text-secondary">ROLE / HAK AKSES</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-shield-quarter'></i></span>
                            <select name="role" class="form-select border-start-0 p-3 fw-bold text-dark" required>
                                @foreach($roles as $key => $val)
                                    <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm transition-all hover-up">
                            <i class='bx bx-save me-1'></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- RIGHT: PASSWORD RESET -->
    <div class="col-lg-5">
        <div class="card-soft p-4 p-md-5 shadow-sm border-0 bg-light bg-opacity-50">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="icon-circle bg-danger bg-opacity-10 text-danger" style="width: 35px; height: 35px; border-radius: 10px;">
                    <i class='bx bx-lock-open-alt'></i>
                </div>
                <h6 class="fw-bold mb-0">Reset Password</h6>
            </div>

            <p class="text-secondary small mb-4">Ubah password pengguna jika mereka lupa atau ingin meningkatkan keamanan akun.</p>

            <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">PASSWORD BARU</label>
                    <input type="password" name="password" class="form-control p-3 border-0 shadow-sm rounded-4" placeholder="Password Baru" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-secondary">KONFIRMASI PASSWORD</label>
                    <input type="password" name="password_confirmation" class="form-control p-3 border-0 shadow-sm rounded-4" placeholder="Ulangi Password" required>
                </div>
                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-bold shadow-sm transition-all hover-up">
                    <i class='bx bx-refresh me-1'></i> Reset Password Sekarang
                </button>
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
