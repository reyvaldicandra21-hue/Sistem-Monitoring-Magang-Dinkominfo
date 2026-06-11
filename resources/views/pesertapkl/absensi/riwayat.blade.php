@extends('layouts.pesertapkl')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1"><i class='bx bx-history text-primary'></i> Riwayat Absensi</h4>
        <small class="text-secondary">Pantau catatan kehadiran Anda di sini</small>
    </div>
</div>

<div class="card-soft mb-4">
    <form method="GET" class="d-flex align-items-end gap-3">
        <div>
            <label class="form-label text-secondary fw-semibold small mb-1">Filter Bulan</label>
            <input type="month" name="bulan" value="{{ $bulan }}" class="form-control" onchange="this.form.submit()" style="max-width: 250px;">
        </div>
        <a href="{{ route('pesertapkl.absensi.index') }}" class="btn btn-outline-secondary ms-auto rounded-pill px-4">
            <i class='bx bx-left-arrow-alt'></i> Kembali
        </a>
    </form>
</div>

<div class="card-soft">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-secondary">
                <tr>
                    <th class="py-3 border-0 rounded-start">Tanggal</th>
                    <th class="py-3 border-0">Status</th>
                    <th class="py-3 border-0">Jam Masuk</th>
                    <th class="py-3 border-0 rounded-end">Jam Pulang</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                @forelse($absensis as $a)
                <tr>
                    <td class="py-3 fw-medium text-dark">
                        <i class='bx bx-calendar-alt text-muted me-1'></i> {{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}
                    </td>
                    <td class="py-3">
                        @if($a->status == 'hadir')
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Hadir</span>
                        @elseif($a->status == 'izin')
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Izin</span>
                        @elseif($a->status == 'sakit')
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Sakit</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">{{ ucfirst($a->status) }}</span>
                        @endif
                    </td>
                    <td class="py-3">
                        @if($a->jam_masuk)
                            <span class="text-dark fw-semibold"><i class='bx bx-time text-success me-1'></i> {{ $a->jam_masuk }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="py-3">
                        @if($a->jam_pulang)
                            <span class="text-dark fw-semibold"><i class='bx bx-time text-warning me-1'></i> {{ $a->jam_pulang }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5 text-muted">
                        <i class='bx bx-folder-open fs-1 mb-2 text-light'></i>
                        <p class="mb-0">Tidak ada riwayat absensi pada bulan ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

@endsection
