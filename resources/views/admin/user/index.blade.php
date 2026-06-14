@extends('layouts.admin')

@section('content')
<div class="container-fluid">

<!-- HEADER -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-soft p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center text-center text-md-start flex-column flex-md-row">
                <div class="icon-box bg-primary mb-3 mb-md-0 me-md-3 shadow-sm">
                    <i class='bx bx-user-circle'></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">Manajemen Pengguna Sistem</h4>
                    <p class="text-secondary mb-0 small">Kelola akun administrator, pembimbing, dan peserta dalam sistem</p>
                </div>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm py-2">
                    <i class='bx bx-plus-circle me-1'></i> Akun Baru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SEARCH & FILTER -->
<div class="card-soft mb-4 border-0 shadow-sm">
    <form action="" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-bold small text-secondary">CARI PENGGUNA</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-search'></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama atau Email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-secondary">FILTER ROLE</label>
                <div class="input-group input-group-modern">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class='bx bx-shield-quarter'></i></span>
                    <select name="role" class="form-select border-start-0 ps-0">
                        <option value="">Semua Role</option>
                        @foreach($roles as $key => $val)
                            <option value="{{ $key }}" {{ request('role') == $key ? 'selected' : '' }}>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2 h-100 pb-1">
                    <button type="submit" class="btn btn-primary flex-fill rounded-pill fw-bold shadow-sm py-2">Filter Akun</button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;" title="Reset">
                            <i class='bx bx-refresh fs-4'></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<!-- USERS TABLE -->
<div class="card-soft p-0 overflow-hidden shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="small text-secondary fw-bold">
                    <th class="ps-4 py-3">PENGGUNA</th>
                    <th class="py-3">ROLE</th>
                    <th class="py-3 text-center">STATUS</th>
                    <th class="pe-4 py-3 text-end">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold" style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $roleClass = [
                                'admin' => 'bg-danger text-danger',
                                'pembimbing' => 'bg-primary text-primary',
                                'pembimbingsekolah' => 'bg-info text-info',
                                'pesertapkl' => 'bg-success text-success'
                            ];
                        @endphp
                        <span class="badge {{ $roleClass[$user->role] ?? 'bg-secondary text-secondary' }} bg-opacity-10 rounded-pill px-3 py-1 small fw-bold">
                            {{ strtoupper($roles[$user->role] ?? $user->role) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <form action="{{ route('admin.users.toggle-status', $user->uuid) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm rounded-pill px-3 py-1 fw-bold border-0 {{ $user->is_active ? 'btn-success' : 'btn-danger' }}"
                                style="font-size: 0.7rem; letter-spacing: 0.5px; min-width: 90px;"
                                {{ auth()->id() == $user->id ? 'disabled' : '' }}
                                title="{{ auth()->id() == $user->id ? 'Tidak bisa menonaktifkan akun sendiri' : 'Klik untuk ubah status' }}">
                                <i class='bx {{ $user->is_active ? "bx-check-circle" : "bx-x-circle" }} me-1'></i>
                                {{ $user->is_active ? 'AKTIF' : 'NONAKTIF' }}
                            </button>
                        </form>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.edit', $user->uuid) }}" class="btn btn-outline-primary btn-sm rounded-circle shadow-xs" title="Edit">
                                <i class='bx bx-edit'></i>
                            </a>
                            @if(auth()->id() != $user->id)
                            <form action="{{ route('admin.users.destroy', $user->uuid) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm rounded-circle shadow-xs" title="Hapus">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted small">Tidak ada data pengguna ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PAGINATION -->
<div class="mt-4">
    {{ $users->links() }}
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
.input-group-modern {
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    padding: 4px;
    transition: all 0.3s ease;
}
.input-group-modern:focus-within {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}
.input-group-modern .form-control {
    background: transparent;
    border: none;
    font-size: 0.9rem;
    padding: 8px 12px;
}
.input-group-modern .form-control:focus {
    box-shadow: none;
}
.input-group-modern .input-group-text {
    border: none;
    background: transparent;
}
.shadow-xs {
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
</style>
@endsection
