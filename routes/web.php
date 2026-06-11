<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// Admin
use App\Http\Controllers\Admin\PembimbingController;
use App\Http\Controllers\Admin\PesertaPklController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AbsensiAController;
use App\Http\Controllers\Admin\LaporanHarianAController;
use App\Http\Controllers\Admin\TugasAController;
use App\Http\Controllers\Admin\PenilaianAController;
use App\Http\Controllers\Admin\DivisiController;
use App\Http\Controllers\Admin\UserController;

// Pembimbing
use App\Http\Controllers\Pembimbing\PembimbingDashboardController;
use App\Http\Controllers\Pembimbing\PenilaianController;
use App\Http\Controllers\Pembimbing\TugasPController;
use App\Http\Controllers\Pembimbing\VerifikasiLaporanController;
use App\Http\Controllers\Pembimbing\LaporanHarianPController;
use App\Http\Controllers\Pembimbing\AbsensiPController;
use App\Http\Controllers\Pembimbing\PesertaPController;



// Peserta PKL
use App\Http\Controllers\PesertaPKL\PesertaDashboardController;
use App\Http\Controllers\PesertaPKL\AbsensiController;
use App\Http\Controllers\PesertaPKL\LaporanHarianController;
use App\Http\Controllers\PesertaPKL\TugasController;
use App\Http\Controllers\PesertaPKL\NilaiController;



/*
|--------------------------------------------------------------------------
| HALAMAN AWAL & AUTENTIKASI
|--------------------------------------------------------------------------
*/
    Route::get('/', function () {
        return redirect('/login');
    });

   Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

    Route::middleware('auth')->get('/dashboard', function () {
        $role = Auth::user()->role;
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'pembimbing' => redirect()->route('pembimbing.dashboard'),
            'pesertapkl' => redirect()->route('pesertapkl.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::controller(AdminDashboardController::class)->group(function() {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/dashboard/data', 'data');
    });

    // CRUD USER
    Route::resource('users', UserController::class);
    Route::controller(UserController::class)->group(function() {
        Route::post('users/{user}/reset-password', 'resetPassword')->name('users.reset-password');
        Route::post('users/{user}/toggle-status', 'toggleStatus')->name('users.toggle-status');
    });

    // CRUD DATA MASTER
    Route::resource('pembimbing', PembimbingController::class);
    Route::resource('divisi', DivisiController::class);

    // CRUD PESERTA PKL (Kustom)
    Route::controller(PesertaPklController::class)->group(function() {
        Route::get('pesertapkl', 'index')->name('pesertapkl.index');
        Route::get('pesertapkl/create', 'create')->name('pesertapkl.create');
        Route::post('pesertapkl', 'store')->name('pesertapkl.store');
        Route::get('pesertapkl/detail/{id}', 'show')->name('pesertapkl.show'); // 🔥 DETAIL (manual ID)
        Route::get('pesertapkl/{id}/edit', 'edit')->name('pesertapkl.edit');
        Route::put('pesertapkl/{id}', 'update')->name('pesertapkl.update');
        Route::delete('pesertapkl/{id}', 'destroy')->name('pesertapkl.destroy');
        Route::get('peserta-pkl/{pesertapkl}/assign', 'assign')->name('pesertapkl.assign');
        Route::put('peserta-pkl/{pesertapkl}/assign', 'assignUpdate')->name('pesertapkl.assign.update');
        Route::get('pesertapkl/statistik/tahunan', 'statistikTahunan')->name('pesertapkl.statistik.tahunan');
        Route::get('pesertapkl/statistik/bulan/{bulan}', 'statistikBulanan')->name('pesertapkl.statistik.bulanan');
    });

    // ABSENSI
// ABSENSI
    Route::controller(AbsensiAController::class)->prefix('absensi')->name('absensis.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('kalender/{id}/{bulan}', 'kalender');
        Route::get('detail/{id}/{tanggal}', 'detail');
        Route::get('export', 'export')->name('export');
        Route::get('export-excel', 'exportExcel')->name('export.excel');
        Route::get('export-pdf', 'exportPdf')->name('export.pdf');
    });

    // LAPORAN HARIAN
    Route::controller(LaporanHarianAController::class)->prefix('laporan-harian')->name('laporanharian.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('{id}', 'show')->name('show');
        Route::post('{id}/verifikasi', 'verifikasi')->name('verifikasi');
        Route::get('{id}/download', 'download')->name('download');
        Route::get('peserta/{peserta_id}/buku', 'downloadBuku')->name('buku');
    });

    // TUGAS & PENILAIAN
    Route::controller(TugasAController::class)->prefix('tugas')->name('tugas.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('{id}', 'hasil')->name('hasil');
    });

    Route::get('/penilaian', [PenilaianAController::class, 'index'])->name('penilaian.index');

});


/*
|--------------------------------------------------------------------------
| PEMBIMBING INDUSTRI
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pembimbing'])->prefix('pembimbing')->name('pembimbing.')->group(function () {

    Route::get('/dashboard', [PembimbingDashboardController::class, 'index'])->name('dashboard');

    Route::controller(LaporanHarianPController::class)->prefix('laporan-harian')->name('laporanharian.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('{id}', 'show')->name('show');
        Route::put('{id}/approve', 'approve')->name('approve');
        Route::put('{id}/reject', 'reject')->name('reject');
        Route::post('{id}/verifikasi', 'verifikasi')->name('verifikasi');
    });

    Route::controller(PenilaianController::class)->prefix('penilaian')->name('penilaian.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('{id}', 'update')->name('update');
    });

    Route::controller(TugasPController::class)->prefix('tugas')->name('tugas.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('{id}', 'update')->name('update');
        Route::get('{id}/hasil', 'hasil')->name('hasil');
        Route::delete('{id}', 'destroy')->name('destroy');
    });

    Route::controller(AbsensiPController::class)->prefix('absensi')->name('absensi.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('kalender/{id}/{bulan}', 'kalender');
        Route::get('detail/{id}/{tanggal}', 'detail');
        Route::get('export-excel', 'exportExcel')->name('export.excel');
        Route::get('export-pdf', 'exportPdf')->name('export.pdf');
    });

    Route::controller(PesertaPController::class)->prefix('peserta')->name('peserta.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('{id}', 'show')->name('show');
    });

    Route::controller(VerifikasiLaporanController::class)->prefix('verifikasi-laporan')->name('verifikasi.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('{id}', 'show')->name('show');
        Route::post('{id}', 'store')->name('store');
    });

});




/*
|--------------------------------------------------------------------------
| PESERTA PKL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pesertapkl'])->prefix('peserta')->name('pesertapkl.')->group(function () {

    Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');

    Route::controller(AbsensiController::class)->group(function() {
        Route::get('/absensi', 'index')->name('absensi.index');
        Route::post('/absen-masuk', 'absenMasuk')->name('absen.masuk');
        Route::post('/absen-pulang', 'absenPulang')->name('absen.pulang');
        Route::get('/absensi/izin', 'formIzin')->name('absen.formizin');
        Route::post('/absen-izin', 'Izin')->name('absen.izin');
        Route::get('/riwayat-absensi', 'riwayat')->name('absensi.riwayat');
    });

    Route::controller(LaporanHarianController::class)->group(function() {
        Route::get('/laporan', 'index')->name('laporanharian.index');
        Route::get('/laporan/create', 'create')->name('laporanharian.create');
        Route::post('/laporan/store', 'store')->name('laporanharian.store');
        Route::get('/laporan/{id}', 'show')->name('laporanharian.show');
        Route::get('/laporan-harian/{id}/edit', 'edit')->name('laporanharian.edit');
        Route::put('/laporan-harian/{id}', 'update')->name('laporanharian.update');
    });

    Route::controller(TugasController::class)->group(function() {
        Route::get('/tugas', 'index')->name('tugas.index');
        Route::get('/tugas/{id}', 'show')->name('tugas.show');
        Route::get('/tugas/{id}/create', 'create')->name('tugas.create');
        Route::post('/tugas/{id}/kumpul', 'kumpul')->name('tugas.kumpul');
    });

    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
});
